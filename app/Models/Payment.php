<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_date',
        'company_id',
        'branch_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Get total paid amount for a specific payroll
    public static function getTotalPaidForPayroll($payrollId)
    {
        return self::where('payroll_id', $payrollId)->sum('paid_amount');
    }

    // Get remaining amount for a specific payroll
    public static function getRemainingForPayroll($payrollId)
    {
        $payroll = Payroll::find($payrollId);
        if (!$payroll) return 0;

        $totalPaid = self::getTotalPaidForPayroll($payrollId);
        return $payroll->net_pay - $totalPaid;
    }
}
