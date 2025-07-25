<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Manager;
use App\Models\User;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class CalendarController extends Controller
{

public function index(Request $request){


 //calendar logic//

 $year = now()->year;
 $month = now()->month;
 $currentDate = now()->format('Y-m-d');

 $daysInMonth = Carbon::create($year, $month)->daysInMonth;
 $firstDayOfMonth = Carbon::create($year, $month, 1)->dayOfWeek;

 // Define special dates (for example, events)
 $events = [
     '2024-08-15' => 'Holiday',

 ];




//gets for card num logic for counting to each card
$data['getEmployeeCount'] = User::count();



return view('backend.dashboard.calender', $data, compact('year', 'month', 'daysInMonth', 'firstDayOfMonth', 'events', 'currentDate'));


}

}
