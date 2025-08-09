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
        $user = Auth::guard('employee')->user();

        // Validate the request
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'reason' => 'required|string|min:10|max:500',
        ]);

        // Check if attendance belongs to the authenticated employee
        $attendance = DB::table('attendances')
            ->where('id', $request->attendance_id)
            ->where('employee_id', $user->id)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Invalid attendance record.');
        }

        // Check if request already exists for this attendance
        $existingRequest = LateRemovalRequest::where('attendance_id', $request->attendance_id)
            ->where('employee_id', $user->id)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Request already submitted for this attendance record.');
        }

        // Create new request
        LateRemovalRequest::create([
            'attendance_id' => $request->attendance_id,
            'employee_id' => $user->id,
            'reason' => trim($request->reason),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Late/Half Day removal request submitted successfully!');

    } catch (\Exception $e) {
        Log::error('Late removal request error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while submitting your request. Please try again.');
    }
}

}
