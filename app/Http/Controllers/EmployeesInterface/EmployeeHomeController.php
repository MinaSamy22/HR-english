<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class EmployeeHomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('employee')->user();
        $user->loadMissing('department');

        $now = Carbon::now();
        $startDate = $now->copy()->startOfMonth()->toDateString();
        $endDate   = $now->copy()->endOfMonth()->toDateString();

        // 1. Get recent news for the employee's company (only required fields)
        $recentNews = News::where('company_id', $user->company_id)
            ->select(['id', 'title', 'description', 'image', 'news_date', 'created_at'])
            ->orderBy('news_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // 2. Attendance stats for current month - combined into 1 single indexed query
        $attendanceStats = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('
                COALESCE(SUM(CASE WHEN attendance_type = 1 THEN 1 ELSE 0 END), 0) as present,
                COALESCE(SUM(CASE WHEN attendance_type = 2 THEN 1 ELSE 0 END), 0) as late,
                COALESCE(SUM(CASE WHEN attendance_type = 3 THEN 1 ELSE 0 END), 0) as absent,
                COALESCE(SUM(CASE WHEN attendance_type = 4 THEN 1 ELSE 0 END), 0) as halfday
            ')
            ->first();

        $presentDays = (int) ($attendanceStats->present ?? 0);
        $lateDays    = (int) ($attendanceStats->late ?? 0);
        $absentDays  = (int) ($attendanceStats->absent ?? 0);
        $halfDays    = (int) ($attendanceStats->halfday ?? 0);

        // 3. Vacation balance calculation
        $usedDays = (float) (DB::table('vacations')
            ->where('employee_id', $user->id)
            ->sum('total') ?? 0);

        $vacationBalance = $user->vacation_balance !== null
            ? max(0, $user->vacation_balance - $usedDays)
            : 0;

        // 4. Recent activities from request tables
        $activities = collect();
        $limit = 5;

        $tables = [
            'extra_time_requests' => [
                'type' => 'Extra Time',
                'badge_class' => 'badge-primary',
                'icon' => 'fas fa-clock',
                'employee_column' => 'employee_id'
            ],
            'vacation_requests' => [
                'type' => 'Vacation',
                'badge_class' => 'badge-success',
                'icon' => 'fas fa-umbrella-beach',
                'employee_column' => 'user_id'
            ],
            'resignations' => [
                'type' => 'Resignation',
                'badge_class' => 'badge-danger',
                'icon' => 'fas fa-sign-out-alt',
                'employee_column' => 'employee_id'
            ],
            'late_removal_requests' => [
                'type' => 'Late Removal',
                'badge_class' => 'badge-warning',
                'icon' => 'fas fa-user-clock',
                'employee_column' => 'employee_id'
            ],
            'early_leave_requests' => [
                'type' => 'Early Leave',
                'badge_class' => 'badge-info',
                'icon' => 'fas fa-door-open',
                'employee_column' => 'employee_id'
            ]
        ];

        foreach ($tables as $table => $config) {
            try {
                $results = DB::table($table)
                    ->select([
                        'id',
                        'status',
                        'reason',
                        'created_at',
                        'updated_at',
                        DB::raw("'{$table}' as table_name"),
                        DB::raw("'{$config['type']}' as activity_type"),
                        DB::raw("'{$config['badge_class']}' as badge_class"),
                        DB::raw("'{$config['icon']}' as icon")
                    ])
                    ->where($config['employee_column'], $user->id)
                    ->whereNotNull('status')
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get();

                $activities = $activities->merge($results);
            } catch (\Throwable $e) {
                // Ignore missing table/columns gracefully
            }
        }

        $recentActivities = $activities->sortByDesc('updated_at')->take($limit)->values();

        return view('EmployeeInterface.dashboard.list', compact(
            'user',
            'recentNews',
            'presentDays',
            'lateDays',
            'absentDays',
            'halfDays',
            'vacationBalance',
            'recentActivities'
        ));
    }

    /**
     * Serve news images from the shared HR-Uploads directory
     */
    public function viewNewsImage($filename)
    {
        $path = public_path('../../HR-Uploads/newsimages/' . $filename);

        // Security check: prevent directory traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
            abort(404);
        }

        if (!file_exists($path)) {
            // Return default image or 404
            $defaultImagePath = public_path('dist/img/default-news.png');
            if (file_exists($defaultImagePath)) {
                return response()->file($defaultImagePath, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
            abort(404);
        }

        // Get file info
        $fileInfo = pathinfo($path);
        $extension = strtolower($fileInfo['extension'] ?? '');

        // Set appropriate content type
        $contentType = $this->getImageMimeType($extension);

        return response()->file($path, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
        ]);
    }

    /**
     * Get MIME type based on file extension
     */
    private function getImageMimeType($extension)
    {
        switch ($extension) {
            case 'svg':
                return 'image/svg+xml';
            case 'png':
                return 'image/png';
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            case 'webp':
                return 'image/webp';
            case 'bmp':
                return 'image/bmp';
            case 'ico':
                return 'image/x-icon';
            default:
                return 'image/png';
        }
    }

    /**
     * Get recent news by company ID
     */
    public function getRecentNewsByCompany($companyId, $limit = 4)
    {
        return News::where('company_id', $companyId)
            ->whereDate('news_date', '>=', now()->subDays(30)) // Last 30 days
            ->orderBy('news_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all news by company ID with pagination
     */
    public function getAllNewsByCompany($companyId, $perPage = 10)
    {
        return News::where('company_id', $companyId)
            ->orderBy('news_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Show single news item
     */
    public function showNews(News $news)
    {
        $user = Auth::guard('employee')->user();

        // Check if the news belongs to the employee's company
        if ($news->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to view this news item.');
        }

        return view('EmployeeInterface.news.show', compact('news', 'user'));
    }

    public function show(News $news)
    {
        $user = Auth::guard('employee')->user();
        if ($user && $news->company_id !== $user->company_id) {
            abort(403);
        }
        return view('EmployeeInterface.dashboard.show-news', compact('news'));
    }

    public function logout($request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/employee/login');
    }
}
