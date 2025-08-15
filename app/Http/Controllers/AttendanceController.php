<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use niklasravnsborg\LaravelPdf\Facades\Pdf;


class AttendanceController extends Controller
{

    public function index(Request $request)
    {

        $data['getRecord'] = Attendance::getRecord(); //function getrecord el fl a5r de 3amlha static func fe model
        $data['header_title'] = "Attendance Report";
        return view('backend.attendance.report', $data);

    }


    public function AttendanceEmployee(Request $request)
    {
        $data['header_title'] = "Employee Attendance";

        $company_id = session('company_id'); // Get the current session's company ID

$branch_id = session('branch_id');

$query = User::query();

if (!empty($branch_id)) {
    $query->where('branch_id', $branch_id);
} else {
    $query->where('company_id', $company_id);
}

$data['getRecord'] = $query->get();

        return view('backend.attendance.employee', $data);
    }

    public function AttendanceEmployeeSubmit(Request $request)
    {
        try {
            $check_attendance = Attendance::ChechAlreadyAttendance($request->employee_id, $request->attendance_date);

            if (!empty($check_attendance)) {
                $attendance = $check_attendance;
            } else {
                $attendance                    = new Attendance;
                $attendance->employee_id       = $request->employee_id;
                $attendance->attendance_date   = $request->attendance_date;
                $attendance->created_by        = Auth::user()->id;
                $attendance->company_id        = session('company_id');
            }

            $attendance->attendance_type       = $request->attendance_type;
            $attendance->save();

          return response()->json(['message' => __('dashboard.attendance_saved')]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
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
