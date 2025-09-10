<?php

namespace App\Http\Controllers;

use App\Models\Administration;
use App\Models\Department;
use App\Models\Job;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;


class DepartmentController extends Controller
{


public function index(Request $request)
{
    // Call the model's getRecord function, passing the $request for search functionality.
    $getRecord = Department::getRecord($request);
    $branches = $this->getAvailableBranches(); // Add branches for dropdown

    // Pass the data to the view using compact
    return view('backend.departments.list', compact('getRecord', 'branches'));
}

// Add this method to get available branches for departments
public function getAvailableBranches()
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $branchesQuery = \DB::table('branches')
        ->where('company_id', $company_id)
        ->select('id', 'name', 'is_main');

    // Apply same branch access logic as getRecord method
    if (!empty($branch_id)) {
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if (!($currentBranch && $currentBranch->is_main == 1)) {
            // If current branch is not main, only show current branch
            $branchesQuery->where('id', $branch_id);
        }
    }

    return $branchesQuery->orderBy('name')->get();
}

public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    if ($branch_id !== null) {
        $data['getManagers'] = Manager::where('branch_id', $branch_id)->get();
        $data['getAdministration'] = Administration::where('branch_id', $branch_id)->get();
    } else {
        $data['getManagers'] = Manager::where('company_id', $company_id)->whereNull('branch_id')->get();
        $data['getAdministration'] = Administration::where('company_id', $company_id)->whereNull('branch_id')->get();
    }

    return view('backend.departments.add', $data);
}

public function add_post(Request $request){         //for validation logic ///// any post logic must put in it the validation before saving in data base

// dd($request->all());
    $department = request()->validate([

'department_name'                 => 'required',
'manager_id'                      => 'required',
'administration_id'               => 'required',
'location'                        => 'required',

]);

$department                          = new Department;
$department->department_name         = trim ($request->department_name);
$department->manager_id              = trim ($request->manager_id);
$department->administration_id       = trim ($request->administration_id);
$department->location                = trim ($request->location);
$department->company_id              = session('company_id'); // Important!

 // Handle company/branch assignment
    $department->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $department->branch_id = session('branch_id');
    }
$department->save();

return redirect('admin/department')->with('success', __('h_department.department_added'));
}


public function view($id){
    $data['getRecord'] = Department::find($id);
    return view('backend.departments.view', $data);

}

public function edit($id)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $data['getRecord'] = Department::find($id);

    if ($branch_id !== null) {
        $data['getManagers'] = Manager::where('branch_id', $branch_id)->get();
        $data['getAdministration'] = Administration::where('branch_id', $branch_id)->get();
    } else {
        $data['getManagers'] = Manager::where('company_id', $company_id)->whereNull('branch_id')->get();
        $data['getAdministration'] = Administration::where('company_id', $company_id)->whereNull('branch_id')->get();
    }

    return view('backend.departments.edit', $data);
}


public function edit_update ($id, Request $request){

    $department = Department::find($id);

// $department                       = new Department; de lma bt3ml add bs

$department->department_name         = trim ($request->department_name);
$department->manager_id              = trim ($request->manager_id);
$department->administration_id       = trim ($request->administration_id);
$department->location                = trim ($request->location);

$department->save();

return redirect('admin/department')->with('success', __('h_department.department_updated'));
}


public function delete($id){
    $recordDelete = Department::find($id);
    $recordDelete->delete();
return redirect()->back()->with('error', __('h_department.record_deleted'));

}

public function info(Request $request){
    $data['getRecord'] = Department::getRecord($request);
    return view('backend.departments.info',$data);

}
}
