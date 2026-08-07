<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Job extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

public static function getRecord($request)
{
    // Retrieve branch_id or company_id if no branch exist
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select('jobs.*', 'departments.department_name', 'branches.name as branch_name')
                 ->leftJoin('departments', 'jobs.department_id', '=', 'departments.id')
                 ->leftJoin('branches', 'jobs.branch_id', '=', 'branches.id')
                 ->orderBy('jobs.id', 'desc');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('jobs.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('jobs.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('jobs.company_id', $company_id);
    }

    // Apply search filters
if (!empty(Request::get('job_title'))) {
    $query->where('jobs.job_title', 'like', '%' . Request::get('job_title') . '%');
}

if (!empty(Request::get('filter_branch_id'))) {
    $query->where('jobs.branch_id', Request::get('filter_branch_id'));
}

    return $query->paginate(5);
}

    public function get_department_single()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'job_id');
    }
}
