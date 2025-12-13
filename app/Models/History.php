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
    'employee_name',
    'employee_id',
    'email',
    'password',
    'phone_number',
    'hire_date',
    'birth_date',
    'nationality',
    'country_code',
    'residency_expiry',
    'passport_number',
    'passport_expiry',
    'residency_number',
    'iban',
    'residency_job',
    'salary_type',
    'salary',
    'work_start_time',
    'work_end_time',
    'shift_count',
    'second_work_start_time',
    'second_work_end_time',
    'macaddress',
    'is_biometric',
    'main_salary',
    'additional_salary',
    'attachment',
    'work_hours_per_day',
    'working_days',
    'vacation_balance',
    'bonus_per_hour',
    'is_role',
    'start_date',
    'end_date',
    'job_id',
    'manager_id',
    'department_id',
    'company_id',
    'branch_id',
    'resignation_date',
    'resignation_reason',
    'deleted_at',
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

public static function getRecord($request)
{
    // Retrieve branch_id or company_id if no branch exist
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // Initialize the query with joins to include job and department data
    $query = self::select(
            'histories.*',
            'jobs.job_title',
            'departments.department_name',
            'branches.name as branch_name'
        )
        ->join('jobs', 'jobs.id', '=', 'histories.job_id')
        ->join('departments', 'departments.id', '=', 'histories.department_id')
        ->leftJoin('branches', 'histories.branch_id', '=', 'branches.id')
        ->orderBy('histories.id', 'desc');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('histories.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('histories.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('histories.company_id', $company_id);
    }

    // Apply search filters
    if (!empty(Request::get('employee_name'))) {
        $query->where('histories.employee_name', 'like', '%' . Request::get('employee_name') . '%');
    }

    if (!empty(Request::get('job_title'))) {
        $query->where('jobs.job_title', 'like', '%' . Request::get('job_title') . '%');
    }



    // Branch filter for main branch users
    if (!empty(Request::get('filter_branch_id'))) {
        $query->where('histories.branch_id', Request::get('filter_branch_id'));
    }

    // Return paginated result
    return $query->paginate(5);
}

public function company()
{
    return $this->belongsTo(Company::class);
}

public function manager()
{
    return $this->belongsTo(Manager::class, 'manager_id');
}
 // Job
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
