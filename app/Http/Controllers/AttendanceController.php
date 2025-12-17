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
        $checkIn = $request->check_in ? strtotime($request->check_in) : null;
        $checkOut = $request->check_out ? strtotime($request->check_out) : null;
        $workStart = $user->work_start_time ? strtotime($user->work_start_time) : null;
        $workEnd = $user->work_end_time ? strtotime($user->work_end_time) : null;

        // FEATURES Start

        // 🔍 FEATURE 1: Approved early leave keeps employee present
        $hasApprovedEarlyLeave = \App\Models\EarlyLeaveRequest::where('employee_id', $request->employee_id)
            ->where('request_date', $request->attendance_date)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedEarlyLeave) {
            $attendanceType = 1; // Force Present
        }

        // 🔍 FEATURE 2: mark as present if no data at chekout
        // 🔹 If check-in is correct and no check-out yet → Present
        if ($checkIn && is_null($checkOut) && $workStart) {

            $earlyMinutes = max(0, ($workStart - $checkIn) / 60);
            $allowedEarlyMinutes = $user->checkin_early_minutes ?? 0;

            // ✔️ Present only if NOT before allowed early minutes
            if ($earlyMinutes <= $allowedEarlyMinutes) {
                $attendanceType = 1; // Present
            } else {
                $attendanceType = null;
            }
        }

        // 🔍 FEATURE 3: Start Automatic choosing (late - halfday - absent)
        //bdayt el automatic choosing

        if ($checkIn && $workStart) {

            // 🔹 Early check-in minutes
            $earlyMinutes = max(0, ($workStart - $checkIn) / 60);
            $allowedEarlyMinutes = $user->checkin_early_minutes ?? 0;

            if ($earlyMinutes > $allowedEarlyMinutes) {
                $attendanceType = null;
            } else {

                // 🔹 Late minutes
                $lateMinutes = max(0, ($checkIn - $workStart) / 60);

                $lateThreshold = $attendanceRule->late_threshold_minutes ?? 15;
                $halfDayThreshold = $attendanceRule->half_day_threshold_minutes ?? 30;
                $absentThreshold = $attendanceRule->absent_threshold_minutes ?? 60;

                if ($lateMinutes > $absentThreshold) {
                    $attendanceType = 3; // Absent
                } elseif ($lateMinutes > $halfDayThreshold) {
                    $attendanceType = 4; // Half Day
                } elseif ($lateMinutes > $lateThreshold) {
                    $attendanceType = 2; // Late
                } else {
                    $attendanceType = 1; // Present
                }
            }

        } elseif (is_null($checkIn) && is_null($checkOut)) {
            $attendanceType = 3; // Absent
        }


        // 🔍 FEATURE 4: when we save the attendance_type as NULL
        if ($checkIn && $checkOut && $workEnd) {

            // check-in == check-out
            if ($checkIn === $checkOut) {
                $attendanceType = null;
            }

            // Minutes difference from work end
            $checkoutDiffMinutes = ($checkOut - $workEnd) / 60;

            // 🔹 Check-out before work end → invalid
            if ($checkoutDiffMinutes < 0) {
                $attendanceType = null;
            }

            // 🔹 Check-out after work end more than 45 minutes → invalid
            if ($checkoutDiffMinutes > 45) {
                $attendanceType = null;
            }
        }

        ///////////////////////////////////////////////////////////////////////////////////////////

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
        $pdf = Pdf::loadView('backend.attendance.pdf', compact('getRecord'), ['format' => 'A4', 'display_mode' => 'fullpage'], ['tempDir' => storage_path('temp/mpdf'),]);

        return $pdf->download('attendance-report.pdf');  // Return the the name of PDF for download
    }

}
