<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Insurance extends Model
{
    protected $fillable = [
        'code',
        'name',
        'i_percent',
        'apply_to_payroll',
        'company_id',
        'employee_id',
        'from_basic',
        'from_transportation',
        'from_housing',
        'from_other_allowances',

        'basic_percent',
        'housing_percent',
        'transportation_percent',
        'other_allowances_percent',
    ];

    protected $casts = [
        'apply_to_payroll' => 'boolean',
        'from_basic' => 'boolean',
        'from_transportation' => 'boolean',
        'from_housing' => 'boolean',
        'from_other_allowances' => 'boolean',
        'basic_percent' => 'float',
        'housing_percent' => 'float',
        'transportation_percent' => 'float',
        'other_allowances_percent' => 'float',
    ];

    protected $attributes = [
        'from_basic' => 0,
        'from_transportation' => 0,
        'from_housing' => 0,
        'from_other_allowances' => 0,
        'basic_percent' => 0,
        'housing_percent' => 0,
        'transportation_percent' => 0,
        'other_allowances_percent' => 0,
    ];

    public function setBasicPercentAttribute($value)
    {
        $this->attributes['basic_percent'] = is_numeric($value) ? (float) $value : 0;
    }

    public function setHousingPercentAttribute($value)
    {
        $this->attributes['housing_percent'] = is_numeric($value) ? (float) $value : 0;
    }

    public function setTransportationPercentAttribute($value)
    {
        $this->attributes['transportation_percent'] = is_numeric($value) ? (float) $value : 0;
    }

    public function setOtherAllowancesPercentAttribute($value)
    {
        $this->attributes['other_allowances_percent'] = is_numeric($value) ? (float) $value : 0;
    }

    // You can define relationships if needed, for example:

    static public function getRecord($request, $company_id)
    {
        // Get the company_id from the session or request
        $company_id = session('company_id');  // You can adjust this to get from request if needed.
        $branch_id = session('branch_id');

        $query = self::select(
            'insurances.*',
            'users.name as employee_name',
            'branches.name as branch_name'  // Add this line to select branch name
        )
            ->join('users', 'users.id', '=', 'insurances.employee_id')
            ->leftJoin('branches', 'insurances.branch_id', '=', 'branches.id')  // Add this line
            ->where('users.company_id', $company_id)
            ->orderBy('insurances.id', 'desc');

        // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
        if (!empty($branch_id)) {
            // Get the current branch info to check if it's main
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                // If current branch is main branch, show all in the company
                $query->where('insurances.company_id', $company_id);
            } else {
                // If current branch is not main, show only of this specific branch
                $query->where('insurances.branch_id', $branch_id);
            }
        } else {
            // If no branch_id in session, show all in the company
            $query->where('insurances.company_id', $company_id);
        }

        if (!empty($request->name)) {
            $query->where('insurances.name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->code)) {
            $query->where('insurances.code', 'like', '%' . $request->code . '%');
        }

        // 🆕 Add branch filter (optional - same pattern as taxes module)
        if (!empty($request->filter_branch_id)) {
            $query->where('insurances.branch_id', $request->filter_branch_id);
        }

        return $query->paginate(6);
    }


    public function employees()
    {
        return $this->belongsToMany(User::class, 'insurance_user', 'insurance_id', 'user_id');
    }
    public function employee()
{
    return $this->belongsTo(User::class, 'employee_id');
}


    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
