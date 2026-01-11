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

    // Get branches for the filter dropdown
    $branches = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    // Check if insurance is applied to payroll
    $isInsuranceApplied = Insurance::where('company_id', $companyId)
                                   ->where('apply_to_payroll', true)
                                   ->exists();

    return view('backend.insurance.list', compact('getRecord', 'isInsuranceApplied', 'branches'));
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

    return view('backend.insurance.add', $data);
}

public function add_post(Request $request)
{
    // ===============================
    // Validation
    // ===============================
    $validated = $request->validate([
        'employee_ids'                   => 'required|array|min:1',
        'employee_ids.*'                 => 'exists:users,id',
        'code'                           => 'required',
        'name'                           => 'required',
        'i_percent'                      => 'required|numeric|min:0|max:100',
        'apply_to_payroll'               => 'required|in:0,1',

        'from_basic'                     => 'boolean',
        'from_housing'                   => 'boolean',
        'from_transportation'            => 'boolean',
        'from_other_allowances'           => 'boolean',

        'basic_percent'                  => 'nullable|numeric|min:0',
        'housing_percent'                => 'nullable|numeric|min:0',
        'transportation_percent'         => 'nullable|numeric|min:0',
        'other_allowances_percent'       => 'nullable|numeric|min:0',
    ]);

    // ===============================
    // Prevent duplicate insurance
    // ===============================
    $insuredEmployees = Insurance::with('employee')
        ->whereIn('employee_id', $validated['employee_ids'])
        ->get();

    if ($insuredEmployees->isNotEmpty()) {
        $employeeNames = $insuredEmployees
            ->pluck('employee.name')
            ->filter()
            ->implode(', ');

        return back()->withInput()->withErrors([
            'employee_ids' => __('h_insurance.employee_already_has_insurance_with_names', [
                'names' => $employeeNames
            ]),
        ]);
    }

    // ===============================
    // Validate total percentage
    // ===============================
    $totalSources =
        (float) $request->input('basic_percent', 0) +
        (float) $request->input('housing_percent', 0) +
        (float) $request->input('transportation_percent', 0) +
        (float) $request->input('other_allowances_percent', 0);

    if (round($totalSources, 2) !== round((float) $request->i_percent, 2)) {
        return back()->withErrors([
            'i_percent' => __('h_insurance.percent_must_equal_total')
        ])->withInput();
    }

    // ===============================
    // Create Insurance
    // ===============================
    foreach ($validated['employee_ids'] as $employeeId) {

        Insurance::create([
            'employee_id' => $employeeId,
            'code' => trim($request->code),
            'name' => trim($request->name),
            'i_percent' => $request->i_percent,
            'apply_to_payroll' => $request->apply_to_payroll,

            'from_basic' => $request->boolean('from_basic'),
            'from_transportation' => $request->boolean('from_transportation'),
            'from_housing' => $request->boolean('from_housing'),
            'from_other_allowances' => $request->boolean('from_other_allowances'),

            'basic_percent' => $request->input('basic_percent', 0),
            'housing_percent' => $request->input('housing_percent', 0),
            'transportation_percent' => $request->input('transportation_percent', 0),
            'other_allowances_percent' => $request->input('other_allowances_percent', 0),

            'company_id' => session('company_id'),
            'branch_id' => session('branch_id'),
        ]);
    }

    return redirect('admin/insurance')
        ->with('success', __('h_insurance.insurance_added_success'));
}






    public function edit($id)
    {
        $data['getRecord'] = Insurance::findOrFail($id);
        return view('backend.insurance.edit', $data);
    }

public function edit_update($id, Request $request)
{
    // ===============================
    // Validation
    // ===============================
    $validated = $request->validate([
        'code'                     => 'required|unique:insurances,code,' . $id,
        'name'                     => 'required',
        'i_percent'                => 'required|numeric|min:0|max:100',
        'apply_to_payroll'         => 'required|in:0,1',

        'from_basic'               => 'boolean',
        'from_transportation'      => 'boolean',
        'from_housing'             => 'boolean',
        'from_other_allowances'    => 'boolean',

        'basic_percent'            => 'nullable|numeric|min:0',
        'housing_percent'          => 'nullable|numeric|min:0',
        'transportation_percent'   => 'nullable|numeric|min:0',
        'other_allowances_percent' => 'nullable|numeric|min:0',
    ]);

    $insurance = Insurance::findOrFail($id);

    // ===============================
    // Validate total percentage
    // ===============================
    $totalSources =
        (float) $request->input('basic_percent', 0) +
        (float) $request->input('housing_percent', 0) +
        (float) $request->input('transportation_percent', 0) +
        (float) $request->input('other_allowances_percent', 0);

    if (round($totalSources, 2) !== round((float) $request->i_percent, 2)) {
        return back()->withErrors([
            'i_percent' => __('h_insurance.percent_must_equal_total')
        ])->withInput();
    }

    // ===============================
    // Update Insurance
    // ===============================
    $insurance->update([
        'code' => trim($request->code),
        'name' => trim($request->name),
        'i_percent' => $request->i_percent,
        'apply_to_payroll' => $request->apply_to_payroll,

        // ✅ checkboxes
        'from_basic' => $request->boolean('from_basic'),
        'from_transportation' => $request->boolean('from_transportation'),
        'from_housing' => $request->boolean('from_housing'),
        'from_other_allowances' => $request->boolean('from_other_allowances'),

        // ✅ percentages
        'basic_percent' => $request->input('basic_percent', 0),
        'housing_percent' => $request->input('housing_percent', 0),
        'transportation_percent' => $request->input('transportation_percent', 0),
        'other_allowances_percent' => $request->input('other_allowances_percent', 0),

        'company_id' => session('company_id'),
        'branch_id' => session('branch_id'),
    ]);

    return redirect()
        ->route('insurance')
        ->with('success', __('h_insurance.insurance_updated_success'));
}



    public function delete($id)
    {
        Insurance::findOrFail($id)->delete();
        return redirect()->back()->with('success', __('h_insurance.insurance_deleted_success'));
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No insurance selected.']);
        }

        Insurance::whereIn('id', $ids)->delete();

        return response()->json(['success' => true,  'message' => __('h_insurance.selected_insurances_deleted_success')]);
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
            ? __('h_insurance.insurances_not_applied_to_payroll')
            : __('h_insurance.insurances_applied_to_payroll');

        return redirect()->back()->with('success', $message);
    }
}
