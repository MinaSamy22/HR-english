<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceImportController extends Controller
{
    public function showForm()
    {
        return view('backend.attendance.biometric-excel');
    }

public function import(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $data = Excel::toArray([], $request->file('excel_file'));
    $rows = $data[0];
    unset($rows[0]); // Remove header

    $insertedCount = 0;


    foreach ($rows as $row) {
        $employeeId = trim($row[0]);
        $checkInRaw = trim($row[1]);
        $checkOutRaw = trim($row[2]);
        $dateRaw = trim($row[3]);

        $user = User::find($employeeId);
        if (!$user || !$user->work_start_time || !$user->work_end_time) {
            continue;
        }

        $checkIn = Carbon::parse($checkInRaw);
        $checkOut = Carbon::parse($checkOutRaw);
        $attendanceDate = Carbon::parse($dateRaw)->format('Y-m-d');

        $scheduledStart = Carbon::parse($user->work_start_time);
        $scheduledEnd = Carbon::parse($user->work_end_time);

        $expectedWorkDuration = $scheduledEnd->diffInMinutes($scheduledStart);
        $actualWorkDuration = $checkOut->diffInMinutes($checkIn);

        $lateMinutes = $scheduledStart->diffInMinutes($checkIn, false); // negative if early

        // Determine attendance_type
        if ($lateMinutes <= 0) {
            $attendanceType = '1'; // Present
        } elseif ($lateMinutes <= 120) {
            $attendanceType = '2'; // Late (within 2 hours)
        } elseif ($actualWorkDuration >= ($expectedWorkDuration / 2)) {
            $attendanceType = '4'; // Half Day
        } else {
            $attendanceType = '3'; // Absent
        }

             Attendance::create([
            'employee_id'     => $user->id,
            'attendance_date' => $attendanceDate,
            'check_in'        => $checkIn->format('H:i:s'),
            'check_out'       => $checkOut->format('H:i:s'),
            'attendance_type' => $attendanceType,
            'created_by'      => auth()->id() ?? 1,
            'company_id'      => $user->company_id,
        ]);
                $insertedCount++; // ✅ count successful inserts

    }


    return back()->with('success', "$insertedCount attendance record(s) imported successfully.");
}


}
