<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Exports\JobsExport;
use Maatwebsite\Excel\Facades\Excel;

class JobController extends Controller
{
public function index(Request $request)
{
    $getRecord = Job::getRecord($request);

    // Simple way to get branches
    $branches = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

        // Pass the data to the view using compact
        return view('backend.jobss.list', compact('getRecord', 'branches'));
    }


    public function jobs_export(Request $request)
{

    return Excel::download(new JobsExport, 'jobs.xlsx');
}





public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // if ($branch_id !== null) {
    //     $data['getDepartments'] = Department::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getDepartments'] = Department::where('company_id', $company_id)->whereNull('branch_id')->get();
    // }

    // ✅ Always show all company data (no branch restriction)
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();



    return view('backend.jobss.add', $data);
}

    public function add_post(Request $request){ //for validation logic ///// any post logic must put in it the validation before saving in data base

    // dd($request->all());
        $job = request()->validate([
    'job_title'                => 'required',
    'min_salary'               => 'required',
    'max_salary'               => 'required',
    'department_id'            => 'required',
    ]);

    $job                       = new Job;
    $job->job_title            = trim ($request->job_title);
    $job->min_salary           = trim ($request->min_salary);
    $job->max_salary           = trim ($request->max_salary);
    $job->department_id        = trim ($request->department_id);
    $job->company_id           = session('company_id'); // Important!
 // Handle company/branch assignment
    $job->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $job->branch_id = session('branch_id');
    }
    $job->save();

return redirect('admin/jobs')->with('success', __('h_jobs.successfully_register'));
    }


    public function view($id){
        $data['getRecord'] = Job::find($id);
        return view('backend.jobss.view', $data);

    }

public function edit($id)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $data['getRecord'] = Job::find($id);

      // if ($branch_id !== null) {
    //     $data['getDepartments'] = Department::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getDepartments'] = Department::where('company_id', $company_id)->whereNull('branch_id')->get();
    // }

    // ✅ Always show all company data (no branch restriction)
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();

    return view('backend.jobss.edit', $data);
}


    public function edit_update ($id, Request $request){


    $job                       = Job::find($id);
    $job->job_title            = trim ($request->job_title);
    $job->min_salary           = trim ($request->min_salary);
    $job->max_salary           = trim ($request->max_salary);
    $job->department_id        = trim ($request->department_id);
    $job->save();

return redirect('admin/jobs')->with('success', __('h_jobs.successfully_update'));


    }


    public function delete($id){
        $recordDelete = Job::find($id);
        $recordDelete->delete();
return redirect()->back()->with('success', __('h_jobs.successfully_deleted'));

    }

    public function info(Request $request){
        $data['getRecord'] = Job::getRecord($request);    //for reterving jobs data from database and retrive model logic

        return view('backend.jobss.info',$data);

    }

    }
