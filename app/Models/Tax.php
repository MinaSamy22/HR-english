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


    if ($branch_id) {
        $query->where('taxes.branch_id', $branch_id);
    } else {
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
