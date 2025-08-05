<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::guard('employee')->user(); // get the logged-in employee

        if (!$employee) {
            abort(403, 'Unauthorized');
        }

        $employee_id = $employee->id;

        // Start building the query
        $query = Attendance::where('employee_id', $employee_id);

        // Check if any filters are applied
        $hasFilters = $request->filled('date_from') || $request->filled('date_to') || ($request->filled('status') && $request->status != '');

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        // Apply status filter - works independently
        if ($request->filled('status') && $request->status != '') {
            $query->where('attendance_type', (int)$request->status);
        }

        // Only apply default month filter if NO filters are applied at all
        if (!$hasFilters) {
            $query->whereMonth('attendance_date', Carbon::now()->month)
                  ->whereYear('attendance_date', Carbon::now()->year);
        }

        $data['getRecord'] = $query->orderBy('attendance_date', 'desc')->paginate(10);
        $data['employee'] = $employee; // Pass employee data to get work times

        return view('EmployeeInterface.Attendance.index', $data);
    }




}
