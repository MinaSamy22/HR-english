<?php
namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;
use App\Models\Vacation; // Add this import
use App\Models\Time; // Add this import
use App\Models\Attendance; // Add this import

class RequestController extends Controller
{
public function index()
{
    // Get current company ID and branch ID from session
    $companyId = session('company_id');
    $branchId = session('branch_id');

    // Determine filtering logic based on branch_id and is_main
    $showAllCompanyRequests = false;
    $filterBranchId = null;

    if ($branchId) {
        $currentBranch = Branch::find($branchId);
        if ($currentBranch && $currentBranch->is_main == 1) {
            // Main branch - show all company requests
            $showAllCompanyRequests = true;
        } else {
            // Regular branch - show only this branch's requests
            $filterBranchId = $branchId;
        }
    } else {
        // No branch_id in session - show all company requests
        $showAllCompanyRequests = true;
    }

    // Create a closure for the user filtering logic
    $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
        if ($showAllCompanyRequests) {
            $query->where('company_id', $companyId);
        } else {
            $query->where('branch_id', $filterBranchId);
        }
    };

    // Get pending requests with consistent filtering
    $pendingVacations = VacationRequest::where('status', 'pending')
        ->whereHas('user', $userFilterClosure)
        ->with('user')
        ->get();

    $pendingExtraTimes = ExtraTimeRequest::where('status', 'pending')
        ->whereHas('user', $userFilterClosure)
        ->with('user')
        ->get();

    $pendingResignations = Resignation::where('status', 'pending')
        ->whereHas('user', $userFilterClosure)
        ->with('user')
        ->get();

    $pendingLateRemovals = LateRemovalRequest::where('status', 'pending')
        ->whereHas('user', $userFilterClosure)
        ->with('user')
        ->get();

