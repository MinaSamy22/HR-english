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
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            $showAllCompanyData = true;
        } else {
            $filterBranchId = $branch_id;
        }
    } else {
        $showAllCompanyData = true;
    }

    // 🆕 Updated query to include branch name
    $query = self::select(
            'attendances.*',
            'employee.name as employee_name',
            'branches.name as branch_name'  // Add branch name
        )
        ->join('users as employee', 'employee.id', '=', 'attendances.employee_id')
        ->leftJoin('branches', 'employee.branch_id', '=', 'branches.id')  // Join with branches table
        ->orderBy('attendances.id', 'desc');

    // Apply branch/company filtering through the employee's branch
    if ($showAllCompanyData) {
        $query->where('employee.company_id', $company_id);
    } else {
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

    // Add branch filter
    if (!empty(Request::get('filter_branch_id'))) {
        $query->where('employee.branch_id', Request::get('filter_branch_id'));
    }

    // Handle per_page parameter
    $perPage = Request::get('per_page', 10); // Default to 10

    if ($perPage === 'all') {
        return $query->get();
    } else {
        $paginatedResults = $query->paginate((int)$perPage);
        // 🔧 FIX: Append all request parameters to pagination links
        $paginatedResults->appends(Request::all());
        return $paginatedResults;
    }
}

static public function getAllRecordsForExport()
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // Determine filtering logic based on branch_id and is_main
    $showAllCompanyData = false;
    $filterBranchId = null;

    if (!empty($branch_id)) {
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            $showAllCompanyData = true;
        } else {
            $filterBranchId = $branch_id;
        }
    } else {
        $showAllCompanyData = true;
    }

    $query = self::select(
            'attendances.*',
            'employee.name as employee_name',
            'branches.name as branch_name'
        )
        ->join('users as employee', 'employee.id', '=', 'attendances.employee_id')
        ->leftJoin('branches', 'employee.branch_id', '=', 'branches.id')
        ->orderBy('attendances.id', 'desc');

    if ($showAllCompanyData) {
        $query->where('employee.company_id', $company_id);
    } else {
        $query->where('employee.branch_id', $filterBranchId);
    }

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

    if (!empty(Request::get('filter_branch_id'))) {
        $query->where('employee.branch_id', Request::get('filter_branch_id'));
    }

    return $query->get();
}






static public function ChechAlreadyAttendance($employee_id, $attendance_date){

return Attendance::where('employee_id', '=',$employee_id)->where('attendance_date', '=',$attendance_date)->first();
}

public function company()
{
    return $this->belongsTo(Company::class);
}

}
