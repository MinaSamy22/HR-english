<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'base_salary',
        'bonuses',
        'deductions',
        'taxes',
        'net_pay',
        'pay_date'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    static public function getRecord($company_id)
    {
        // Get the company_id from the session or request
        $company_id = session('company_id');
        $branch_id = session('branch_id');

        $return = self::select(
            'payrolls.*',
            'users.name',
            \DB::raw("COALESCE(insurances.apply_to_payroll, 0) as is_insured")
        )
            ->join('users', 'users.id', '=', 'payrolls.employee_id')
            ->leftJoin('insurances', function ($join) {
                $join->on('insurances.employee_id', '=', 'users.id')
                    ->where('insurances.apply_to_payroll', 1);
            })

            ->where('users.company_id', $company_id)
            ->orderBy('payrolls.id', 'desc');

        // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
        if (!empty($branch_id)) {
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                $return->where('payrolls.company_id', $company_id);
            } else {
                $return->where('payrolls.branch_id', $branch_id);
            }
        } else {
            $return->where('payrolls.company_id', $company_id);
        }

        // Apply search filters
        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('month'))) {
            $return = $return->whereMonth('payrolls.created_at', Request::get('month'));
        }

        if (!empty(Request::get('year'))) {
            $return = $return->whereYear('payrolls.created_at', Request::get('year'));
        }

        if (!empty(Request::get('payroll_type'))) {
            $return = $return->where('payrolls.payroll_type', Request::get('payroll_type'));
        }

        // 🆕 Add branch filter (same pattern as other modules)
        if (!empty(Request::get('filter_branch_id'))) {
            $return = $return->where('payrolls.branch_id', Request::get('filter_branch_id'));
        }

        $return = $return->paginate(15);

        return $return;
    }


    // NEW METHOD: Fixed payslip search with proper pay_date filtering
    static public function getPayslipRecord($company_id)
    {
        $return = self::select('payrolls.*', 'users.name', 'users.email', 'users.phone_number', 'users.hire_date')
            ->join('users', 'users.id', '=', 'payrolls.employee_id')
            ->where('users.company_id', $company_id)  // Filter by company_id
            ->orderBy('payrolls.pay_date', 'desc')
            ->orderBy('payrolls.id', 'desc');



        // FIXED: Search by month using pay_date instead of created_at
        if (!empty(Request::get('months'))) {
            $return = $return->whereMonth('payrolls.start_date', Request::get('months'));
        }

        $return = $return->paginate(15);

        return $return;
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