    return view('backend.requests.pending', compact(
        'pendingVacations',
        'pendingExtraTimes',
        'pendingResignations',
        'pendingLateRemovals'
    ));
}

    public function processed(Request $request)
    {
        // Get current company ID from session
        $companyId = session('company_id');

        // Get filter parameters
        $selectedMonth = $request->get('month');
        $searchName = $request->get('search_name');

        // Build processed requests queries with filters and company restriction
        $processedVacationsQuery = VacationRequest::whereIn('status', ['accepted', 'rejected'])
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedExtraTimesQuery = ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedResignationsQuery = Resignation::whereIn('status', ['accepted', 'rejected'])
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedLateRemovalsQuery = LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with('user')
            ->orderBy('updated_at', 'desc');

        // Apply month filter if selected
        if ($selectedMonth) {
            $year = date('Y');
            $month = $selectedMonth;

            $processedVacationsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedExtraTimesQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedResignationsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedLateRemovalsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);
        }

        // Apply name search filter if provided
        if ($searchName) {
            $processedVacationsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
                $query->where('name', 'LIKE', '%' . $searchName . '%')
                      ->where('company_id', $companyId);
            });

            $processedExtraTimesQuery->whereHas('user', function($query) use ($searchName, $companyId) {
                $query->where('name', 'LIKE', '%' . $searchName . '%')
                      ->where('company_id', $companyId);
            });

            $processedResignationsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
                $query->where('name', 'LIKE', '%' . $searchName . '%')
                      ->where('company_id', $companyId);
            });

            $processedLateRemovalsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
                $query->where('name', 'LIKE', '%' . $searchName . '%')
                      ->where('company_id', $companyId);
            });
        }

        // Execute queries
        $processedVacations = $processedVacationsQuery->get();
        $processedExtraTimes = $processedExtraTimesQuery->get();
        $processedResignations = $processedResignationsQuery->get();
        $processedLateRemovals = $processedLateRemovalsQuery->get();

        // Generate months list for filter dropdown
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('backend.requests.processed', compact(
            'processedVacations',
            'processedExtraTimes',
            'processedResignations',
            'processedLateRemovals',
            'months',
            'selectedMonth',
            'searchName'
        ));
    }

    public function accept($type, $id)
    {
        $model = $this->getModelInstance($type, $id);

        // Verify the request belongs to the current company before processing
        if (!$this->belongsToCurrentCompany($model)) {
            abort(403, 'Unauthorized access to this request.');
        }

        $model->status = 'accepted';
        $model->save();

        // If it's a vacation request, save to vacations table
        if ($type === 'vacation') {
            $this->saveVacationToVacationsTable($model);
        }

        // If it's an extra time request, save to times table
        if ($type === 'extra_time') {
            $this->saveExtraTimeToTimesTable($model);
        }

        // If it's a late removal request, update attendance
        if ($type === 'late_removal') {
            $this->updateAttendanceForLateRemoval($model);
        }

        return back()->with('success', __('h_requests.accept message'));
    }

    public function reject($type, $id)
    {
        $model = $this->getModelInstance($type, $id);

        // Verify the request belongs to the current company before processing
        if (!$this->belongsToCurrentCompany($model)) {
            abort(403, 'Unauthorized access to this request.');
        }

        $model->status = 'rejected';
        $model->save();

        return back()->with('success', __('h_requests.reject message'));
    }

    /**
     * Check if the request belongs to the current company
     */
    private function belongsToCurrentCompany($model)
    {
        $companyId = session('company_id');

        // Load the user relationship if not already loaded
        if (!$model->relationLoaded('user')) {
            $model->load('user');
        }

        return $model->user && $model->user->company_id == $companyId;
    }

    private function getModelInstance($type, $id)
    {
        switch ($type) {
            case 'vacation':
                return VacationRequest::with('user')->findOrFail($id);
            case 'extra_time':
                return ExtraTimeRequest::with('user')->findOrFail($id);
            case 'resignation':
                return Resignation::with('user')->findOrFail($id);
            case 'late_removal':
                return LateRemovalRequest::with('user')->findOrFail($id);
            default:
                abort(404);
        }
    }

    /**
     * Save accepted vacation request to vacations table
     */
    private function saveVacationToVacationsTable(VacationRequest $vacationRequest)
    {
        // Check if vacation already exists to prevent duplicates
        $existingVacation = Vacation::where('employee_id', $vacationRequest->user_id)
            ->where('start_date', $vacationRequest->start_date)
            ->where('end_date', $vacationRequest->end_date)
            ->first();

        if (!$existingVacation) {
            // Calculate total days if not available in request
            $totalDays = $vacationRequest->total ??
                        $vacationRequest->total_days ??
                        $vacationRequest->days ??
                        $this->calculateVacationDays($vacationRequest->start_date, $vacationRequest->end_date);

            // Get company_id from user relationship if not in request
            $companyId = $vacationRequest->company_id ??
                        $vacationRequest->user->company_id ??
                        null;

            // Get branch_id from user relationship if not in request
            $branchId = $vacationRequest->branch_id ??
                       $vacationRequest->user->branch_id ??
                       null;

            Vacation::create([
                'employee_id' => $vacationRequest->user_id,
                'start_date' => $vacationRequest->start_date,
                'end_date' => $vacationRequest->end_date,
                'total' => $totalDays,
                'vacation_type' => $vacationRequest->vacation_type,
                'company_id' => $companyId,
                'branch_id' => $branchId,
            ]);
        }
    }

    /**
     * Calculate vacation days between start and end date
     */
    private function calculateVacationDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Add 1 to include both start and end date
        return $start->diffInDays($end) + 1;
    }

    /**
     * Save accepted extra time request to times table
     */
    private function saveExtraTimeToTimesTable(ExtraTimeRequest $extraTimeRequest)
    {
        // Try different possible field names for employee ID
        $employeeId = $extraTimeRequest->user_id ??
                     $extraTimeRequest->employee_id ??
                     $extraTimeRequest->emp_id ??
                     null;

        // Try different possible field names for hours
        $hours = $extraTimeRequest->hours ??
                $extraTimeRequest->extra_hours ??
                $extraTimeRequest->bonus_hours ??
                $extraTimeRequest->overtime_hours ??
                null;

        // Get the date from extra time request
        $requestDate = $extraTimeRequest->date ??
                      $extraTimeRequest->request_date ??
                      $extraTimeRequest->work_date ??
                      $extraTimeRequest->created_at ??
                      now();

        // Debug logging (remove after fixing)
        \Log::info('ExtraTimeRequest data:', [
            'all_data' => $extraTimeRequest->toArray(),
            'employee_id' => $employeeId,
            'hours' => $hours,
            'request_date' => $requestDate,
        ]);

        if (!$employeeId) {
            \Log::error('Employee ID is null for ExtraTimeRequest ID: ' . $extraTimeRequest->id);
            return;
        }

        if (!$hours) {
            \Log::error('Hours is null for ExtraTimeRequest ID: ' . $extraTimeRequest->id);
            return;
        }

        // Check if time entry already exists to prevent duplicates
        $existingTime = Time::where('employee_id', $employeeId)
            ->where('hours', $hours)
            ->whereDate('created_at', Carbon::parse($requestDate)->toDateString())
            ->first();

        if (!$existingTime) {
            // Get company_id from user relationship if not in request
            $companyId = $extraTimeRequest->company_id ??
                        $extraTimeRequest->user->company_id ??
                        null;

            // Get branch_id from user relationship if not in request
            $branchId = $extraTimeRequest->branch_id ??
                       $extraTimeRequest->user->branch_id ??
                       null;

            $timeEntry = new Time([
                'employee_id' => $employeeId,
                'hours' => $hours,
                'company_id' => $companyId,
                'branch_id' => $branchId,
            ]);

            // Set the created_at to the date from the extra time request
            $timeEntry->created_at = Carbon::parse($requestDate);
            $timeEntry->updated_at = now(); // Keep updated_at as current time
            $timeEntry->save();

            \Log::info('Successfully saved time entry for employee: ' . $employeeId . ' with date: ' . $requestDate);
        } else {
            \Log::info('Time entry already exists, skipping duplicate.');
        }
    }

    /**
     * Update attendance when late removal request is accepted
     */
    private function updateAttendanceForLateRemoval(LateRemovalRequest $lateRemovalRequest)
    {
        // Get employee ID and date from the late removal request
        $employeeId = $lateRemovalRequest->employee_id;
        $attendanceDate = $lateRemovalRequest->day; // Using the 'day' column you added

        // Debug logging
        \Log::info('LateRemovalRequest data:', [
            'request_id' => $lateRemovalRequest->id,
            'employee_id' => $employeeId,
            'attendance_date' => $attendanceDate,
            'attendance_id' => $lateRemovalRequest->attendance_id,
        ]);

        if (!$employeeId) {
            \Log::error('Employee ID is null for LateRemovalRequest ID: ' . $lateRemovalRequest->id);
            return;
        }

        if (!$attendanceDate) {
            \Log::error('Attendance date is null for LateRemovalRequest ID: ' . $lateRemovalRequest->id);
            return;
        }

        // Find the attendance record using the attendance_id from the request (more reliable)
        $attendance = null;

        if ($lateRemovalRequest->attendance_id) {
            $attendance = Attendance::find($lateRemovalRequest->attendance_id);
        }

        // If not found by ID, try to find by employee_id and date
        if (!$attendance) {
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('attendance_date', Carbon::parse($attendanceDate)->toDateString())
                ->first();
        }

        if ($attendance) {
            // Update attendance_type to 1 (present)
            $attendance->attendance_type = '1';
            $attendance->save();

            \Log::info('Successfully updated attendance for employee: ' . $employeeId . ' on date: ' . $attendanceDate);
        } else {
            \Log::error('No attendance record found for employee: ' . $employeeId . ' on date: ' . $attendanceDate);

            // Optionally, you could create a new attendance record here
            // But it's better to log the error since the attendance should already exist
        }
    }
}
