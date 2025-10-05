<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\Company;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $getRecord = Branch::getRecord($request);

        return view('backend.branches.list', compact('getRecord'));
    }

    public function add()
    {
        return view('backend.branches.add');
    }

    public function add_post(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $branch = new Branch;
        $branch->name = trim($request->name);
        $branch->location = trim($request->location);
        $branch->company_id = session('company_id');
        $branch->is_main = $request->has('is_main') ? 1 : 0;
        $branch->save();

        return redirect('admin/branches')->with('success', __('h_branches.branch_created_success'));
    }

    public function edit($id)
    {
        $data['getRecord'] = Branch::findOrFail($id);
        return view('backend.branches.edit', $data);
    }

    public function edit_update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->name = trim($request->name);
        $branch->location = trim($request->location);
        $branch->is_main = $request->has('is_main') ? 1 : 0;
        $branch->save();

        return redirect('admin/branches')->with('success', __('h_branches.branch_updated_success'));
    }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->back()->with('success', __('h_branches.branch_deleted_success'));
    }

public function assignEmployee(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:users,id',
        'branch_id' => 'required',
    ]);

    $company_id = session('company_id');
    $userIds = $request->user_ids;
    $branchId = $request->branch_id;

    // Verify users belong to same company
    $users = User::whereIn('id', $userIds)
                 ->where('company_id', $company_id)
                 ->get();

    if ($users->count() !== count($userIds)) {
        return redirect()->back()->with('error', 'One or more employees not found or do not belong to your company.');
    }

    // If main branch selected => set branch_id = null
    if ($branchId === 'main') {
        User::whereIn('id', $userIds)
            ->where('company_id', $company_id)
            ->update(['branch_id' => null]);

        $branchName = __('h_branches.main_branch');
    } else {
        // validate that branch exists in company
        if (!Branch::where('id', $branchId)->where('company_id', $company_id)->exists()) {
            return redirect()->back()->with('error', 'Invalid branch selection.');
        }

        User::whereIn('id', $userIds)
            ->where('company_id', $company_id)
            ->update(['branch_id' => $branchId]);

        $branchName = Branch::find($branchId)->name;
    }

    $employeeCount = count($userIds);

    if ($employeeCount === 1) {
        $message = __('h_branches.employee_transferred_success', ['branch' => $branchName]);
    } else {
        $message = __('h_branches.employees_transferred_success', [
            'count' => $employeeCount,
            'branch' => $branchName
        ]);
    }

    return redirect()->back()->with('success', $message);
}


public function showTransferForm()
{
    $company_id = session('company_id');

    $branches = Branch::where('company_id', $company_id)->get();

    $employees = User::where('company_id', $company_id)
                     ->with('branch')
                     ->get();

    return view('backend.branches.transfer', compact('branches', 'employees'));
}
}
