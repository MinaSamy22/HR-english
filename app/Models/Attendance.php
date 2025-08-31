<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $fillable = [
        'attendance_date',
        'employee_id',
        'attendance_type',
        'created_by',
        'company_id',
        'check_in',
        'check_out',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }




    static public function getRecord()
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // Determine filtering logic based on branch_id and is_main
    $showAllCompanyData = false;
    $filterBranchId = null;

    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all attendances in the company
            $showAllCompanyData = true;
        } else {
            // If current branch is not main, show only attendances of employees from this branch
            $filterBranchId = $branch_id;
        }
    } else {
        // If no branch_id in session, show all attendances in the company
        $showAllCompanyData = true;
    }

    $query = self::select('attendances.*', 'employee.name as employee_name')
        ->join('users as employee', 'employee.id', '=', 'attendances.employee_id')
        ->orderBy('attendances.id', 'desc');

    // Apply branch/company filtering through the employee's branch
    if ($showAllCompanyData) {
        // Show all company attendances
        $query->where('employee.company_id', $company_id);
    } else {
        // Show only attendances for employees from specific branch
        $query->where('employee.branch_id', $filterBranchId);
    }

    // 🔍 Apply search filters
    if (!empty(Request::get('employee_name'))) {
        $query->where('employee.name', 'like', '%' . Request::get('employee_name') . '%');
    }

    if (!empty(Request::get('attendance_date'))) {
        $query->where('attendances.attendance_date', 'like', '%' . Request::get('attendance_date') . '%');
    }

    if (!empty(Request::get('attendance_type'))) {
        $query->where('attendances.attendance_type', 'like', '%' . Request::get('attendance_type') . '%');
    }

    if (!empty(Request::get('start_date'))) {
        if (empty(Request::get('end_date'))) {
            $query->whereDate('attendances.attendance_date', Request::get('start_date'));
        } else {
            $query->whereDate('attendances.attendance_date', '>=', Request::get('start_date'))
                  ->whereDate('attendances.attendance_date', '<=', Request::get('end_date'));
        }
    }

    return $query->get(); // Use ->paginate(5) if you want pagination
}






static public function ChechAlreadyAttendance($employee_id, $attendance_date){

return Attendance::where('employee_id', '=',$employee_id)->where('attendance_date', '=',$attendance_date)->first();
}

public function company()
{
    return $this->belongsTo(Company::class);
}

}
