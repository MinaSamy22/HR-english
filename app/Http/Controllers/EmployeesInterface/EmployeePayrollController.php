<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ هذا هو الصحيح
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class EmployeePayrollController extends Controller
{
public function index(Request $request)
{
    $employee = Auth::guard('employee')->user(); // get the logged-in employee

    if (!$employee) {
        abort(403, 'Unauthorized');
    }

    $employee_id = $employee->id;

    $query = Payroll::where('employee_id', $employee_id);

    // فلترة حسب الشهر والسنة لو موجودين
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }

    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    $data['getRecord'] = $query->orderBy('id', 'desc')->paginate(10);

    return view('EmployeeInterface.payrolls.payslip', $data);
}

public function downloadSinglePayslip(Request $request)
{
    $employee = Auth::guard('employee')->user(); // استخدم الجارد الصحيح للموظف

    if (!$employee) {
        abort(403, 'Unauthorized');
    }

    $payrollId = $request->input('payroll_id');

    // Get specific payroll record that belongs to this employee only
    $payrollRecord = Payroll::where('id', $payrollId)
        ->where('employee_id', $employee->id)
        ->with(['employee.department', 'employee.job', 'company'])
        ->first();

    if (!$payrollRecord) {
        return redirect()->back()->with('error', 'Payroll record not found.');
    }

    // Create filename with employee name and date
    $fileName = 'payslip-' . str_replace(' ', '-', strtolower($payrollRecord->employee->name)) . '-' .
        date('Y-m', strtotime($payrollRecord->start_date)) . '.pdf';

    // Render the PDF with single payroll record
    $pdf = Pdf::loadView('EmployeeInterface.payrolls.single-payslip-pdf', compact('payrollRecord'));

    // Return the PDF for download
    return $pdf->download($fileName);
}





}
