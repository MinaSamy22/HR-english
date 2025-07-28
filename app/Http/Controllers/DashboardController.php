<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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

    // Get branch name
    $data['branchName'] = 'Main'; // Default value
    if ($branch_id) {
        $branch = Branch::find($branch_id);
        if ($branch) {
            $data['branchName'] = $branch->name;
        }
    }
    // Alternative: Get branch name from authenticated user
    // if (Auth::user()->branch_id) {
    //     $branch = Branch::find(Auth::user()->branch_id);
    //     if ($branch) {
    //         $data['branchName'] = $branch->name;
    //     }
    // }

    $data['getEmployeeCount'] = User::when($branch_id, fn($q) => $q->where('branch_id', $branch_id)
                         , fn($q) => $q->where('company_id', $company_id))->count();

    $attendanceBase = Attendance::join('users','users.id','attendances.employee_id')
        ->where('attendances.attendance_date', $currentDate);
    if ($branch_id) {
        $attendanceBase->where('users.branch_id', $branch_id);
    } else {
        $attendanceBase->where('users.company_id', $company_id);
    }

    $data['presentCount'] = (clone $attendanceBase)->where('attendance_type',1)->count();
    $data['lateCount'] = (clone $attendanceBase)->where('attendance_type',2)->count();
    $data['absentCount'] = (clone $attendanceBase)->where('attendance_type',3)->count();
    $data['halfdayCount'] = (clone $attendanceBase)->where('attendance_type',4)->count();

    $year = now()->year;
    $vacations = []; $absences = []; $presentMonthly = [];
    foreach (range(1,12) as $month) {
        $vacQ = Vacation::join('users','users.id','vacations.employee_id')
            ->whereYear('start_date',$year)
            ->whereMonth('start_date',$month);
        if ($branch_id) $vacQ->where('users.branch_id',$branch_id);
        else $vacQ->where('users.company_id',$company_id);
        $vacations[] = $vacQ->distinct('employee_id')->count('employee_id');

        $attQ = Attendance::join('users','users.id','attendances.employee_id')
            ->whereYear('attendance_date',$year)
            ->whereMonth('attendance_date',$month);
        if ($branch_id) $attQ->where('users.branch_id',$branch_id);
        else $attQ->where('users.company_id',$company_id);

        $absences[] = (clone $attQ)->where('attendance_type',3)->count();
        $presentMonthly[] = (clone $attQ)->where('attendance_type',1)->count()/4;
    }

      // Fetch latest 4 news items for the authenticated user's company
        $data['recentNews'] = News::where('company_id', auth()->user()->company_id)
                                ->orderBy('news_date', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->limit(4)
                                ->get();

    $data['vacations']=$vacations;
    $data['absences']=$absences;
    $data['Present']=$presentMonthly;

    return view('backend.dashboard.list',$data);
}

}
