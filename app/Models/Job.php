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
    $company_id = session('company_id'); // Or use $request->company_id if passed in the request
    $branch_id = session('branch_id');

        $query = self::select('jobs.*', 'departments.department_name')
                     ->leftJoin('departments', 'jobs.department_id', '=', 'departments.id')
                     ->orderBy('jobs.id', 'desc');

    if ($branch_id) {
        $query->where('jobs.branch_id', $branch_id);
    } else {
        $query->where('jobs.company_id', $company_id);
    }


        if (!empty(Request::get('id'))) {
            $query->where('jobs.id', Request::get('id'));
        }

        if (!empty(Request::get('job_title'))) {
            $query->where('jobs.job_title', 'like', '%' . Request::get('job_title') . '%');
        }

        if (!empty(Request::get('min_salary'))) {
            $query->where('jobs.min_salary', '>=', Request::get('min_salary'));
        }

        if (!empty(Request::get('max_salary'))) {
            $query->where('jobs.max_salary', '<=', Request::get('max_salary'));
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
}
