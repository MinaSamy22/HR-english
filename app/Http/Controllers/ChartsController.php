<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\Manager;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChartsController extends Controller
{

public function index(Request $request)
{
    $branch_id = session('branch_id');
    $company_id = session('company_id');

    // Helper filter to reuse
    $filter = function ($query) use ($branch_id, $company_id) {
        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        } else {
            $query->where('company_id', $company_id);
        }
    };

    // Count of all employees, jobs, managers, departments
    $data['getEmployeeCount'] = User::where($filter)->count();
    $data['getJobsCount'] = Job::where($filter)->count();
    $data['getManagersCount'] = Manager::where($filter)->count();
    $data['getDepartmentCount'] = Department::where($filter)->count();

    // Departments with user count
    $departments = Department::where($filter)->withCount('users')->get();
    $data['departmentNames'] = $departments->pluck('department_name');
    $data['employeeCounts'] = $departments->pluck('users_count');

    // Current month
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    // Overtime by employee
    $overtimeData = Time::with('user')
        ->selectRaw('employee_id, SUM(hours) as total_hours')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->whereHas('user', function ($query) use ($branch_id, $company_id) {
            if ($branch_id) {
                $query->where('branch_id', $branch_id);
            } else {
                $query->where('company_id', $company_id);
            }
        })
        ->groupBy('employee_id')
        ->get();

    $data['employeeNames'] = $overtimeData->map(function ($time) {
        return optional($time->user)->name ?? 'Unknown';
    })->toArray();

    $data['overtimeHours'] = $overtimeData->pluck('total_hours')->toArray();

    return view('backend.dashboard.charts', $data);
}


}
