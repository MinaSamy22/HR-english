<?php
namespace App\Services;
use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\Insurance;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\User;
use App\Models\Vacation;
use Illuminate\Support\Facades\DB;

class PayrollService
{
public function calculateAttendanceDeductions($employee, $salary, $startDate, $endDate, $companyId, $payrollType = 'monthly')
{
    if (!$employee || !$employee->company || !$employee->company->attendanceSetting) {
        return [0, 0, 0];
    }

    $settings = $employee->company->attendanceSetting;
    $workingDays = json_decode($settings->working_days ?? '[]', true);

    // STEP 1: Base daily wage logic by payroll type
    if ($payrollType === 'daily') {
        $dailyWage = $salary; // For daily payroll, salary = wage per day
    } elseif ($payrollType === 'weekly') {
        $dailyWage = round($salary / 7, 2); // Temporarily divided; will adjust below
    } else {
        $dailyWage = round($salary / 30, 2); // Default monthly logic
    }

    $attendanceRecords = $employee->attendances()
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->get()
        ->keyBy('attendance_date');

    $officialHolidays = collect(json_decode($settings->official_holidays ?? '[]', true))
        ->map(fn($date) => is_array($date) ? date('Y-m-d', strtotime($date['date'] ?? '')) : date('Y-m-d', strtotime($date)))
        ->filter()
        ->toArray();

    $vacations = $employee->vacations()
        ->whereDate('start_date', '<=', $endDate)
        ->whereDate('end_date', '>=', $startDate)
        ->get();

    $vacationDates = [];
    foreach ($vacations as $vacation) {
        $vacPeriod = new \DatePeriod(
            new \DateTime($vacation->start_date),
            new \DateInterval('P1D'),
            (new \DateTime($vacation->end_date))->modify('+1 day')
        );
        foreach ($vacPeriod as $vacDate) {
            $vacationDates[] = $vacDate->format('Y-m-d');
        }
    }

    $deduction = 0;
    $daysAbsent = 0;
    $actualWorkedDays = 0;

    $period = new \DatePeriod(
        new \DateTime($startDate),
        new \DateInterval('P1D'),
        (new \DateTime($endDate))->modify('+1 day')
    );

    foreach ($period as $date) {
        $dayName = $date->format('l');
        $formattedDate = $date->format('Y-m-d');

        if (!in_array($dayName, $workingDays) || in_array($formattedDate, $officialHolidays)) {
            continue;
        }

        if (in_array($formattedDate, $vacationDates)) {
            continue;
        }

        $record = $attendanceRecords[$formattedDate] ?? null;
        $type = $record->attendance_type ?? 3;

        if (!$record || $type == 3) {
            $daysAbsent++;
            $deduction += $dailyWage;
        } elseif ($type == 2) {
            $latePercent = $settings->late_deduction_percentage ?? 0;
            $deduction += $dailyWage * ($latePercent / 100);
            $actualWorkedDays++;
        } elseif ($type == 4) {
            $halfDayPercent = $settings->half_day_deduction_percentage ?? 0;
            $deduction += $dailyWage * ($halfDayPercent / 100);
            $actualWorkedDays++;
        } else {
            $actualWorkedDays++; // Present (type 1)
        }
    }

    // STEP 2: Weekly payroll full salary logic
    if ($payrollType === 'weekly' && $actualWorkedDays === 7) {
        $dailyWage = round($salary / 7, 2); // Keep same daily wage
        $deduction = 0; // No deductions since they attended 7 days
        $daysAbsent = 0;
    }

    // STEP 3: Cap the deduction
    $deduction = min($deduction, $salary);

    return [$deduction, $dailyWage, $daysAbsent];
}



    public function calculateVacationDeductions($employee, $startDate, $endDate)
{

    if (!$employee || !$employee->company || !$employee->company->attendanceSetting) {
        return [0, 0]; // default if data is missing
    }

    $vacationLimit = $employee->company->attendanceSetting->vacation_balance ?? 25;
    $deductionRate = $employee->company->attendanceSetting->vacation_deduction_rate ?? 200;

    // Calculate used vacation days up to end date
    $totalUsed = $employee->vacations()
        ->whereDate('end_date', '<=', $endDate)
        ->sum('total');

    $rest = max(0, $vacationLimit - $totalUsed);
    $excess = max(0, $totalUsed - $vacationLimit);

    return [$rest, $excess * $deductionRate];
}


       public function calculateDeductions($employee, $startDate, $endDate)
    {
    return $employee->deductions()
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('money_deduction');
    }


    public function calculateBonus($employee, $startDate, $endDate)
    {

        if (!$employee || !$employee->company || !$employee->company->attendanceSetting) {
            return 0; // handle missing data safely
        }
        $bonusPerHour = $employee->company->attendanceSetting->bonus_per_hour ?? 0;


        // Calculate total bonus hours from the 'times' table
        $bonusHours = $employee->times()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('hours');

        return $bonusHours * $bonusPerHour;
    }


    public function calculateTaxes($employee, $companyId, $salary)
    {
        if (!Tax::where('company_id', $companyId)->where('apply_to_payroll', true)->exists()) return 0;

        return $employee->taxes()->where('company_id', $companyId)
            ->orWhereNull('employee_id')
            ->get()
            ->sum(fn($tax) => $salary * $tax->percent / 100);
    }

    public function calculateInsurance($employee, $companyId, $salary)
    {
        if (!Insurance::where('company_id', $companyId)->where('apply_to_payroll', true)->exists()) return 0;

        return $employee->insurances()->where('company_id', $companyId)->orWhereNull('employee_id')
            ->get()
            ->sum(fn($insurance) => $salary * $insurance->percent / 100);
    }
}
