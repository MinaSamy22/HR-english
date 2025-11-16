<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use niklasravnsborg\LaravelPdf\Facades\Pdf;


class AttendanceController extends Controller
{

public function index(Request $request)
{
    $data['getRecord'] = Attendance::getRecord();
    $data['header_title'] = "Attendance Report";

    // Add branches data like in your other controllers
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.attendance.report', $data);
}


    public function AttendanceEmployee(Request $request)
{
    $data['header_title'] = "Employee Attendance";

    $company_id = session('company_id');
    $branch_id = session('branch_id');

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

    $query = User::query();

    if ($showAllCompanyEmployees) {
        $query->where('company_id', $company_id);
    } else {
        $query->where('branch_id', $filterBranchId);
    }

    $data['getRecord'] = $query->get();

    return view('backend.attendance.employee', $data);
}

public function AttendanceEmployeeSubmit(Request $request)
{
    $company_id = session('company_id');
    $created_by = auth()->id();

    $validated = $request->validate([
        'attendance_date' => 'required|date',
        'employee_id' => 'required',
        'check_in' => 'nullable',
        'check_out' => 'nullable',
        'attendance_type' => 'nullable|string',
    ]);

    // 🔹 Load existing record
    $existing = \App\Models\Attendance::where([
        'attendance_date' => $request->attendance_date,
        'employee_id' => $request->employee_id,
        'company_id' => $company_id,
    ])->first();

    $attendanceRule = \App\Models\AttendanceRule::where('company_id', $company_id)->first();
    $user = \App\Models\User::find($request->employee_id);

    // Detect field changes
    $checkInChanged = !$existing || $existing->check_in != $request->check_in;
    $checkOutChanged = !$existing || $existing->check_out != $request->check_out;
    $manualTypeSelected = $request->filled('attendance_type');
    $oldType = $existing ? $existing->attendance_type : null;

    $attendanceType = $oldType;

    if ($manualTypeSelected && $request->attendance_type != $oldType) {
        // ✅ HR manually changed attendance type
        $attendanceType = $request->attendance_type;
    } elseif (($checkInChanged || $checkOutChanged) && $user && $attendanceRule) {
        // ✅ Auto recalc when time changes
        $checkIn = $request->check_in ? strtotime($request->check_in) : null;
        $checkOut = $request->check_out ? strtotime($request->check_out) : null;
        $workStart = $user->work_start_time ? strtotime($user->work_start_time) : null;
        $workEnd = $user->work_end_time ? strtotime($user->work_end_time) : null;

        if ($checkIn && $checkOut && $workStart && $workEnd) {
            // 🔸 If check-in and check-out are identical → Absent
            if ($request->check_in === $request->check_out) {
                $attendanceType = 3; // ❌ Absent
            } else {
                $lateMinutes = ($checkIn - $workStart) / 60;
                $earlyLeaveMinutes = ($workEnd - $checkOut) / 60;

                // ✅ If checkout early by 60 minutes or more → Absent
                if ($earlyLeaveMinutes >= 60) {
                    $attendanceType = 4; // ⏱ half day
                }
                elseif ($lateMinutes <= $attendanceRule->late_threshold_minutes && $earlyLeaveMinutes <= 0) {
                    $attendanceType = 1; // ✅ Present
                }
                elseif (
                    $lateMinutes >= $attendanceRule->late_threshold_minutes &&
                    $lateMinutes <= $attendanceRule->half_day_threshold_minutes
                ) {
                    $attendanceType = 4; // ⏱ Half Day
                }
                elseif (
                    $lateMinutes > $attendanceRule->half_day_threshold_minutes ||
                    $earlyLeaveMinutes > $attendanceRule->half_day_threshold_minutes
                ) {
                    $attendanceType = 3; // ❌ Absent
                }
                else {
                    $attendanceType = 1; // Default → Present
                }
            }
        } elseif (is_null($checkIn) && is_null($checkOut)) {
            $attendanceType = 3; // ❌ Absent
        } else {
            $attendanceType = null; // Partial data
        }
    }
    // 🔍 NEW FEATURE: Approved early leave keeps employee present
    $hasApprovedEarlyLeave = \App\Models\EarlyLeaveRequest::where('employee_id', $request->employee_id)
        ->where('request_date', $request->attendance_date)
        ->where('status', 'approved')
        ->exists();

    if ($hasApprovedEarlyLeave) {
        $attendanceType = "1"; // Force Present
    }

    // 🔹 Save or update record
    $updateData = [
        'company_id' => $company_id,
        'created_by' => $created_by,
        'attendance_type' => $attendanceType,
        'check_in' => $request->check_in ?: null,
        'check_out' => $request->check_out ?: null,
        'updated_at' => now(),
    ];

    $record = \App\Models\Attendance::updateOrCreate(
        [
            'attendance_date' => $request->attendance_date,
            'employee_id' => $request->employee_id,
            'company_id' => $company_id,
        ],
        $updateData
    );

    return response()->json([
        'success' => true,
        'message' => 'Attendance saved successfully!',
        'record' => $record
    ]);
}


    public function exportPdf(Request $request)
    {
        // Use the getRecord method from the Attendance model instead
        $getRecord = Attendance::getRecord();

        // Render the PDF-specific view and pass the filtered records
        $pdf = Pdf::loadView('backend.attendance.pdf', compact('getRecord'),['format' => 'A4','display_mode'=> 'fullpage'],['tempDir' => storage_path('temp/mpdf'),]);

        return $pdf->download('attendance-report.pdf');  // Return the the name of PDF for download
    }

}
