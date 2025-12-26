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
        'pay_date',
        'branch_id',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public static function getRecord()
    {
        $company_id = session('company_id');
        $branch_id = session('branch_id');

        $query = self::select(
            'payrolls.*',
            'users.name',
            \DB::raw("COALESCE(insurances.apply_to_payroll, 0) as is_insured"),
            \DB::raw("
            COALESCE(users.housing_allowance, 0)
          + COALESCE(users.transportation_allowance, 0)
          + COALESCE(users.other_allowances, 0)
          AS total_allowances
        ")
        )
            ->join('users', 'users.id', '=', 'payrolls.employee_id')
            ->leftJoin('insurances', function ($join) {
                $join->on('insurances.employee_id', '=', 'users.id')
                    ->where('insurances.apply_to_payroll', 1);
            });

        /* ===============================
           🔍 Branch & Company Logic
           (EXACT SAME PATTERN AS USERS)
        ================================ */

        if (!empty($branch_id)) {

            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch → all company payrolls
                $query->where('users.company_id', $company_id);
            } else {
                // Normal branch → only its payrolls
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
            $query->whereMonth('payrolls.start_date', Request::get('month'));
        }

        if (!empty(Request::get('year'))) {
            $query->whereYear('payrolls.start_date', Request::get('year'));
        }

        if (!empty(Request::get('payroll_type'))) {
            $query->where('payrolls.payroll_type', Request::get('payroll_type'));
        }

        // 🆕 Branch dropdown filter (manual override)
        if (!empty(Request::get('filter_branch_id'))) {
            $query->where('users.branch_id', Request::get('filter_branch_id'));
        }

        /* ===============================
           📄 Pagination
        ================================ */

        $query->orderBy('payrolls.id', 'desc');

        $result = $query->paginate(15);
        $result->appends(Request::all());

        return $result;
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
