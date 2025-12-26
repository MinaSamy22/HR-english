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
use ZipArchive;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

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
    $data['getRecord'] = Payroll::getRecord($company_id);

    // Add branches data like in your other controllers
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.payrolls.index', $data);
}





    public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // If branch_id is null, show all employees for the company
    if (empty($branch_id)) {
        $data['getEmployees'] = User::where('company_id', $company_id)->get();
    } else {
        // Check if the current branch is the main branch
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        // If it's the main branch (is_main == 1), show all employees for the company
        if ($currentBranch && $currentBranch->is_main == 1) {
            $data['getEmployees'] = User::where('company_id', $company_id)->get();
        } else {
            // Otherwise, filter by the specific branch_id
            $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
        }
    }

    // Add branches data for the filter dropdown
    $data['branches'] = \DB::table('branches')
        ->where('company_id', $company_id)
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    $data['getPayrolls'] = Payroll::get(); // Optional, depending on your needs
    return view('backend.payrolls.create', $data);
}


public function add_post(Request $request)
{
    $employeeIds = $request->input('employee_ids');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $payrollType  = $request->input('payroll_type');
    $companyId = auth()->user()->company_id;

    $employees = User::with('company.attendanceSetting','attendances','vacations','times')->whereIn('id',$employeeIds)->get();

    $validationErrors = [];
    $processedEmployees = [];

    foreach ($employees as $employee) {
        $hasError = false;

        // Validation 1: Check if payroll start date is before employee hire date
        if ($employee->hire_date && $startDate < $employee->hire_date) {
            $validationErrors[] = __('Employee') . ' ' . $employee->name . ': ' .
                __('h_payroll.payroll_period_starts_before_hire_date') . ' (' . $employee->hire_date . ')';
            $hasError = true;
        }

        // Validation 2: Check if payroll already exists for overlapping period
        if (!$hasError) {
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('payroll_type', $payrollType)
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where(function($q) use ($startDate, $endDate) {
                        // Case 1: Existing payroll starts before or on current start and ends after or on current start
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $startDate);
                    })
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        // Case 2: Existing payroll starts before or on current end and ends after or on current end
                        $q->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $endDate);
                    })
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        // Case 3: Current period completely contains existing payroll
                        $q->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                    });
                })
                ->first();

            if ($existingPayroll) {
                $validationErrors[] = __('Employee') . ' ' . $employee->name . ': ' .
                    __('h_payroll.overlapping_payroll_exists_for_period') . ' (' . $existingPayroll->start_date . ' - ' . $existingPayroll->end_date . ')';
                $hasError = true;
            }
        }

        // If no validation errors, add to processing list
        if (!$hasError) {
            $processedEmployees[] = $employee;
        }
    }

    // IF ANY ERRORS EXIST, return back with errors WITHOUT processing ANY payrolls
    if (!empty($validationErrors)) {
        $errorMessage = __('h_payroll.payroll_generation_failed_for_following_employees') . "\n\n";
        foreach ($validationErrors as $error) {
            $errorMessage .= "• " . $error . "\n";
        }
        $errorMessage .= "\n" . __('h_payroll.please_fix_errors_before_proceeding');

        return redirect()->back()
            ->withInput()
            ->with('error', $errorMessage);
    }

    // Process employees ONLY if all validations passed
    foreach ($processedEmployees as $employee) {

        $salary = $employee->salary;

        // Calculate components and retrieve from service file
        [$attendanceDeductions, $dailyWage, $daysAbsent]       = $this->payrollService->calculateAttendanceDeductions($employee, $salary, $startDate, $endDate, $companyId, $payrollType);
        $leaveDeductions                                       = $this->payrollService->calculateDeductions($employee, $startDate, $endDate);
        [$restVacancy, $vacationDeductions]                    = $this->payrollService->calculateVacationDeductions($employee, $startDate, $endDate);
        $bonus                                                 = $this->payrollService->calculateBonus($employee, $startDate, $endDate);
        $taxAmount                                             = $this->payrollService->calculateTaxes($employee, $companyId, $salary);
        $insuranceAmount                                       = $this->payrollService->calculateInsurance($employee, $companyId, $salary);
        $totalDeductions                                       = $leaveDeductions + $vacationDeductions;
        $totalTaxes                                            = $taxAmount + $insuranceAmount;

        // Final payroll record
        $payroll                                      = new Payroll();
        $payroll->employee_id                         = $employee->id;
        $payroll->start_date                          = $startDate;
        $payroll->end_date                            = $endDate;

        //new
        $housingAllowance                             = $employee->housing_allowance ?? 0;
        $transportationAllowance                      = $employee->transportation_allowance ?? 0;
        $otherAllowances                              = $employee->other_allowances ?? 0;

        $totalAllowances = $housingAllowance + $transportationAllowance + $otherAllowances;

        $payroll->basic_salary                        = $salary;
        $payroll->bounas                              = $bonus;
        $payroll->deductions                          = $totalDeductions;
        $payroll->attendance_deduction                = $attendanceDeductions;
        $payroll->taxes                               = $totalTaxes;
        $payroll->rest_vacancy                        = $restVacancy;
        $payroll->payroll_type                        = $payrollType;

        $payroll->net_pay                             = $salary - ($totalDeductions + $totalTaxes + $attendanceDeductions) + $bonus + $totalAllowances;
        $payroll->daily_wage                          = $dailyWage;
        $payroll->days_absent                         = $daysAbsent;

        // Handle company/branch assignment
        $payroll->company_id = session('company_id') ?: $companyId;
        if (session()->has('branch_id')) {
            $payroll->branch_id = session('branch_id');
        }

        $payroll->save();
    }

    // Build success message
    $responseMessage = __('h_payroll.payroll_registered') . "\n\n" . __('h_payroll.generated_for') . ":\n";
    $employeeNames = array_map(function($emp) { return '• ' . $emp->name; }, $processedEmployees);
    $responseMessage .= implode("\n", $employeeNames);

    // Redirect to index ONLY after successful generation of ALL payrolls
    return redirect('admin/payroll')->with('success', $responseMessage);
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


