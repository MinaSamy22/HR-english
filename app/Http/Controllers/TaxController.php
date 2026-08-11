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

    // Add branches data like in your other controllers
    $branches = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.taxes.list', compact('getRecord', 'isTaxApplied', 'branches'));
}




public function add()
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // If branch_id is null, show all employees for the company
    if (empty($branch_id)) {
        $data['getEmployees'] = User::where('company_id', $company_id)->get();
    } else {
        // Check if the current branch is the main branch
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        // If it's the main branch (is_main == 1), show all employees for the company
        if ($currentBranch && $currentBranch->is_main == 1) {
            $data['getEmployees'] = User::where('company_id', $company_id)->get();
        } else {
            // Otherwise, filter by the specific branch_id
            $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
        }
    }

    return view('backend.taxes.add', $data);
}

public function add_post(Request $request)
{
    $request->validate([
        'employee_ids' => 'required|array|min:1',
        'employee_ids.*' => 'exists:users,id',

        'code' => 'required',
        'name' => 'required',
        'percent' => 'required|numeric|min:0|max:100',

        'basic_percent' => 'nullable|numeric|min:0',
        'housing_percent' => 'nullable|numeric|min:0',
        'transportation_percent' => 'nullable|numeric|min:0',
        'other_allowances_percent' => 'nullable|numeric|min:0',
    ]);

    $total =
        ($request->basic_percent ?? 0) +
        ($request->housing_percent ?? 0) +
        ($request->transportation_percent ?? 0) +
        ($request->other_allowances_percent ?? 0);

    if ($total != $request->percent) {
        return back()->withErrors([
            'percent' => __('h_tax.percent_must_equal_total')
        ])->withInput();
    }

    foreach ($request->employee_ids as $employeeId) {

        Tax::create([
            'employee_id' => $employeeId,
            'company_id'  => session('company_id'),
            'branch_id'   => session('branch_id'),
 
            'code'    => trim($request->code),
            'name'    => trim($request->name),
            'percent' => $request->percent,

            'apply_to_payroll' => $request->apply_to_payroll,

            // ✅ checkbox flags
            'from_basic'            => $request->filled('basic_percent'),
            'from_housing'          => $request->filled('housing_percent'),
            'from_transportation'   => $request->filled('transportation_percent'),
            'from_other_allowances' => $request->filled('other_allowances_percent'),

            // ✅ percent values
            'basic_percent'            => $request->basic_percent ?? 0,
            'housing_percent'          => $request->housing_percent ?? 0,
            'transportation_percent'   => $request->transportation_percent ?? 0,
            'other_allowances_percent' => $request->other_allowances_percent ?? 0,
        ]);
    }

    return redirect('admin/taxes')->with('success', __('h_tax.tax_added_success'));
}

 


    public function edit($id)
    {
        $data['getRecord'] = Tax::findOrFail($id);
        return view('backend.taxes.edit', $data);
    }

public function edit_update($id, Request $request)
{
    $request->validate([
        'code'    => 'required|unique:taxes,code,' . $id,
        'name'    => 'required',
        'percent' => 'required|numeric|min:0|max:100',

        'basic_percent' => 'nullable|numeric|min:0',
        'housing_percent' => 'nullable|numeric|min:0',
        'transportation_percent' => 'nullable|numeric|min:0',
        'other_allowances_percent' => 'nullable|numeric|min:0',

        'apply_to_payroll' => 'required|in:0,1',
    ]);

    // ✅ validate sum
    $total =
        ($request->basic_percent ?? 0) +
        ($request->housing_percent ?? 0) +
        ($request->transportation_percent ?? 0) +
        ($request->other_allowances_percent ?? 0);

    if ($total != $request->percent) {
        return back()->withErrors([
            'percent' => __('h_tax.percent_must_equal_total')
        ])->withInput();
    }

    $tax = Tax::findOrFail($id);

    $tax->update([
        'code'    => trim($request->code),
        'name'    => trim($request->name),
        'percent' => $request->percent,

        'apply_to_payroll' => $request->apply_to_payroll,

        // ✅ checkbox flags (auto true if percent exists)
        'from_basic'            => $request->filled('basic_percent'),
        'from_housing'          => $request->filled('housing_percent'),
        'from_transportation'   => $request->filled('transportation_percent'),
        'from_other_allowances' => $request->filled('other_allowances_percent'),

        // ✅ stored percent values
        'basic_percent'            => $request->basic_percent ?? 0,
        'housing_percent'          => $request->housing_percent ?? 0,
        'transportation_percent'   => $request->transportation_percent ?? 0,
        'other_allowances_percent' => $request->other_allowances_percent ?? 0,

        'company_id' => session('company_id'),
        'branch_id'  => session('branch_id'),
    ]);

    return redirect('admin/taxes')->with('success', __('h_tax.tax_updated_success'));
}


    public function delete($id)
    {
        Tax::findOrFail($id)->delete();
        return redirect()->back()->with('success', __('h_tax.tax_deleted_success'));
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No taxes selected.']);
        }

        Tax::whereIn('id', $ids)->delete();


        return response()->json(['success' => true,  'message' => __('h_tax.selected_taxes_deleted_success')]);
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

        // Localized flash message
        $message = $currentlyApplied
            ? __('h_tax.taxes_not_applied_to_payroll')
            : __('h_tax.taxes_applied_to_payroll');

        return redirect()->back()->with('success', $message);
    }


}
