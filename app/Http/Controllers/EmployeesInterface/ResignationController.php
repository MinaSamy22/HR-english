<?php

namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    public function index()
{
    $employeeId = Auth::guard('employee')->id();

    $resignations = Resignation::where('employee_id', $employeeId)
        ->latest()
        ->get();

    $hasApprovedResignation = Resignation::where('employee_id', $employeeId)
        ->whereIn('status', ['pending', 'approved'])
        ->exists();

    return view('EmployeeInterface.Requests.resignation.index', compact(
        'resignations',
        'hasApprovedResignation'
    ));
}


    public function create()
    {
        return view('EmployeeInterface.Requests.resignation.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'resignation_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:500',
        ]);

        Resignation::create([
            'employee_id'          => Auth::guard('employee')->id(),
            'resignation_date'     => $request->resignation_date,
            'type'                 => $request->type,
            'reason'               => $request->reason,
        ]);

         return redirect()->route('employee.resignation.index')
         ->with('success', __('E_resignation.add-message'));
    }

    public function destroy($id)
{
    $resignation = Resignation::where('id', $id)
        ->where('employee_id', Auth::guard('employee')->id())
        ->where('status', 'pending') // only allow deleting pending requests
        ->firstOrFail();

    $resignation->delete();

    return redirect()->route('employee.resignation.index')
    ->with('success', __('E_resignation.delete-message'));
}

}
