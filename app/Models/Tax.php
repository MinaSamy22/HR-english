<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tax extends Model
{
    protected $fillable = ['code', 'name', 'percent', 'company_id','employee_id'];

    // You can define relationships if needed, for example:

static public function getRecord($request, $company_id)
{
    // Get the company_id from the session or request
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select(
                'taxes.*', 
                'users.name as employee_name',
                'branches.name as branch_name'  // Add this line to select branch name
            )
                ->join('users', 'users.id', '=', 'taxes.employee_id')
                ->leftJoin('branches', 'taxes.branch_id', '=', 'branches.id')
                ->where('users.company_id', $company_id)
                ->orderBy('taxes.id', 'desc');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            $query->where('taxes.company_id', $company_id);
        } else {
            $query->where('taxes.branch_id', $branch_id);
        }
    } else {
        $query->where('taxes.company_id', $company_id);
    }

    // Apply search filters
    if (!empty($request->name)) {
        $query->where('taxes.name', 'like', '%' . $request->name . '%');
    }

    if (!empty($request->code)) {
        $query->where('taxes.code', 'like', '%' . $request->code . '%');
    }

    // 🆕 Add branch filter (same pattern as other modules)
    if (!empty($request->filter_branch_id)) {
        $query->where('taxes.branch_id', $request->filter_branch_id);
    }

    return $query->paginate(6);
}



    public function employees()
    {
        return $this->belongsToMany(User::class, 'tax_user', 'tax_id', 'user_id');
    }
}
