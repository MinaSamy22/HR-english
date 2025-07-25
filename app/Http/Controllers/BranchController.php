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

        return redirect('admin/branches')->with('success', 'Branch successfully created.');
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

        return redirect('admin/branches')->with('success', 'Branch successfully updated.');
    }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->back()->with('error', 'Branch deleted successfully.');
    }

    public function assignEmployee(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:users,id',
        'branch_id' => 'required|exists:branches,id',
    ]);

    $company_id = session('company_id');
    $userIds = $request->user_ids;
    $branchId = $request->branch_id;

    // Verify that all users belong to the same company for security
    $users = User::whereIn('id', $userIds)
                 ->where('company_id', $company_id)
                 ->get();

    if ($users->count() !== count($userIds)) {
        return redirect()->back()->with('error', 'One or more employees not found or do not belong to your company.');
    }

    // Update all selected employees
    User::whereIn('id', $userIds)
        ->where('company_id', $company_id)
        ->update(['branch_id' => $branchId]);

    $branchName = Branch::find($branchId)->name;
    $employeeCount = count($userIds);

    $message = $employeeCount === 1
        ? "Employee transferred to {$branchName} successfully."
        : "{$employeeCount} employees transferred to {$branchName} successfully.";

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
