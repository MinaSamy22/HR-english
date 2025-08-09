<?php
// app/Http/Controllers/Employee/ExtraTimeRequestController.php

namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraTimeRequest;
use Illuminate\Support\Facades\Auth;

class ExtraTimeRequestController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        $requests = ExtraTimeRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('EmployeeInterface.Requests.extra.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:255',
        ]);

        ExtraTimeRequest::create([
            'employee_id' => Auth::guard('employee')->id(),
            'date' => $request->date,
            'hours' => $request->hours,
            'reason' => $request->reason,
        ]);

        return redirect()->route('employee.extra.index')
            ->with('success', 'Extra time request submitted successfully.');
    }

    public function destroy($id)
{
    $request = ExtraTimeRequest::where('id', $id)
        ->where('employee_id', Auth::guard('employee')->id())
        ->where('status', 'pending')
        ->firstOrFail();

    $request->delete();

    return redirect()->route('employee.extra.index')
        ->with('success', 'Request deleted successfully.');
}


}
