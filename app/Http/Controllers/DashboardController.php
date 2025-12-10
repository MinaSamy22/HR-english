<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRule;
use App\Models\Branch;
use App\Models\Department;
use App\Models\News;
use App\Models\User;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{

public function dashboard(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');
    $currentDate = now()->format('Y-m-d');

    // Determine filtering logic based on branch_id and is_main
    $showAllCompanyEmployees = false;
    $filterBranchId = null;

    if ($branch_id) {
        $currentBranch = Branch::find($branch_id);
        if ($currentBranch && $currentBranch->is_main == 1) {
            // Main branch - show all company employees
            $showAllCompanyEmployees = true;
        } else {
            // Regular branch - show only this branch's employees
            $filterBranchId = $branch_id;
        }
    } else {
        // No branch_id in session - show all company employees
        $showAllCompanyEmployees = true;
    }

    // Get branch name
    $data['branchName'] = 'Main'; // Default value
    if ($branch_id) {
        $branch = Branch::find($branch_id);
        if ($branch) {
            $data['branchName'] = $branch->name;
        }
    }

    // Apply filtering logic to employee count
    $data['getEmployeeCount'] = User::when($showAllCompanyEmployees,
        fn($q) => $q->where('company_id', $company_id),
        fn($q) => $q->where('branch_id', $filterBranchId)
    )->count();

    // Apply filtering logic to attendance queries
    $attendanceBase = Attendance::join('users', 'users.id', 'attendances.employee_id')
        ->where('attendances.attendance_date', $currentDate);

    if ($showAllCompanyEmployees) {
        $attendanceBase->where('users.company_id', $company_id);
    } else {
        $attendanceBase->where('users.branch_id', $filterBranchId);
    }

    $data['presentCount'] = (clone $attendanceBase)->where('attendance_type', 1)->count();
    $data['lateCount'] = (clone $attendanceBase)->where('attendance_type', 2)->count();
    $data['absentCount'] = (clone $attendanceBase)->where('attendance_type', 3)->count();
    $data['halfdayCount'] = (clone $attendanceBase)->where('attendance_type', 4)->count();

    // Monthly statistics
    $year = now()->year;
    $vacations = [];
    $absences = [];
    $presentMonthly = [];

    foreach (range(1, 12) as $month) {
        // Vacation query with updated filtering
        $vacQ = Vacation::join('users', 'users.id', 'vacations.employee_id')
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month);

        if ($showAllCompanyEmployees) {
            $vacQ->where('users.company_id', $company_id);
        } else {
            $vacQ->where('users.branch_id', $filterBranchId);
        }
        $vacations[] = $vacQ->distinct('employee_id')->count('employee_id');

        // Attendance query with updated filtering
        $attQ = Attendance::join('users', 'users.id', 'attendances.employee_id')
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month);

        if ($showAllCompanyEmployees) {
            $attQ->where('users.company_id', $company_id);
        } else {
            $attQ->where('users.branch_id', $filterBranchId);
        }

        $absences[] = (clone $attQ)->where('attendance_type', 3)->count();
        $presentMonthly[] = (clone $attQ)->where('attendance_type', 1)->count() / 4;
    }

    // Fetch latest 4 news items for the authenticated user's company
    $data['recentNews'] = News::where('company_id', auth()->user()->company_id)
                            ->orderBy('news_date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->limit(4)
                            ->get();

    $data['vacations'] = $vacations;
    $data['absences'] = $absences;
    $data['Present'] = $presentMonthly;

      // 👇 ADD THIS LINE - Fetch attendance rule settings for company policy PDF
    $data['setting'] = AttendanceRule::where('company_id', $company_id)->first();

    return view('backend.dashboard.list', $data);
}

}