return redirect('admin/payroll')->with('success', __('h_payroll.payroll_updated'));
    }

    // Method to delete a Payroll record
    public function delete($id)
    {
        $recordDelete = Payroll::find($id);
        if ($recordDelete) {
            $recordDelete->delete();
return redirect()->back()->with('success', __('h_payroll.record_deleted'));
        } else {
            return redirect()->back()->with('error', 'Record not found.');
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
return response()->json(['success' => false, 'message' => __('h_payroll.no_payroll_selected')]);        }

        Payroll::whereIn('id', $ids)->delete();

return response()->json(['success' => true, 'message' => __('h_payroll.selected_deleted')]);
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
        $pdf = Pdf::loadView('backend.payrolls.pdf', compact('getRecord'),['format' => 'A4','display_mode'=> 'fullpage'],['tempDir' => storage_path('temp/mpdf'),]);

        // Return the PDF for download
        return $pdf->download('payroll-report.pdf');
    }



    public function salary_payment (Request $request)
{
    $company_id = session('company_id');

    // Pass the company_id to the getRecord method for filtering
    $data['getRecord'] = Payroll::getRecord($company_id);

    // Add branches data like in your other controllers
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.payrolls.Salary Payment.salary-payment', $data);
}

    public function salary_payment_add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // If branch_id is null, show all employees for the company
    if (empty($branch_id)) {
        $data['getEmployees'] = User::where('company_id', $company_id)->get();
    } else {
        // Check if the current branch is the main branch
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        // If it's the main branch (is_main == 1), show all employees for the company
        if ($currentBranch && $currentBranch->is_main == 1) {
            $data['getEmployees'] = User::where('company_id', $company_id)->get();
        } else {
            // Otherwise, filter by the specific branch_id
            $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
        }
    }


    // Add branches data for the filter dropdown
    $data['branches'] = \DB::table('branches')
        ->where('company_id', $company_id)
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    $data['getPayrolls'] = Payroll::get(); // Optional, depending on your needs
    return view('backend.payrolls.Salary Payment.create', $data);
}


    public function payslip(Request $request)
{
    $company_id = session('company_id');

    // Pass the company_id to the getRecord method for filtering
    $data['getRecord'] = Payroll::getRecord($company_id);

    // Add branches data like in your other controllers
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

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
