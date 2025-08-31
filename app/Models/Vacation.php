<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'total',
        'vacation_type',
        'company_id',
        'branch_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    static public function getRecord($request)
    {
        // Get the company_id from the session or request
    $company_id = session('company_id');
    $branch_id = session('branch_id');

        $return = self::select('vacations.*', 'users.name')
            ->join('users', 'users.id', '=', 'vacations.employee_id')
            ->where('users.company_id', $company_id)  // Filter by company_id
            ->orderBy('vacations.id', 'desc');  // Ensure ordering by vacation ID

     // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $return->where('vacations.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $return->where('vacations.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $return->where('vacations.company_id', $company_id);
    }


        // logic of the search box
        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');  // Search by name
        }

        // End logic of search

        $return = $return->paginate(6);  // Paginate the results

        return $return;
    }


        public function company()
        {
            return $this->belongsTo(Company::class);
        }


}
