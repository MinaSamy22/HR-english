<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
    'name', 'email', 'phone_number', 'hire_date', 'birth_date',
    'job_id', 'salary_type', 'salary', 'work_start_time', 'work_end_time',
    'company_id', 'manager_id', 'department_id', 'is_role', 'password' ,'branch_id','macaddress'
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
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select('users.*');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id
              // 🔍 Filter by branch_id or fallback to company_id
    if (!empty($branch_id)) {
        $query->where('users.branch_id', $branch_id);
    } else {
        $query->where('users.company_id', $company_id);
    }

    // Apply search filters if any
    if (!empty(Request::get('id'))) {
        $query->where('id', '=', Request::get('id'));
    }
    if (!empty(Request::get('name'))) {
        $query->where('name', 'like', '%' . Request::get('name') . '%');
    }
    if (!empty(Request::get('email'))) {
        $query->where('email', 'like', '%' . Request::get('email') . '%');
    }

    return $query->orderBy('id', 'desc')->paginate(5);
}


   public static function getAllRecordsForExport($request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select('users.*');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id
    if ($branch_id) {
        $query->where('branch_id', $branch_id);
    } elseif ($company_id) {
        $query->where('company_id', $company_id);
    }

    // Apply search filters if any
    if (!empty($request['id'])) {
        $query->where('id', '=', $request['id']);
    }
    if (!empty($request['name'])) {
        $query->where('name', 'like', '%' . $request['name'] . '%');
    }
    if (!empty($request['email'])) {
        $query->where('email', 'like', '%' . $request['email'] . '%');
    }

    return $query->orderBy('id', 'desc')->get();
}


public function job()
{
    return $this->belongsTo(Job::class);
}

public function get_job_single(){
    return $this->belongsTo(Job::class, "job_id");
}

public function get_manager_single(){
    return $this->belongsTo(Manager::class, "manager_id");
}

public function get_department_single(){
    return $this->belongsTo(Department::class, "department_id");
}




public function getAttendance($employee_id,$attendance_date)
{
    return Attendance::ChechAlreadyAttendance($employee_id,$attendance_date);
}


public function payrolls()
{
    return $this->hasMany(Payroll::class);
}

public function times()
{
    return $this->hasMany(Time::class, 'employee_id');
}

public function department()
{
    return $this->belongsTo(Department::class);
}

public function company()
{
    return $this->belongsTo(Company::class);
}

public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function attendances()
{
    return $this->hasMany(Attendance::class, 'employee_id');
}

public function vacations()
{
    return $this->hasMany(Vacation::class, 'employee_id');
}
public function deductions()
{
    return $this->hasMany(Deduction::class, 'employee_id');
}
public function taxes()
{
    return $this->hasMany(Tax::class, 'employee_id');
}
public function insurances(){
    return $this->hasMany(Insurance::class, 'employee_id');
}


}
