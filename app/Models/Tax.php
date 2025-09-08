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
        $company_id = session('company_id');  // You can adjust this to get from request if needed.
        $branch_id = session('branch_id');

            $query = self::select('taxes.*', 'users.name as employee_name')
                        ->join('users', 'users.id', '=', 'taxes.employee_id')
                        ->where('users.company_id', $company_id)
                        ->orderBy('taxes.id', 'desc');


        // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('taxes.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('taxes.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('taxes.company_id', $company_id);
    }

            if (!empty($request->name)) {
                $query->where('taxes.name', 'like', '%' . $request->name . '%');
            }

            if (!empty($request->code)) {
                $query->where('taxes.code', 'like', '%' . $request->code . '%');
            }

            return $query->paginate(6);
        }



    public function employees()
    {
        return $this->belongsToMany(User::class, 'tax_user', 'tax_id', 'user_id');
    }
}
