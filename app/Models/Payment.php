<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

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

    /* ===============================
       🔍 GET RECORD METHOD
       (EXACT SAME PATTERN AS PAYROLL)
    ================================ */
    public static function getRecord()
    {
        $company_id = session('company_id');
        $branch_id  = session('branch_id');

        $query = self::select(
                'payments.*',
                'users.name',
                'users.email',
                'users.phone_number'
            )
            ->join('users', 'users.id', '=', 'payments.employee_id');

        /* ===============================
           🔍 Branch & Company Logic
           (EXACT SAME PATTERN AS PAYROLL)
        ================================ */

        if (!empty($branch_id)) {

            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch → all company payments
                $query->where('users.company_id', $company_id);
            } else {
                // Normal branch → only its payments
                $query->where('users.branch_id', $branch_id);
            }

        } else {
            // No branch in session → company wide
            $query->where('users.company_id', $company_id);
        }

        /* ===============================
           🔎 Search Filters
        ================================ */

        if (!empty(Request::get('name'))) {
            $query->where('users.name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('month'))) {
            $query->whereMonth('payments.payment_date', Request::get('month'));
        }

        if (!empty(Request::get('year'))) {
            $query->whereYear('payments.payment_date', Request::get('year'));
        }

        // 🆕 Branch dropdown filter (manual override)
        if (!empty(Request::get('filter_branch_id'))) {
            $query->where('users.branch_id', Request::get('filter_branch_id'));
        }

        /* ===============================
           📄 Pagination
        ================================ */

        $query->orderBy('payments.payment_date', 'desc')
              ->orderBy('payments.id', 'desc');

        $result = $query->paginate(15);
        $result->appends(Request::all());

        return $result;
    }


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
