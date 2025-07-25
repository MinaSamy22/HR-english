<?php

namespace App\Http\Controllers;
use App\Models\Vacation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VacationController extends Controller
{
    // Method to display the list of vacations
    public function index(Request $request){
        $data['getRecord'] = Vacation::getRecord($request); // Retrieving vacation data from the database
        return view('backend.vacations.index', $data);
    }

    // Method to show the add vacation form
    public function add(Request $request){
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    if (!empty($branch_id)) {
        $data['getUsers'] = User::where('branch_id', $branch_id)->get();
    } else {
        $data['getUsers'] = User::where('company_id', $company_id)->whereNull('branch_id')->get();
    }

        return view('backend.vacations.add', $data);
    }

    // Method to handle the submission of the add vacation form
public function add_post(Request $request)
{
    $validatedData = $request->validate([
        'employee_id'    => 'required|exists:users,id',
        'vacation_type'  => 'required',
        'start_date'     => 'required|date',
        'end_date'       => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    $totalDays = $endDate->diffInDays($startDate) + 1;

    $employee = User::with('company.attendanceSetting')->find($request->employee_id);

    if (!$employee || !$employee->company || !$employee->company->attendanceSetting) {
        return back()->withErrors(['error' => 'Invalid employee or company settings not found.']);
    }

    $vacationLimit = $employee->company->attendanceSetting->vacation_balance ?? 25;

    $totalUsed = Vacation::where('employee_id', $employee->id)
        ->sum('total');

    $remainingBalance = max(0, $vacationLimit - $totalUsed);

    if ($remainingBalance <= 0) {
        return back()->withErrors(['error' => 'You have exhausted your allowed vacation balance. ']);
    }

    if ($totalDays > $remainingBalance) {
        return back()->withErrors(['error' => "You are trying to request $totalDays days, but the remaining balance is only $remainingBalance days. Vacation request denied."]);
    }

    $vacation = new Vacation();
    $vacation->employee_id   = trim($request->employee_id);
    $vacation->vacation_type = trim($request->vacation_type);
    $vacation->start_date    = $startDate;
    $vacation->end_date      = $endDate;
    $vacation->total         = $totalDays;
    $vacation->company_id    = session('company_id');

// Handle company/branch assignment
    $vacation->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $vacation->branch_id = session('branch_id');
    }
    $vacation->save();

    return redirect('admin/vacations')->with('success', 'Vacation successfully registered.');
}


    // Method to delete a vacation record
    public function delete($id){
        $recordDelete = Vacation::find($id);
        if ($recordDelete) {
            $recordDelete->delete();
            return redirect()->back()->with('success', 'Record successfully deleted.');
        } else {
            return redirect()->back()->with('error', 'Record not found.');
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No vacations selected.']);
        }

        Vacation::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected vacations deleted successfully.']);
    }
}
