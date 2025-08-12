<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;


class ManagerController extends Controller
{
public function index(Request $request){
    $data['getRecord'] = Manager::getRecord();     //for reterving managers data from database and retrive model logic
    return view('backend.managers.list',$data);

}

public function add(Request $request){
    $company_id = session('company_id');

    //$data['getJobs'] = Job::get();
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();

    return view('backend.managers.add',$data);

}
public function add_post(Request $request){         //for validation logic ///// any post logic must put in it the validation before saving in data base

// dd($request->all());
    $manager = request()->validate([

'name'                         => 'required',
'email'                        => 'required|unique:managers',
'phone_number'                 => 'required',
'hire_date'                    => 'required',
'salary'                       => 'required',
'commission_pct'               => 'required',

]);

$manager                       = new Manager;
$manager->name                 = trim ($request->name);
$manager->email                = trim ($request->email);
$manager->phone_number         = trim ($request->phone_number);
$manager->hire_date            = trim ($request->hire_date);
$manager->salary               = trim ($request->salary);
$manager->commission_pct       = trim ($request->commission_pct);
$manager->company_id           = session('company_id'); // Important!

        // Handle company/branch assignment
    $manager->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $manager->branch_id = session('branch_id');
    }
$manager->save();

return redirect('admin/manager')->with('success', __('h_manager.manager_register_success'));
}


public function view($id){
    $data['getRecord'] = Manager::find($id);
    return view('backend.managers.view', $data);

}

public function edit($id){

    $data['getRecord'] = Manager::find($id);
    // $data['getJobs'] = Job::get(); //for the link
    // $data['getDepartments'] = Department::get();
    return view('backend.managers.edit', $data);

}

public function edit_update ($id, Request $request){


    $manager = request()->validate([
        'email' => 'required|unique:managers,email,'.$id
     ]);

    $manager = Manager::find($id);

    // $manager                    = new Manager; de lma bt3ml add bs
    $manager->name                 = trim ($request->name);
    $manager->email                = trim ($request->email);
    $manager->phone_number         = trim ($request->phone_number);
    $manager->hire_date            = trim ($request->hire_date);
    $manager->salary               = trim ($request->salary);
    $manager->commission_pct       = trim ($request->commission_pct);
    $manager->company_id           = session('company_id'); // Important!

    $manager->save();

return redirect('admin/manager')->with('success', __('h_manager.manager_update_success'));}



public function delete($id){
    $recordDelete = Manager::find($id);
    $recordDelete->delete();
return redirect()->back()->with('success', __('h_manager.manager_delete_success'));
}

public function info(Request $request){
    $data['getRecord'] = Manager::getRecord();
    return view('backend.managers.info',$data);

}

}
