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

    $updateData = [
        'company_id' => $company_id,
        'created_by' => $created_by,
        'attendance_type' => $request->attendance_type ?: null,
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
