<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class EarlyLeave extends Model
{
    use HasFactory;

    protected $table = 'early_leaves';

    protected $fillable = [
        'employee_id',
        'request_date',
        'requested_leave_time',
        'reason',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    static public function getRecord($request)
    {
        $company_id = session('company_id');
        $branch_id  = session('branch_id');

        $return = self::select(
                'early_leaves.*',
                'users.name',
                'users.branch_id as user_branch_id',
                'branches.name as branch_name'
            )
            ->join('users', 'users.id', '=', 'early_leaves.employee_id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->where('users.company_id', $company_id)
            ->orderBy('early_leaves.id', 'desc');

        if (!empty($branch_id)) {
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                // main branch sees all
            } else {
                $return->where('users.branch_id', $branch_id);
            }
        }

        if (!empty(Request::get('name'))) {
            $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('filter_branch_id'))) {
            $return->where('users.branch_id', Request::get('filter_branch_id'));
        }

        if (!empty(Request::get('from_date'))) {
            $return->whereDate('early_leaves.request_date', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $return->whereDate('early_leaves.request_date', '<=', Request::get('to_date'));
        }

        return $return->paginate(10);
    }
}
