<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class EarlyLeaveRequest extends Model
{
    use HasFactory;

    static public function getRecord($request)
    {
        $company_id = session('company_id');
        $branch_id  = session('branch_id');

        $return = self::select(
                'early_leave_requests.*',
                'users.name',
                'users.branch_id as user_branch_id',
                'branches.name as branch_name'
            )
            ->join('users', 'users.id', '=', 'early_leave_requests.employee_id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->where('users.company_id', $company_id)
            ->orderBy('early_leave_requests.id', 'desc');

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
            $return->whereDate('early_leave_requests.request_date', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $return->whereDate('early_leave_requests.request_date', '<=', Request::get('to_date'));
        }

        return $return->paginate(10);
    }

    protected $fillable = [
        'employee_id',
        'request_date',
        'requested_leave_time',
        'reason',
        'status',
        'urgent_request',
        'is_seen', // Make sure this is in fillable
        'created_by',
        'updated_by',
    ];

    protected $guarded = []; // Or remove $fillable entirely and use this

    protected $casts = [
        'request_date' => 'date',
        'urgent_request' => 'boolean',
        'is_seen' => 'boolean',
    ];

    /**
     * Get the user (employee) who made the request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for urgent requests
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent_request', 1);
    }

    /**
     * Scope for unseen requests
     */
    public function scopeUnseen($query)
    {
        return $query->where('is_seen', 0);
    }
}
