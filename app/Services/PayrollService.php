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
use Carbon\Carbon;

class PayrollService
{

public function calculateAttendanceDeductions($employee, $salary, $startDate, $endDate, $companyId, $payrollType = 'monthly')
{
    if (!$employee || !$employee->company || !$employee->company->attendanceSetting) {
        return [0, 0, 0, 0]; // خصم, أجر يومي, غياب, صافي مرتب
    }

    $settings = $employee->company->attendanceSetting;

    // 1. تحميل أيام العمل من الموظف أو الشركة
    $rawWorking = json_decode($employee->working_days ?? '[]', true);
    if (empty($rawWorking)) {
        $rawWorking = json_decode($settings->working_days ?? '[]', true);
    }
    if (!is_array($rawWorking)) $rawWorking = [];

    $numToName = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
    $shortMap = [
        'Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday',
        'mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'
    ];
    $arabicMap = [
        'السبت'=>'Saturday','الأحد'=>'Sunday','الاحد'=>'Sunday','الاثنين'=>'Monday','الإثنين'=>'Monday','الثلاثاء'=>'Tuesday',
        'الأربعاء'=>'Wednesday','الخميس'=>'Thursday','الجمعة'=>'Friday'
    ];

    $workingDays = [];
    foreach ($rawWorking as $wd) {
        $wd = trim((string)$wd);
        if ($wd === '') continue;

        if (is_numeric($wd)) {
            $n = (int)$wd;
            if ($n >= 0 && $n <= 6) {
                $workingDays[] = $numToName[$n];
            } elseif ($n >=1 && $n <=7) {
                $workingDays[] = $numToName[$n % 7];
            }
            continue;
        }

        $uc = ucfirst(strtolower($wd));
        if (in_array($uc, array_values($numToName))) {
            $workingDays[] = $uc;
            continue;
        }

        if (isset($shortMap[$wd])) {
            $workingDays[] = $shortMap[$wd];
            continue;
        }

        if (isset($arabicMap[$wd])) {
            $workingDays[] = $arabicMap[$wd];
            continue;
        }
    }
    $workingDays = array_values(array_unique($workingDays));
    if (empty($workingDays)) {
        $workingDays = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
    }

    // 2. حساب الأجر اليومي
    if ($payrollType === 'daily') {
        $dailyWage = floatval($salary);
    } elseif ($payrollType === 'weekly') {
        $dailyWage = round($salary / 7, 2);
    } else {
        $dailyWage = round($salary / 30, 2);
    }

    // 3. تحميل الحضور
    $attendanceCollection = $employee->attendances()
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->get()
        ->mapWithKeys(function ($att) {
            $d = Carbon::parse($att->attendance_date)->format('Y-m-d');
            return [$d => $att];
        });

    // 4. العطلات الرسمية
    $officialHolidays = collect(json_decode($settings->official_holidays ?? '[]', true))
        ->map(fn($item) => is_array($item) ? ($item['date'] ?? null) : $item)
        ->filter()
        ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
        ->toArray();

    // 5. الإجازات
    $vacations = $employee->vacations()
        ->whereDate('start_date', '<=', $endDate)
        ->whereDate('end_date', '>=', $startDate)
        ->get();

    $vacationDates = [];
    foreach ($vacations as $vac) {
        $period = new \DatePeriod(
            new \DateTime($vac->start_date),
            new \DateInterval('P1D'),
            (new \DateTime($vac->end_date))->modify('+1 day')
        );
        foreach ($period as $d) $vacationDates[] = $d->format('Y-m-d');
    }

    // 6. الحساب
    $deduction = 0.0;
    $daysAbsent = 0;
    $attendedDays = 0;

    $period = new \DatePeriod(
        new \DateTime($startDate),
        new \DateInterval('P1D'),
        (new \DateTime($endDate))->modify('+1 day')
    );

    foreach ($period as $dt) {
        $dayName = $dt->format('l');
        $formattedDate = $dt->format('Y-m-d');

        if (!in_array($dayName, $workingDays)) continue;
        if (in_array($formattedDate, $officialHolidays)) continue;
        if (in_array($formattedDate, $vacationDates)) continue;

        $record = $attendanceCollection->get($formattedDate);

        $rawType = $record->attendance_type ?? null;
        $type = is_numeric($rawType) ? (int)$rawType : null;

        if ($type === 1) { // حاضر
            $attendedDays++;
        } elseif ($type === 2) { // متأخر
            $attendedDays++;
            $latePercent = $settings->late_deduction_percentage ?? 0;
            $deduction += $dailyWage * ($latePercent / 100);
        } elseif ($type === 3) { // غياب
            $daysAbsent++;
            $deduction += $dailyWage;
        } elseif ($type === 4) { // نصف يوم
            $attendedDays += 0.5;
            $halfPercent = $settings->half_day_deduction_percentage ?? 0;
            $deduction += $dailyWage * ($halfPercent / 100);
        } else { // مفيش سجل → غياب
            $daysAbsent++;
            $deduction += $dailyWage;
        }
    }

    // تطبيق الشروط الجديدة بناءً على عدد أيام الحضور
    if ($attendedDays < 15) {
        // إذا كان الحضور أقل من 15 يوم
        if ($attendedDays == 0) {
            // إذا لم يحضر إطلاقاً
            $netPay = 0;
            $deduction = $salary; // الخصم يساوي الراتب بالكامل
        } else {
            // إذا حضر أقل من 15 يوم
            $netPay = $attendedDays * $dailyWage;
            $deduction = $salary - $netPay; // الخصم هو الفرق بين الراتب الأساسي والأجر المستحق
        }
    } else {
        // إذا حضر 15 يوم أو أكثر
        $netPay = $salary - $deduction;
    }

    return [$deduction, $dailyWage, $daysAbsent, $netPay];
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
