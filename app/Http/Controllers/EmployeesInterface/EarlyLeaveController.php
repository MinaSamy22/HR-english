<?php

namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\EarlyLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EarlyLeaveController extends Controller
{
    public function index()
    {
    $employeeId = Auth::guard('employee')->id();

    $requests = EarlyLeaveRequest::where('employee_id', $employeeId)
                ->orderBy('id', 'DESC')
                ->paginate(10);

        return view('EmployeeInterface.Requests.Early-Leave.index', compact('requests'));
    }

public function store(Request $request)
{
    $request->validate([
        'request_date' => [
            'required',
            'date',
            Rule::unique('early_leave_requests')->where(function ($query) {
                return $query->where('employee_id', Auth::guard('employee')->id());
            }),
        ],
        'requested_leave_time'  => 'required|date_format:H:i',
        'reason'                => 'required|string|min:4',
], [
        'request_date.unique'   => __('E_early.validation.request_date_unique'),
         ]);
    EarlyLeaveRequest::create([
        'employee_id'           => Auth::guard('employee')->id(),
        'request_date'          => $request->request_date,
        'requested_leave_time'  => $request->requested_leave_time,
        'reason'                => $request->reason,
        'urgent_request'        => $request->urgent_request ? 1 : 0,
        'created_by'            => Auth::guard('employee')->id(),
    ]);

    return redirect()->back()->with('success', __('E_early.request_sent_successfully'));
}

    public function cancel($id)
    {
        $req = EarlyLeaveRequest::where('employee_id', Auth::guard('employee')->id())
                ->where('status', 'pending')
                ->findOrFail($id);

        $req->delete();

    return redirect()->back()->with('success', __('E_early.request_cancelled_successfully'));
    }
}
