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

    $query = self::select('attendances.*', 'employee.name as employee_name')
        ->join('users as employee', 'employee.id', '=', 'attendances.employee_id')
        ->orderBy('attendances.id', 'desc');

    // 🔎 Filter by branch_id or fallback to company_id
    if (!empty($branch_id)) {
        $query->where('employee.branch_id', $branch_id);
    } else {
        $query->where('employee.company_id', $company_id);
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
