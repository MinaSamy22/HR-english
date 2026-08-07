<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Job;
use App\Models\Manager;
use App\Models\Time;
use App\Models\User;
use App\Models\Vacation;
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
        $data['departmentNames'] = $departments->pluck('department_name')->toArray();
        $data['employeeCounts'] = $departments->pluck('users_count')->toArray();

        // Jobs with user count
        $jobs = Job::where($filter)->withCount('users')->get();
        $data['jobTitles'] = $jobs->map(function ($job) {
            return $job->job_title ?? $job->name ?? ('Role #' . $job->id);
        })->toArray();
        $data['jobUserCounts'] = $jobs->pluck('users_count')->toArray();

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

        // Today's Attendance breakdown
        $currentDate = now()->format('Y-m-d');
        $attendanceBase = Attendance::join('users', 'users.id', 'attendances.employee_id')
            ->where('attendances.attendance_date', $currentDate);

        if ($branch_id) {
            $attendanceBase->where('users.branch_id', $branch_id);
        } else {
            $attendanceBase->where('users.company_id', $company_id);
        }

        $data['todayPresent'] = (clone $attendanceBase)->where('attendance_type', 1)->count();
        $data['todayLate'] = (clone $attendanceBase)->where('attendance_type', 2)->count();
        $data['todayAbsent'] = (clone $attendanceBase)->where('attendance_type', 3)->count();
        $data['todayHalfday'] = (clone $attendanceBase)->where('attendance_type', 4)->count();

        // Annual Attendance trends (12 Months)
        $year = now()->year;
        $monthlyPresent = [];
        $monthlyAbsences = [];
        $monthlyVacations = [];

        foreach (range(1, 12) as $month) {
            $attQ = Attendance::join('users', 'users.id', 'attendances.employee_id')
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date', $month);

            if ($branch_id) {
                $attQ->where('users.branch_id', $branch_id);
            } else {
                $attQ->where('users.company_id', $company_id);
            }

            $monthlyPresent[] = (clone $attQ)->where('attendance_type', 1)->count();
            $monthlyAbsences[] = (clone $attQ)->where('attendance_type', 3)->count();

            $vacQ = Vacation::join('users', 'users.id', 'vacations.employee_id')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month);

            if ($branch_id) {
                $vacQ->where('users.branch_id', $branch_id);
            } else {
                $vacQ->where('users.company_id', $company_id);
            }

            $monthlyVacations[] = $vacQ->distinct('employee_id')->count('employee_id');
        }

        $data['monthlyPresent'] = $monthlyPresent;
        $data['monthlyAbsences'] = $monthlyAbsences;
        $data['monthlyVacations'] = $monthlyVacations;

        return view('backend.dashboard.charts', $data);
    }
}
