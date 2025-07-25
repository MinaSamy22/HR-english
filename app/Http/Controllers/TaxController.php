<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tax;

class TaxController extends Controller
{

    public function index(Request $request)
{
    $companyId = auth()->user()->company_id;

    // Use the model's getRecord method and pass the request and company ID
    $getRecord = Tax::getRecord($request, $companyId);

    // Check if any tax is applied for this company
    $isTaxApplied = Tax::where('company_id', $companyId)
                       ->where('apply_to_payroll', true)
                       ->exists();

    return view('backend.taxes.list', compact('getRecord', 'isTaxApplied'));
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
        return view('backend.taxes.add',$data);
    }

    public function add_post(Request $request)
{
    // Validate first before saving to the database
    $validated = $request->validate([
        'employee_ids'             => 'required|array|min:1',
        'employee_ids.*'           => 'exists:users,id',
        'code'                     => 'required',
        'name'                     => 'required',
        'percent'                  => 'required|numeric|min:0|max:100',
    ]);

    foreach ($validated['employee_ids'] as $employeeId) {
        $tax                      = new Tax;
        $tax->employee_id         = $employeeId;
        $tax->code                = trim($request->code);
        $tax->name                = trim($request->name);
        $tax->percent             = trim($request->percent);
        $tax->company_id          = session('company_id'); // Use session like the previous pattern

        if (session()->has('branch_id')) {
            $tax->branch_id = session('branch_id');
        }

        $tax->save();
    }

    return redirect('admin/taxes')->with('success', 'Tax added for selected employees.');
}



    public function edit($id)
    {
        $data['getRecord'] = Tax::findOrFail($id);
        return view('backend.taxes.edit', $data);
    }

    public function edit_update($id, Request $request)
    {
        $request->validate([
            'code'             => 'required|unique:taxes,code,' . $id,
            'name'             => 'required',
            'percent'          => 'required|numeric|min:0|max:100',
        ]);

        $tax                   = Tax::findOrFail($id);
        $tax->code             = $request->code;
        $tax->name             = $request->name;
        $tax->percent          = $request->percent;
        // Handle company/branch assignment
    $tax->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $tax->branch_id = session('branch_id');
    }
        $tax->save();

        return redirect()->route('taxes')->with('success', 'Tax updated successfully.');
    }

    public function delete($id)
    {
        Tax::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Tax deleted successfully.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No taxes selected.']);
        }

        Tax::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected taxes deleted successfully.']);
    }


    public function toggleCompanyTax(Request $request)
    {
        // Get the logged-in HR's company ID
        $companyId = auth()->user()->company_id;

        // Check if taxes for this company are currently applied to payroll
        $currentlyApplied = Tax::where('company_id', $companyId)
                                ->where('apply_to_payroll', true)
                                ->exists();

        // Toggle: If currently applied, set to false. If not applied, set to true.
        Tax::where('company_id', $companyId)
            ->update(['apply_to_payroll' => !$currentlyApplied]);

        // Optional: flash message to show in Blade
        $message = $currentlyApplied
            ? 'Taxes will no longer be applied to payroll for this company.'
            : 'Taxes are now applied to payroll for this company.';

        return redirect()->back()->with('success', $message);
    }


}
