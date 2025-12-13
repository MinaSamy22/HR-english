<?php

namespace App\Http\Controllers;

use App\Exports\JobhistoryExport;
use App\Models\Department;
use App\Models\History;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Exports\JobsExport;
use Maatwebsite\Excel\Facades\Excel;

class JobHistoryController extends Controller
{
public function index(Request $request)
{
    $getRecord = History::getRecord($request);

    // Get branches for filter dropdown
    $branches = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.job-history.list', compact('getRecord', 'branches'));
}

    public function jobs_export(Request $request){    //for export to excel sheet

        return Excel::download(new JobhistoryExport, 'history.xlsx');

    }

public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // if ($branch_id !== null) {
    //     $data['getJobs'] = Job::where('branch_id', $branch_id)->get();
    //     $data['getDepartments'] = Department::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getJobs'] = Job::where('company_id', $company_id)->whereNull('branch_id')->get();
    //     $data['getDepartments'] = Department::where('company_id', $company_id)->whereNull('branch_id')->get();
    // }
        // if ($branch_id !== null) {
    //     $data['getDepartments'] = Department::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getDepartments'] = Department::where('company_id', $company_id)->whereNull('branch_id')->get();
    // }

    // ✅ Always show all company data (no branch restriction)
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();
    $data['getJobs'] = Job::where('company_id', $company_id)->get();


    return view('backend.job-history.add', $data);
}

    public function add_post(Request $request){ //for validation logic ///// any post logic must put in it the validation before saving in data base

    // dd($request->all());
    $history = request()->validate([
    'employee_name'             => 'required',
    'start_date'                => 'required',
    'end_date'                  => 'required',
    'job_id'                    => 'required',
    'department_id'             => 'required'
    ]);

    $history                           = new History;
    $history->employee_name            = trim ($request->employee_name);
    $history->start_date               = trim ($request->start_date);
    $history->end_date                 = trim ($request->end_date);
    $history->job_id                   = trim ($request->job_id);
    $history->department_id            = trim ($request->department_id);
    $history->company_id               = session('company_id'); // Important!

     // Handle company/branch assignment
    $history->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $history->branch_id = session('branch_id');
    }
    $history->save();

return redirect('admin/job_history')->with('success', __('h_job_history.successfully_register'));
    }

    public function restoreEmployee($id)
{
    $history = \App\Models\History::findOrFail($id);

    // ننقل البيانات إلى users
    $user = new \App\Models\User();
    $user->name                 = $history->employee_name;
    $user->email                = $history->email;
    $user->password             = $history->password; // already hashed
    $user->phone_number         = $history->phone_number;
    $user->hire_date            = $history->hire_date;
    $user->birth_date           = $history->birth_date;
    $user->nationality          = $history->nationality;
    $user->country_code         = $history->country_code;
    $user->residency_expiry     = $history->residency_expiry;
    $user->passport_number      = $history->passport_number;
    $user->passport_expiry      = $history->passport_expiry;
    $user->residency_number     = $history->residency_number;
    $user->iban                 = $history->iban;
    $user->residency_job        = $history->residency_job;
    $user->salary_type          = $history->salary_type;
    $user->salary               = $history->salary;
    $user->work_start_time      = $history->work_start_time;
    $user->work_end_time        = $history->work_end_time;
    $user->shift_count          = $history->shift_count;
    $user->second_work_start_time = $history->second_work_start_time;
    $user->second_work_end_time = $history->second_work_end_time;
    $user->macaddress           = $history->macaddress;
    $user->is_biometric         = $history->is_biometric;
    $user->main_salary          = $history->main_salary;
    $user->additional_salary    = $history->additional_salary;
    $user->attachment           = $history->attachment;
    $user->work_hours_per_day   = $history->work_hours_per_day;
    $user->working_days         = $history->working_days;
    $user->vacation_balance     = $history->vacation_balance;
    $user->bonus_per_hour       = $history->bonus_per_hour;
    $user->is_role              = $history->is_role;
    $user->job_id               = $history->job_id;
    $user->department_id        = $history->department_id;
    $user->manager_id           = $history->manager_id;
    $user->company_id           = $history->company_id;
    $user->branch_id            = $history->branch_id;

    $user->save();

    // حذف السجل من جدول histories
    $history->delete();

return redirect()->back()->with('success', __('dashboard.restore_employee'));
}

public function edit($id)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $data['getRecord'] = History::find($id);

    // if ($branch_id !== null) {
    //     $data['getJobs'] = Job::where('branch_id', $branch_id)->get();
    //     $data['getDepartments'] = Department::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getJobs'] = Job::where('company_id', $company_id)->whereNull('branch_id')->get();
    //     $data['getDepartments'] = Department::where('company_id', $company_id)->whereNull('branch_id')->get();
    // }

      // ✅ Always show all company data (no branch restriction)
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();
    $data['getJobs'] = Job::where('company_id', $company_id)->get();


    return view('backend.job-history.edit', $data);
}

    public function edit_update ($id, Request $request){


        $history = History::find($id);

        $history->id                       = trim ($request->id);
        $history->employee_name            = trim ($request->employee_name);
        $history->start_date               = trim ($request->start_date);
        $history->end_date                 = trim ($request->end_date);
        $history->job_id                   = trim ($request->job_id);
        $history->department_id            = trim ($request->department_id);
        $history->save();

        return redirect('admin/job_history')->with('success', __('h_job_history.successfully_update'));
    }

    public function delete($id){
        $recordDelete = History::find($id);
        $recordDelete->delete();
        return redirect()->back()->with('success', __('h_job_history.successfully_deleted'));

    }

public function view($id)
{
    $data['getRecord'] = History::with([
        'job',
        'department',
        'manager'
    ])->findOrFail($id);

    return view('backend.job-history.view', $data);
}



    }
