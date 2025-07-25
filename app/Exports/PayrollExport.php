<?php
namespace App\Exports;
use App\Models\History;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Request;

class PayrollExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings{



public function collection(){
   // Get the company ID dynamically, for example from the logged-in user
   $companyId = Auth::user()->company_id;
   // Pass the company ID to the model's method
   return Payroll::getRecord($companyId);
}
protected $index = 0;
public function map($payroll): array
{
    $createdAtFormat = date('d-m-Y', strtotime($payroll->created_at));
    $month = date('F', strtotime($payroll->created_at)); // Get the month from the pay date

    // Extract the month from the start_date
    $startMonth = date('F', strtotime($payroll->start_date)); // Get the month name of start_date

    return [
        $payroll->employee_id,
        $payroll->name,
        $payroll->basic_salary,
        $payroll->bounas,
        $payroll->deductions,
        $payroll->attendance_deduction,
        $payroll->taxes,
        $payroll->payroll_type == 'monthly' ? $payroll->rest_vacancy : 0, // Conditionally show rest_vacancy
        $payroll->net_pay,
        $payroll->payroll_type,
        $createdAtFormat, // Pay Date
        $startMonth,
    ];
}


public function headings():array{  //this is the names that will show in the head of excel sheet
return [
    'Employee ID',
    'Employee Name',
    'Basic Salary',
    'Bounas',
    'Deductions',
    'Attendance Deductions',
    'Taxes/Insurance',
    'Vacation Balance',
    'Net Pay',
    'Payroll Type',
    'Pay Date',
    'Month',



];
}

}
