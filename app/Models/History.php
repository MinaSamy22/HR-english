<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class History extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   // protected $table = 'histories'; //34an lo hnak esm elgdwal mo5tlf
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    static public function getRecord($request)
    {
// Retrieve branch_id or company_id if no branch exist
    $company_id = session('company_id'); // Or use $request->company_id if passed in the request
    $branch_id = session('branch_id');

        // Initialize the query with joins to include job and department data
         $query = self::select('histories.*', 'jobs.job_title', 'departments.department_name')
                     ->join('jobs', 'jobs.id', '=', 'histories.job_id')
                     ->join('departments', 'departments.id', '=', 'histories.department_id')
                     ->orderBy('histories.id', 'desc');

        // Apply branch_id filter if available
    if ($branch_id) {
        $query->where('histories.branch_id', $branch_id);
    } else {
        $query->where('histories.company_id', $company_id);
    }

        // Apply search filters
        if (!empty(Request::get('employee_name'))) {
            $query->where('employee_name', 'like', '%' . Request::get('employee_name') . '%');
        }

        if (!empty(Request::get('job_title'))) {
            $query->where('jobs.job_title', 'like', '%' . Request::get('job_title') . '%');
        }

        if (!empty(Request::get('start_date'))) {
            $query->where('histories.start_date', '>=', Request::get('start_date'));
        }

        // Return paginated result
        return $query->paginate(5);
    }

public function company()
{
    return $this->belongsTo(Company::class);
}

}
