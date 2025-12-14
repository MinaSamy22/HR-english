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
    $company_id = session('company_id');

    // Get the authenticated employee
    $employee = Auth::guard('employee')->user();
    $companyId = $employee->company_id;

    // Get vacation requests for the authenticated employee
    $vacationRequests = VacationRequest::where('user_id', $employeeId)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Get total vacation balance directly from users table
    $totalVacationAllowed = $employee->vacation_balance ?? 0;

    // Calculate used days (approved vacations from vacations table)
    $usedDays = \App\Models\Vacation::where('employee_id', $employeeId)->sum('total');

    // Calculate remaining vacation balance
    $vacationBalance = $employee->vacation_balance !== null
        ? $employee->vacation_balance - $usedDays
        : 0;

    // Get count of pending vacation requests
    $pendingVacations = VacationRequest::where('user_id', $employeeId)
        ->where('status', 'pending')
        ->count();

    // Get total days for pending requests
    $pendingVacationDays = VacationRequest::where('user_id', $employeeId)
        ->where('status', 'pending')
        ->sum('total_days') ?? 0;

    // Calculate available balance (excluding pending requests)
    $availableBalance = $vacationBalance - $pendingVacationDays;

    return view('EmployeeInterface.Requests.vacation.index', compact(
        'vacationRequests',
        'totalVacationAllowed',
        'usedDays',
        'pendingVacations',
        'pendingVacationDays',
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
         ->with('success', __('E_vacations.add-message'));
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
         ->with('success', __('E_vacations.delete-message'));
}

}
