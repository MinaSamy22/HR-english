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
        $payroll->total_allowances,
        $payroll->deductions,
        $payroll->attendance_deduction,
        $payroll->taxes + $payroll->insurance,
        $payroll->is_insured == 1 ? __('h_payroll.yes') : __('h_payroll.no'),
        $payroll->rest_vacancy,

        $payroll->net_pay < 0 ? 0 : $payroll->net_pay,

        __('h_payroll.payroll_types.' . strtolower($payroll->payroll_type)),
        $createdAtFormat, // Pay Date


date('m', strtotime($payroll->start_date))
    ];
}


public function headings():array{  //this is the names that will show in the head of excel sheet
return [
        __('h_payroll.employee_id'),
        __('h_payroll.employee_name'),
        __('h_payroll.basic_salary'),
        __('h_payroll.bonus'),
        __('h_payroll.total_allowance'),
        __('h_payroll.deductions'),
        __('h_payroll.attendance_deduction'),
        __('h_payroll.taxes_insurance'),
        __('h_payroll.is_insure'),
        __('h_payroll.vacation_balance'),
        __('h_payroll.net_pay'),
        __('h_payroll.payroll_type'),
        __('h_payroll.pay_date'),
        __('h_payroll.month'),
    ];



}

}
