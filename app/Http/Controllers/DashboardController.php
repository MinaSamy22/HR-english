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

    // Apply filtering logic to attendance queries (combined into 1 query)
    $attendanceBase = Attendance::join('users', 'users.id', 'attendances.employee_id')
        ->where('attendances.attendance_date', $currentDate);

    if ($showAllCompanyEmployees) {
        $attendanceBase->where('users.company_id', $company_id);
    } else {
        $attendanceBase->where('users.branch_id', $filterBranchId);
    }

    $todayStats = (clone $attendanceBase)
        ->selectRaw('
            SUM(CASE WHEN attendances.attendance_type = 1 THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN attendances.attendance_type = 2 THEN 1 ELSE 0 END) as late,
            SUM(CASE WHEN attendances.attendance_type = 3 THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN attendances.attendance_type = 4 THEN 1 ELSE 0 END) as halfday
        ')
        ->first();

    $data['presentCount'] = (int) ($todayStats->present ?? 0);
    $data['lateCount']    = (int) ($todayStats->late ?? 0);
    $data['absentCount']  = (int) ($todayStats->absent ?? 0);
    $data['halfdayCount'] = (int) ($todayStats->halfday ?? 0);

    // Monthly statistics (aggregated in 2 fast queries instead of 36 separate loop queries)
    $year = now()->year;

    // 1. Vacation monthly aggregation
    $vacQ = Vacation::join('users', 'users.id', 'vacations.employee_id')
        ->whereYear('vacations.start_date', $year);

    if ($showAllCompanyEmployees) {
        $vacQ->where('users.company_id', $company_id);
    } else {
        $vacQ->where('users.branch_id', $filterBranchId);
    }

    $vacationMonthlyData = $vacQ->selectRaw('MONTH(vacations.start_date) as month, COUNT(DISTINCT vacations.employee_id) as total')
        ->groupByRaw('MONTH(vacations.start_date)')
        ->pluck('total', 'month');

    // 2. Attendance monthly aggregation
    $attQ = Attendance::join('users', 'users.id', 'attendances.employee_id')
        ->whereYear('attendances.attendance_date', $year);

    if ($showAllCompanyEmployees) {
        $attQ->where('users.company_id', $company_id);
    } else {
        $attQ->where('users.branch_id', $filterBranchId);
    }

    $attendanceMonthlyData = $attQ->selectRaw('
            MONTH(attendances.attendance_date) as month,
            SUM(CASE WHEN attendances.attendance_type = 3 THEN 1 ELSE 0 END) as absences,
            SUM(CASE WHEN attendances.attendance_type = 1 THEN 1 ELSE 0 END) as presents
        ')
        ->groupByRaw('MONTH(attendances.attendance_date)')
        ->get()
        ->keyBy('month');

    $vacations = [];
    $absences = [];
    $presentMonthly = [];

    foreach (range(1, 12) as $month) {
        $vacations[] = (int) ($vacationMonthlyData[$month] ?? 0);
        $attItem = $attendanceMonthlyData->get($month);
        $absences[] = (int) ($attItem->absences ?? 0);
        $presentMonthly[] = ($attItem ? (float) $attItem->presents : 0) / 4;
    }

    // Fetch latest 4 news items for the authenticated user's company
    $data['recentNews'] = News::where('company_id', auth()->user()->company_id)
                            ->select(['id', 'title', 'description', 'image', 'news_date', 'created_at'])
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
