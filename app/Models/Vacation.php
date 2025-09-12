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

    $return = self::select('vacations.*', 'users.name', 'branches.name as branch_name') // 🆕 Added branch_name
        ->join('users', 'users.id', '=', 'vacations.employee_id')
        ->leftJoin('branches', 'vacations.branch_id', '=', 'branches.id') // 🆕 Added branch join
        ->where('users.company_id', $company_id)
        ->orderBy('vacations.id', 'desc');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            $return->where('vacations.company_id', $company_id);
        } else {
            $return->where('vacations.branch_id', $branch_id);
        }
    } else {
        $return->where('vacations.company_id', $company_id);
    }

    // Apply search filters
    if (!empty(Request::get('name'))) {
        $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
    }

    // Add branch filter
    if (!empty(Request::get('filter_branch_id'))) {
        $return = $return->where('vacations.branch_id', Request::get('filter_branch_id'));
    }

    return $return->paginate(6);
}

        public function company()
        {
            return $this->belongsTo(Company::class);
        }


}
