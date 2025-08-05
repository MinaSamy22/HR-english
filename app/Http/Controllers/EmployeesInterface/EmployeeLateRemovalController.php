<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LateRemovalRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeLateRemovalController extends Controller
{

public function index()
{
    $user = Auth::guard('employee')->user();
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // Fetch summary counts using attendance_type IDs
    $presentDays = DB::table('attendances')
        ->where('employee_id', $user->id)
        ->where('attendance_type', 1) // present
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->count();

    $lateDays = DB::table('attendances')
        ->where('employee_id', $user->id)
        ->where('attendance_type', 2) // late
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->count();

    $absentDays = DB::table('attendances')
        ->where('employee_id', $user->id)
        ->where('attendance_type', 3) // absent
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->count();

    $halfDays = DB::table('attendances')
        ->where('employee_id', $user->id)
        ->where('attendance_type', 4) // half_day
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->count();

    // Late & half_day attendances (for current month)
    $lateOrHalfDayAttendances = DB::table('attendances')
        ->where('employee_id', $user->id)
        ->whereIn('attendance_type', [2, 4])
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->get();

    // Requests already submitted
    $requests = LateRemovalRequest::where('employee_id', $user->id)->get()->keyBy('attendance_id');

    // Vacation balance
    $totalVacationAllowed = DB::table('attendance_rules')
        ->where('company_id', $user->company_id)
        ->value('vacation_balance') ?? 0;

    $vacationsTaken = DB::table('vacations')
        ->where('employee_id', $user->id)
        ->sum('total') ?? 0;

    $vacationBalance = $totalVacationAllowed - $vacationsTaken;

    return view('EmployeeInterface.Requests.late.index', compact(
        'presentDays',
        'lateDays',
        'absentDays',
        'halfDays',
        'vacationBalance',
        'lateOrHalfDayAttendances',
        'requests'
    ));
}

public function store(Request $request)
{
    try {
        // Debug: Log incoming request data
        Log::info('Late Removal Request - Incoming data:', $request->all());
Log::info('Late Removal Request - Employee ID', ['employee_id' => Auth::guard('employee')->id()]);

        // Validate the request
        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'reason' => 'nullable|string|max:255',
        ]);

        Log::info('Late Removal Request - Validation passed:', $validated);

        // Check if already requested
        $alreadyRequested = LateRemovalRequest::where('employee_id', Auth::guard('employee')->id())
            ->where('attendance_id', $request->attendance_id)
            ->exists();

        if ($alreadyRequested) {
            Log::warning('Late Removal Request - Already requested for attendance_id:', $request->attendance_id);
            return back()->with('error', 'You have already requested removal for this record.');
        }

        // Create the request
        $lateRemovalRequest = LateRemovalRequest::create([
            'attendance_id' => $request->attendance_id,
            'employee_id' => Auth::guard('employee')->id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        Log::info('Late Removal Request - Successfully created:', $lateRemovalRequest->toArray());

        return redirect()->back()->with('success', 'Your request has been sent to HR.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Late Removal Request - Validation error:', $e->errors());
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Late Removal Request - General error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Failed to submit request. Please try again or contact support.');
    }
}

}
