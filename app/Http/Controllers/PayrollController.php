<?php
namespace App\Http\Controllers;
use App\Exports\PayrollExport;
use App\Models\Attendance;
use App\Models\Insurance;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\User;
use App\Models\Vacation;
use App\Services\PayrollService;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;


class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request)
    {
        $company_id = session('company_id');

        // Pass the company_id to the getRecord method for filtering
        $data['getRecord'] = Payroll::getRecord($company_id);  // Modify getRecord to accept company_id

        return view('backend.payrolls.index', $data);
    }



    public function add(Request $request)
    {
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    if (!empty($branch_id)) {
        $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
    } else {
        $data['getEmployees'] = User::where('company_id', $company_id)->whereNull('branch_id')->get();
    }
        $data['getPayrolls'] = Payroll::get(); // Optional, depending on your needs
        return view('backend.payrolls.create', $data);
    }



    public function add_post(Request $request)
    {
        $employeeIds = $request->input('employee_ids');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $payrollType  = $request->input('payroll_type'); // NEW: Get selected payroll type
        $companyId = auth()->user()->company_id;

        $employees = User::with('company.attendanceSetting','attendances','vacations','times')->whereIn('id',$employeeIds)->get();
        foreach ($employees as $employee) {

            $salary = $employee->salary;

            // Calculate components and retreve from service file
            [$attendanceDeductions, $dailyWage, $daysAbsent]   = $this->payrollService->calculateAttendanceDeductions($employee, $salary, $startDate, $endDate, $companyId, $payrollType);
            $leaveDeductions                                   = $this->payrollService->calculateDeductions($employee, $startDate, $endDate);
            [$restVacancy, $vacationDeductions]                = $this->payrollService->calculateVacationDeductions($employee, $startDate, $endDate);
            $bonus                                             = $this->payrollService->calculateBonus($employee, $startDate, $endDate);
            $taxAmount                                         = $this->payrollService->calculateTaxes($employee, $companyId, $salary);
            $insuranceAmount                                   = $this->payrollService->calculateInsurance($employee, $companyId, $salary);
            $totalDeductions                                   = $leaveDeductions + $vacationDeductions;
            $totalTaxes                                        = $taxAmount + $insuranceAmount;

            // Final payroll record
            $payroll                            = new Payroll();
            $payroll->employee_id               = $employee->id;
            $payroll->start_date                = $startDate;
            $payroll->end_date                  = $endDate;
            $payroll->basic_salary              = $salary;
            $payroll->bounas                    = $bonus;
            $payroll->deductions                = $totalDeductions;
            $payroll->attendance_deduction      = $attendanceDeductions;
            $payroll->taxes                     = $totalTaxes; //tax + assurance
            $payroll->rest_vacancy              = $restVacancy; // Only for info/reporting, not deducted in net pay
            $payroll->payroll_type              = $payrollType; // ✅ NEW: Save the payroll type

            $payroll->net_pay                   = $salary - ($totalDeductions + $totalTaxes + $attendanceDeductions ) + $bonus;
            $payroll->company_id                = $companyId;
            $payroll->company_id                = $companyId;
            $payroll->daily_wage                = $dailyWage;
            $payroll->days_absent               = $daysAbsent;

            // Handle company/branch assignment
    $payroll->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $payroll->branch_id = session('branch_id');
    }
            $payroll->save();
        }

        return redirect('admin/payroll')->with('success', 'Payrolls successfully registered.');
    }





    public function edit($id)
    {
        //all of these to show the name of employee at top of edit page
        $payroll = Payroll::find($id);

        // take the specific employee associated with this payroll
        $employee = User::find($payroll->employee_id);

        $data['getRecord']    = Payroll::find($id);
        $data['getEmployee']  = User::all();


        return view('backend.payrolls.edit', $data, [
            'getRecord'       => $payroll,
            'employeeName'    => $employee->name,
        ]);


    }

    public function edit_update($id, Request $request)
    {

        $payroll = Payroll::find($id);

     // $payroll                          = new payroll; de lma bt3ml add bs
        $payroll->basic_salary            = trim($request->basic_salary);
        $payroll->bounas                  = trim($request->bounas);
        $payroll->deductions              = trim($request->deductions);
        $payroll->taxes                   = trim($request->taxes);
        $payroll->rest_vacancy            = trim($request->rest_vacancy);
        $payroll->net_pay                 = trim($request->net_pay);
     // $payroll->created_at              = trim ($request->created_at);

     // Handle company/branch assignment
    $payroll->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $payroll->branch_id = session('branch_id');
    }
        $payroll->save();


        return redirect('admin/payroll')->with('success', 'successfully update.');
    }

    // Method to delete a Payroll record
    public function delete($id)
    {
        $recordDelete = Payroll::find($id);
        if ($recordDelete) {
            $recordDelete->delete();
            return redirect()->back()->with('success', 'Record successfully deleted.');
        } else {
            return redirect()->back()->with('error', 'Record not found.');
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No Payroll selected.']);
        }

        Payroll::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected Payroll deleted successfully.']);
    }



    public function payrolls_export(Request $request)
    {

        return Excel::download(new PayrollExport, 'payroll.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Use the getRecord method and pass the company_id
        $getRecord = Payroll::getRecord($companyId);

        // Render the PDF-specific view and pass the filtered records
        $pdf = Pdf::loadView('backend.payrolls.pdf', compact('getRecord'));

        // Return the PDF for download
        return $pdf->download('payroll-report.pdf');
    }



    public function payslip(Request $request)
    {
        $company_id = session('company_id');

        // Pass the company_id to the getRecord method for filtering
        $data['getRecord'] = Payroll::getRecord($company_id);  // Modify getRecord to accept company_id

        return view('backend.payrolls.payslip', $data);
    }

public function downloadSinglePayslip(Request $request)
{
    $companyId = Auth::user()->company_id;
    $payrollId = $request->input('payroll_id');

    // Get specific payroll record for the company
    $payrollRecord = Payroll::where('id', $payrollId)
                           ->where('company_id', $companyId)
                           ->with(['employee.department', 'employee.job', 'company'])
                           ->first();

    if (!$payrollRecord) {
        return redirect()->back()->with('error', 'Payroll record not found.');
    }

    // Create filename with employee name and date
    $fileName = 'payslip-' . str_replace(' ', '-', strtolower($payrollRecord->employee->name)) . '-' .
                date('Y-m', strtotime($payrollRecord->start_date)) . '.pdf';

    // Render the PDF with single payroll record
    $pdf = Pdf::loadView('backend.payrolls.single-payslip-pdf', compact('payrollRecord'));

    // Return the PDF for download
    return $pdf->download($fileName);
}

public function downloadAllPayslips(Request $request)
{
    $companyId = Auth::user()->company_id;
    $payrollIds = $request->input('payroll_ids', []);

    // If no specific IDs provided, use search parameters to get records
    if (empty($payrollIds)) {
        // Get search parameters
        $name = $request->input('name');
        $month = $request->input('month');
        $year = $request->input('year');

        // Build query with search filters
        $query = Payroll::where('company_id', $companyId)
                        ->with(['employee.department', 'employee.job', 'company']);

        if (!empty($name)) {
            $query->whereHas('employee', function($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if (!empty($month)) {
            $query->whereMonth('start_date', $month);
        }

        if (!empty($year)) {
            $query->whereYear('start_date', $year);
        }

        $payrollRecords = $query->get();
    } else {
        // Get specific payroll records by IDs
        $payrollRecords = Payroll::whereIn('id', $payrollIds)
                                ->where('company_id', $companyId)
                                ->with(['employee.department', 'employee.job', 'company'])
                                ->get();
    }

    if ($payrollRecords->isEmpty()) {
        return redirect()->back()->with('error', 'No payroll records found.');
    }

    // Create filename with date range or current date
    $fileName = 'payslips-all-' . date('Y-m-d-H-i-s') . '.pdf';

    // Render the PDF with all payroll records
    $pdf = Pdf::loadView('backend.payrolls.bulk-payslips-pdf', compact('payrollRecords'));

    // Return the PDF for download
    return $pdf->download($fileName);
}

}
