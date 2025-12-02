<?php
namespace App\Models;

use Request;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
    'name', 'email', 'phone_number', 'hire_date', 'birth_date',
    'job_id', 'salary_type', 'salary', 'work_start_time', 'work_end_time',
    'company_id', 'manager_id', 'department_id', 'is_role', 'password' ,'branch_id','macaddress','work_hours_per_day', 'working_days', 'shifts','second_start_time','second_end_time','main_salary'

];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

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

    $query = self::select('users.*', 'branches.name as branch_name', 'branches.is_main')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id');

    // 🔍 filtering logic for branch and main branch handling
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all employees in the company
            $query->where('users.company_id', $company_id);
        } else {
            // If current branch is not main, show only employees of this specific branch
            $query->where('users.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all employees in the company
        $query->where('users.company_id', $company_id);
    }

    // Apply search filters if any
    if (!empty(Request::get('id'))) {
        $query->where('users.id', '=', Request::get('id'));
    }
    if (!empty(Request::get('name'))) {
        $query->where('users.name', 'like', '%' . Request::get('name') . '%');
    }
    if (!empty(Request::get('email'))) {
        $query->where('users.email', 'like', '%' . Request::get('email') . '%');
    }

    // 🆕 NEW: Branch filter by ID (from dropdown)
    if (!empty(Request::get('filter_branch_id'))) {
        $query->where('users.branch_id', '=', Request::get('filter_branch_id'));
    }

    // Handle per_page parameter
    $perPage = Request::get('per_page', 5); // Default to 5

    $query->orderBy('users.id', 'desc');

    if ($perPage === 'all') {
        return $query->get();
    } else {
        $paginatedResults = $query->paginate((int)$perPage);
        // 🔧 FIX: Append all request parameters to pagination links
        $paginatedResults->appends(Request::all());
        return $paginatedResults;
    }
}

public function getEmployeeStatus()
{
    $today = date('Y-m-d');
    $now = date('H:i:s'); // الساعة الحالية
    $companyId = session('company_id');

    // 1) Vacation - أزرق
    $vacation = \DB::table('vacations')
        ->where('employee_id', $this->id)
        ->where('company_id', $companyId)
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->first();

    if ($vacation) {
        return ['text' => 'إجازة', 'color' => '#007bff']; // أزرق
    }

    // 2) Attendance
    $attendance = \DB::table('attendances')
        ->where('employee_id', $this->id)
        ->where('company_id', $companyId)
        ->whereDate('attendance_date', $today)
        ->orderByDesc('id')
        ->first();

    if ($attendance) {
        $check_in = $attendance->check_in;
        $check_out = $attendance->check_out;

        // حالة: سجل دخول ولم يسجل خروج حتى الآن → يعمل الآن (أخضر)
        if (!empty($check_in) && empty($check_out)) {
            return ['text' => 'يعمل الآن', 'color' => '#28a745']; // أخضر
        }

        // حالة: سجل دخول وخروج، والوقت الحالي بعد وقت الخروج → في العمل (رمادي)
        if (!empty($check_in) && !empty($check_out) && $now > $check_out) {
            return ['text' => 'في العمل', 'color' => '#6c757d']; // رمادي
        }

        // حالة: سجل دخول وخروج، والوقت الحالي بين الدخول والخروج → يعمل الآن (أخضر)
        if (!empty($check_in) && !empty($check_out) && $now >= $check_in && $now <= $check_out) {
            return ['text' => 'يعمل الآن', 'color' => '#28a745']; // أخضر
        }
    }

    // 3) Transfer
    if (!empty($this->transfer_status) && $this->transfer_status == 1) {
        return ['text' => 'نقل كفالة', 'color' => '#ffc107']; // أصفر
    }

    // 4) Default
    return ['text' => 'في العمل', 'color' => '#6c757d']; // رمادي
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
        return $this->hasMany(Payroll::class,'employee_id');
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


    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class, 'user_id');
    }
    public function resignations()
    {
        return $this->hasMany(Resignation::class, 'employee_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'employee_location');
    }


}
