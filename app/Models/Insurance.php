<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Insurance extends Model
{
    protected $fillable = ['code', 'name', 'percent', 'company_id','employee_id'];

    // You can define relationships if needed, for example:

        static public function getRecord($request, $company_id)
        {
            // Get the company_id from the session or request
        $company_id = session('company_id');  // You can adjust this to get from request if needed.
        $branch_id = session('branch_id');

            $query = self::select('insurances.*', 'users.name as employee_name')
                        ->join('users', 'users.id', '=', 'insurances.employee_id')
                        ->where('users.company_id', $company_id)
                        ->orderBy('insurances.id', 'desc');



    if ($branch_id) {
        $query->where('insurances.branch_id', $branch_id);
    } else {
        $query->where('insurances.company_id', $company_id);
    }

            if (!empty($request->name)) {
                $query->where('insurances.name', 'like', '%' . $request->name . '%');
            }

            if (!empty($request->code)) {
                $query->where('insurances.code', 'like', '%' . $request->code . '%');
            }

            return $query->paginate(6);
        }


    public function employees()
    {
        return $this->belongsToMany(User::class, 'insurance_user', 'insurance_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
