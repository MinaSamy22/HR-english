<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tax;

class InsuranceController extends Controller
{

    public function index(Request $request)
{
    $companyId = auth()->user()->company_id;

    // Use the model's getRecord method and pass the request and company ID
    $getRecord = Insurance::getRecord($request, $companyId);

    // Check if insurance is applied to payroll
    $isInsuranceApplied = Insurance::where('company_id', $companyId)
                                   ->where('apply_to_payroll', true)
                                   ->exists();

    return view('backend.insurance.list', compact('getRecord', 'isInsuranceApplied'));
}
    public function add()
    {

 $company_id = session('company_id');
    $branch_id = session('branch_id');

    if (!empty($branch_id)) {
        $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
    } else {
        $data['getEmployees'] = User::where('company_id', $company_id)->whereNull('branch_id')->get();
    }

        return view('backend.insurance.add',$data);
    }

    public function add_post(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'employee_ids'             => 'required|array|min:1',
        'employee_ids.*'           => 'exists:users,id',
        'code'                     => 'required',
        'name'                     => 'required',
        'percent'                  => 'required|numeric|min:0|max:100',
    ]);

    foreach ($validated['employee_ids'] as $employeeId) {
        $insurance                = new Insurance;
        $insurance->employee_id  = $employeeId;
        $insurance->code         = trim($request->code);
        $insurance->name         = trim($request->name);
        $insurance->percent      = trim($request->percent);
        $insurance->company_id   = session('company_id'); // Use session company_id

        if (session()->has('branch_id')) {
            $insurance->branch_id = session('branch_id');
        }

        $insurance->save();
    }

    return redirect('admin/insurance')->with('success', 'Insurance added for selected employees.');
}


    public function edit($id)
    {
        $data['getRecord'] = Insurance::findOrFail($id);
        return view('backend.insurance.edit', $data);
    }

    public function edit_update($id, Request $request)
    {
        $request->validate([
            'code'             => 'required|unique:insurances,code,' . $id,
            'name' => 'required',
            'percent' => 'required|numeric|min:0|max:100',
        ]);

        $insurance = Insurance::findOrFail($id);
        $insurance->code = $request->code;
        $insurance->name = $request->name;
        $insurance->percent = $request->percent;
         // Handle company/branch assignment
    $insurance->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $insurance->branch_id = session('branch_id');
    }
        $insurance->save();

        return redirect()->route('insurance')->with('success', 'Insurance updated successfully.');
    }

    public function delete($id)
    {
        Insurance::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Insurance deleted successfully.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No insurance selected.']);
        }

        Insurance::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected insurance deleted successfully.']);
    }

    public function toggleCompanyInsurance(Request $request)
    {
        // Get the logged-in HR's company ID
        $companyId = auth()->user()->company_id;

        // Check if Insurance for this company are currently applied to payroll
        $currentlyApplied = Insurance::where('company_id', $companyId)
                                ->where('apply_to_payroll', true)
                                ->exists();

        // Toggle: If currently applied, set to false. If not applied, set to true.
        Insurance::where('company_id', $companyId)
            ->update(['apply_to_payroll' => !$currentlyApplied]);

        // Optional: flash message to show in Blade
        $message = $currentlyApplied
            ? 'Insurances will no longer be applied to payroll for this company.'
            : 'Insurances are now applied to payroll for this company.';

        return redirect()->back()->with('success', $message);
    }
}
