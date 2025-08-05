<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeVacationController extends Controller
{
public function index(Request $request)
{
    $employeeId = Auth::guard('employee')->id();

    // Get vacation requests for the authenticated employee user
    $vacationRequests = VacationRequest::where('user_id', $employeeId)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Get the employee's company to fetch vacation rules
    $employee = Auth::guard('employee')->user();
    $companyId = $employee->company_id; // Assuming employee has company_id

    // Get total vacation allowed from attendance_rules
    $attendanceRule = DB::table('attendance_rules')
        ->where('company_id', $companyId)
        ->first();

    $totalVacationAllowed = $attendanceRule->vacation_balance ?? 25; // Default to 25 if not found

    // Get total vacations taken from vacations table (approved vacations)
    $vacationsTaken = DB::table('vacations')
        ->where('employee_id', $employeeId)
        ->sum('total') ?? 0;

    // Get pending vacation requests total
    $pendingVacations = DB::table('vacation_requests')
        ->where('user_id', $employeeId)
        ->where('status', 'pending')
        ->sum('total_days') ?? 0;

    // Calculate remaining vacation balance
    $vacationBalance = $totalVacationAllowed - $vacationsTaken;

    // Calculate available balance (excluding pending requests)
    $availableBalance = $vacationBalance - $pendingVacations;

    return view('EmployeeInterface.Requests.vacation.index', compact(
        'vacationRequests',
        'totalVacationAllowed',
        'vacationsTaken',
        'pendingVacations',
        'vacationBalance',
        'availableBalance'
    ));
}

public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'vacation_type'           => 'required|in:annual,sick,emergency,personal,maternity,paternity',
        'start_date'              => 'required|date|after_or_equal:today',
        'end_date'                => 'required|date|after_or_equal:start_date',
        'reason'                  => 'required|string|min:10|max:1000',
        'emergency_contact'       => 'nullable|string|max:255',
        'urgent_request'          => 'nullable|boolean',
    ]);

    // Calculate total days
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);
    $totalDays = $startDate->diffInDays($endDate) + 1;

    // Create the vacation request
    VacationRequest::create([
        'user_id'                   => Auth::guard('employee')->id(),
        'vacation_type'             => $request->vacation_type,
        'start_date'                => $request->start_date,
        'end_date'                  => $request->end_date,
        'reason'                    => $request->reason,
        'emergency_contact'         => $request->emergency_contact,
        'is_urgent'                 => $request->has('urgent_request') ? true : false,
        'status'                    => 'pending',
        'total_days'                => $totalDays,
    ]);

    return redirect()->route('vacation.index')
        ->with('success', 'Vacation request submitted successfully! It will be reviewed by your manager.');
}


public function show($id)
{
    $vacationRequest = VacationRequest::where('user_id', Auth::guard('employee')->id())
        ->findOrFail($id);

    return response()->json($vacationRequest);
}



    public function cancel($id)
{
    $vacationRequest = VacationRequest::where('user_id', Auth::guard('employee')->id())
        ->where('status', 'pending')
        ->findOrFail($id);

    $vacationRequest->delete();

    return redirect()->route('vacation.index')
        ->with('success', 'Vacation request cancelled successfully!');
}

}
