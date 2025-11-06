<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialAnalysisController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $branchId = $request->get('filter_branch_id');
        $payrollType = $request->get('payroll_type');

        // Base query
        $query = Payroll::query();

        // Apply filters
        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($month) {
            $query->whereMonth('start_date', $month);
        }
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        if ($payrollType) {
            $query->where('payroll_type', $payrollType);
        }

        // 1. Summary Statistics
        $summary = [
            'total_employees' => $query->distinct('employee_id')->count(),
            'total_basic_salary' => $query->sum('basic_salary'),
            'total_bonuses' => $query->sum('bounas'),
            'total_deductions' => $query->sum('deductions'),
            'total_attendance_deductions' => $query->sum('attendance_deduction'),
            'total_taxes' => $query->sum('taxes'),
            'total_net_pay' => $query->sum(DB::raw('CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END')),
            'average_salary' => $query->avg('basic_salary'),
        ];

        // Calculate percentage metrics
        $summary['bonus_percentage'] = $summary['total_basic_salary'] > 0
            ? ($summary['total_bonuses'] / $summary['total_basic_salary']) * 100
            : 0;
        $summary['deduction_percentage'] = $summary['total_basic_salary'] > 0
            ? (($summary['total_deductions'] + $summary['total_attendance_deductions']) / $summary['total_basic_salary']) * 100
            : 0;
        $summary['tax_percentage'] = $summary['total_basic_salary'] > 0
            ? ($summary['total_taxes'] / $summary['total_basic_salary']) * 100
            : 0;


        // 2. Monthly Trend Analysis (Last 12 months)
        $monthlyTrend = Payroll::select(
            DB::raw('YEAR(start_date) as year'),
            DB::raw('MONTH(start_date) as month'),
            DB::raw('SUM(basic_salary) as total_salary'),
            DB::raw('SUM(bounas) as total_bonus'),
            DB::raw('SUM(deductions + attendance_deduction) as total_deductions'),
            DB::raw('SUM(taxes) as total_taxes'),
            DB::raw('SUM(CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END) as total_net_pay'),
            DB::raw('COUNT(DISTINCT employee_id) as employee_count')
        )
        ->where('start_date', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // 3. Payroll Type Analysis
        $payrollTypeAnalysis = Payroll::select(
            'payroll_type',
            DB::raw('COUNT(DISTINCT employee_id) as employee_count'),
            DB::raw('SUM(basic_salary) as total_salary'),
            DB::raw('SUM(bounas) as total_bonus'),
            DB::raw('SUM(deductions + attendance_deduction) as total_deductions'),
            DB::raw('AVG(basic_salary) as avg_salary'),
            DB::raw('SUM(CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END) as total_net_pay')
        )
        ->groupBy('payroll_type')
        ->get();

        // 4. Branch Analysis (if applicable)
        $branchAnalysis = [];
        if (session('branch_id') === null || Branch::find(session('branch_id'))?->is_main == 1) {
            $branchAnalysis = Payroll::select(
                'branch_id',
                DB::raw('COUNT(DISTINCT employee_id) as employee_count'),
                DB::raw('SUM(basic_salary) as total_salary'),
                DB::raw('SUM(bounas) as total_bonus'),
                DB::raw('SUM(deductions + attendance_deduction) as total_deductions'),
                DB::raw('SUM(taxes) as total_taxes'),
                DB::raw('AVG(basic_salary) as avg_salary'),
                DB::raw('SUM(CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END) as total_net_pay')
            )
            ->with('branch:id,name')
            ->groupBy('branch_id')
            ->get();
        }


        // 6. Cost Analysis
        $costAnalysis = [
            'total_gross_cost' => $summary['total_basic_salary'] + $summary['total_bonuses'],
            'total_deduction_savings' => $summary['total_deductions'] + $summary['total_attendance_deductions'],
            'total_tax_liability' => $summary['total_taxes'],
            'total_net_cost' => $summary['total_net_pay'],
        ];

        // Calculate average cost per employee
        $costAnalysis['avg_cost_per_employee'] = $summary['total_employees'] > 0
            ? $costAnalysis['total_net_cost'] / $summary['total_employees']
            : 0;

        // 7. Deduction Analysis
        $deductionAnalysis = [
            'attendance_deductions' => $summary['total_attendance_deductions'],
            'other_deductions' => $summary['total_deductions'],
            'total_deductions' => $summary['total_attendance_deductions'] + $summary['total_deductions'],
            'employees_with_attendance_deductions' => Payroll::where('attendance_deduction', '>', 0)->distinct('employee_id')->count(),
            'employees_with_other_deductions' => Payroll::where('deductions', '>', 0)->distinct('employee_id')->count(),
        ];

        // 8. Vacation Balance Analysis
        $vacationAnalysis = Payroll::where('payroll_type', 'monthly')
            ->select(
                DB::raw('SUM(rest_vacancy) as total_vacation_days'),
                DB::raw('AVG(rest_vacancy) as avg_vacation_per_employee'),
                DB::raw('COUNT(DISTINCT employee_id) as employees_with_vacation')
            )
            ->first();

        // Get branches for filter
        $branches = Branch::orderBy('name', 'asc')->get();

        // 9. Comparison with Previous Period
        $previousPeriodQuery = Payroll::query();
        if ($month && $year) {
            $previousMonth = $month == 1 ? 12 : $month - 1;
            $previousYear = $month == 1 ? $year - 1 : $year;
            $previousPeriodQuery->whereYear('start_date', $previousYear)
                               ->whereMonth('start_date', $previousMonth);
        } else {
            $previousPeriodQuery->whereYear('start_date', $year - 1);
        }

        $previousPeriod = [
            'total_net_pay' => $previousPeriodQuery->sum(DB::raw('CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END')),
            'total_employees' => $previousPeriodQuery->distinct('employee_id')->count(),
        ];

        // Calculate growth rates
        $growthRates = [
            'payroll_growth' => $previousPeriod['total_net_pay'] > 0
                ? (($summary['total_net_pay'] - $previousPeriod['total_net_pay']) / $previousPeriod['total_net_pay']) * 100
                : 0,
            'employee_growth' => $previousPeriod['total_employees'] > 0
                ? (($summary['total_employees'] - $previousPeriod['total_employees']) / $previousPeriod['total_employees']) * 100
                : 0,
        ];

        // 10. Top Employees (Highest Net Pay)
$topEmployees = Payroll::select(
        'employee_id',
        DB::raw('SUM(CASE WHEN net_pay < 0 THEN 0 ELSE net_pay END) as total_net_pay'),
        DB::raw('SUM(basic_salary) as total_basic'),
        DB::raw('SUM(bounas) as total_bonus')
    )
    ->when($year, fn($q) => $q->whereYear('start_date', $year))
    ->when($month, fn($q) => $q->whereMonth('start_date', $month))
    ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
    ->when($payrollType, fn($q) => $q->where('payroll_type', $payrollType))
    ->groupBy('employee_id')
    ->orderByDesc('total_net_pay')
    ->take(5)
    ->get();

// Attach employee names
$topEmployees->load(['employee' => function($q) {
    $q->select('id', 'name');
}]);

        return view('backend.payrolls.financial_analysis', compact(
            'summary',
            'monthlyTrend',
            'payrollTypeAnalysis',
            'branchAnalysis',
            'costAnalysis',
            'deductionAnalysis',
            'vacationAnalysis',
            'branches',
            'year',
            'month',
            'branchId',
            'payrollType',
            'previousPeriod',
            'growthRates',
                'topEmployees'

        ));
    }
}
