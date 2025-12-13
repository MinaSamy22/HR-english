<?php
namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;
use App\Models\EarlyLeaveRequest;
use App\Models\Vacation;
use App\Models\Time;
use App\Models\Attendance;

class RequestController extends Controller
{
    public function index()
    {
        // Get current company ID and branch ID from session
        $companyId = session('company_id');
        $branchId = session('branch_id');

        // Determine filtering logic based on branch_id and is_main
        $showAllCompanyRequests = false;
        $filterBranchId = null;

        if ($branchId) {
            $currentBranch = Branch::find($branchId);
            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch - show all company requests
                $showAllCompanyRequests = true;
            } else {
                // Regular branch - show only this branch's requests
                $filterBranchId = $branchId;
            }
        } else {
            // No branch_id in session - show all company requests
            $showAllCompanyRequests = true;
        }

        // Create a closure for the user filtering logic
        $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
            if ($showAllCompanyRequests) {
                $query->where('company_id', $companyId);
            } else {
                $query->where('branch_id', $filterBranchId);
            }
        };

        // Get pending requests with consistent filtering
        $pendingVacations = VacationRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->with('user')
            ->get();

        $pendingExtraTimes = ExtraTimeRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->with('user')
            ->get();

        $pendingResignations = Resignation::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->with('user')
            ->get();

        $pendingLateRemovals = LateRemovalRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->with('user')
            ->get();

        // Get pending early leave requests
        $pendingEarlyLeaves = EarlyLeaveRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->with('user')
            ->get();

        return view('backend.requests.pending', compact(
            'pendingVacations',
            'pendingExtraTimes',
            'pendingResignations',
            'pendingLateRemovals',
            'pendingEarlyLeaves'
        ));
    }


public function processed(Request $request)
{
    // Get current company ID from session
    $companyId = session('company_id');

    // Get filter parameters
    $selectedMonth = $request->get('month');
    $searchName = $request->get('search_name');

    // Build processed requests queries with filters and company restriction
    $processedVacationsQuery = VacationRequest::whereIn('status', ['accepted', 'rejected'])
        ->whereHas('user', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->with('user')
        ->orderBy('updated_at', 'desc');

    $processedExtraTimesQuery = ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
        ->whereHas('user', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->with('user')
        ->orderBy('updated_at', 'desc');

    $processedResignationsQuery = Resignation::whereIn('status', ['accepted', 'rejected'])
        ->whereHas('user', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->with('user')
        ->orderBy('updated_at', 'desc');

    $processedLateRemovalsQuery = LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
        ->whereHas('user', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->with('user')
        ->orderBy('updated_at', 'desc');

    // Add Early Leave Requests query
    $processedEarlyLeavesQuery = EarlyLeaveRequest::whereIn('status', ['accepted', 'rejected'])
        ->whereHas('user', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->with('user')
        ->orderBy('updated_at', 'desc');

    // Apply month filter if selected
    if ($selectedMonth) {
        $year = date('Y');
        $month = $selectedMonth;

        $processedVacationsQuery->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        $processedExtraTimesQuery->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        $processedResignationsQuery->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        $processedLateRemovalsQuery->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        $processedEarlyLeavesQuery->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);
    }

    // Apply name search filter if provided
    if ($searchName) {
        $processedVacationsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                  ->where('company_id', $companyId);
        });

        $processedExtraTimesQuery->whereHas('user', function($query) use ($searchName, $companyId) {
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                  ->where('company_id', $companyId);
        });

        $processedResignationsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                  ->where('company_id', $companyId);
        });

        $processedLateRemovalsQuery->whereHas('user', function($query) use ($searchName, $companyId) {
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                  ->where('company_id', $companyId);
        });

        $processedEarlyLeavesQuery->whereHas('user', function($query) use ($searchName, $companyId) {
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                  ->where('company_id', $companyId);
        });
    }

    // Execute queries
    $processedVacations = $processedVacationsQuery->get();
    $processedExtraTimes = $processedExtraTimesQuery->get();
    $processedResignations = $processedResignationsQuery->get();
    $processedLateRemovals = $processedLateRemovalsQuery->get();
    $processedEarlyLeaves = $processedEarlyLeavesQuery->get();

    // Generate months list for filter dropdown
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    return view('backend.requests.processed', compact(
        'processedVacations',
        'processedExtraTimes',
        'processedResignations',
        'processedLateRemovals',
        'processedEarlyLeaves',
        'months',
        'selectedMonth',
        'searchName'
    ));
}

private function moveUserToHistory($resignation)
{
    $user = User::find($resignation->employee_id);

    if (!$user) {
        return; // لو مفيش موظف متسجل
    }

    // نقل البيانات لجداول histories
    History::create([
        'employee_name'        => $user->name,
        'employee_id'          => $user->id,
        'email'                => $user->email,
        'password'             => $user->password,
        'phone_number'         => $user->phone_number,
        'hire_date'            => $user->hire_date,
        'birth_date'           => $user->birth_date,
        'nationality'          => $user->nationality,
        'country_code'         => $user->country_code,
        'residency_expiry'     => $user->residency_expiry,
        'passport_number'      => $user->passport_number,
        'passport_expiry'      => $user->passport_expiry,
        'residency_number'     => $user->residency_number,
        'iban'                 => $user->iban,
        'residency_job'        => $user->residency_job,
        'salary_type'          => $user->salary_type,
        'salary'               => $user->salary,
        'work_start_time'      => $user->work_start_time,
        'work_end_time'        => $user->work_end_time,
        'shift_count'          => $user->shift_count,
        'second_work_start_time' => $user->second_work_start_time,
        'second_work_end_time'   => $user->second_work_end_time,
        'macaddress'           => $user->macaddress,
        'is_biometric'         => $user->is_biometric,
        'main_salary'          => $user->main_salary,
        'additional_salary'    => $user->additional_salary,
        'attachment'           => $user->attachment,
        'work_hours_per_day'   => $user->work_hours_per_day,
        'working_days'         => $user->working_days,
        'vacation_balance'     => $user->vacation_balance,
        'bonus_per_hour'       => $user->bonus_per_hour,
        'is_role'              => $user->is_role,
        'start_date'           => $user->start_date,
        'end_date'             => $user->end_date,
        'job_id'               => $user->job_id,
        'department_id'        => $user->department_id,
        'manager_id'           => $user->manager_id,
        'company_id'           => $user->company_id,
        'branch_id'            => $user->branch_id,

        // بيانات الاستقالة
        'resignation_date'     => now(),
        'resignation_reason'   => $resignation->reason,

        // timestamps
        'created_at'           => now(),
        'updated_at'           => now(),
    ]);

    // حذف الموظف من جدول users
    $user->delete();
}

public function accept($type, $id)
{
    $model = $this->getModelInstance($type, $id);

    // Verify the request belongs to the current company before processing
    if (!$this->belongsToCurrentCompany($model)) {
        abort(403, 'Unauthorized access to this request.');
    }

    $model->status = 'accepted';
    $model->is_seen = 0; // Mark as unseen for notifications
    $model->save();

    // If it's a vacation request, save to vacations table
    if ($type === 'vacation') {
        $this->saveVacationToVacationsTable($model);
    }

    // If it's an extra time request, save to times table
    if ($type === 'extra_time') {
        $this->saveExtraTimeToTimesTable($model);
    }

    // If it's a late removal request, update attendance
    if ($type === 'late_removal') {
        $this->updateAttendanceForLateRemoval($model);
    }

    // If it's an early leave request, handle it
    if ($type === 'early_leave') {
        $this->handleEarlyLeaveRequest($model);
    }

    // Resignation → نقل الموظف إلى الأرشيف
    if ($type === 'resignation') {
        $this->moveUserToHistory($model);
    }

    return back()->with('success', __('h_requests.accept message'));
}


    public function reject($type, $id)
    {
        $model = $this->getModelInstance($type, $id);

        // Verify the request belongs to the current company before processing
        if (!$this->belongsToCurrentCompany($model)) {
            abort(403, 'Unauthorized access to this request.');
        }

        $model->status = 'rejected';
        $model->is_seen = 0; // Mark as unseen for notifications
        $model->save();

        return back()->with('success', __('h_requests.reject message'));
    }


    // the next one related to employee interface and the action afyer reject or accept

    /**
     * Show a processed request and mark it as seen
     */
   /**
 * Show a processed request and mark it as seen in employee interface
 */
public function showProcessedRequest($type, $id)
{
    $model = $this->getModelInstance($type, $id);

    // Verify the request belongs to the current company
    if (!$this->belongsToCurrentCompany($model)) {
        abort(403, 'Unauthorized access to this request.');
    }

    // Mark as seen
    $model->is_seen = 1;
    $model->save();

    // Redirect to appropriate route based on request type
    switch ($type) {
        case 'vacation':
            return redirect()->route('vacation.index');
        case 'extra_time':
            return redirect()->route('employee.extra.index');
        case 'resignation':
            return redirect()->route('employee.resignation.index');
        case 'late_removal':
            return redirect()->route('employee.late.index');
        case 'early_leave':
            return redirect()->route('employee.early_leave.index');
        default:
            // Fallback to the original view if type doesn't match
            return view('backend.requests.show', compact('model', 'type'));
    }
}


    /**
     * Mark all processed notifications as seen (AJAX endpoint)
     */
    // public function markAllProcessedAsSeen()
    // {
    //     // Get current company ID and branch ID from session
    //     $companyId = session('company_id');
    //     $branchId = session('branch_id');

    //     if (!$companyId) {
    //         return response()->json(['error' => 'No company session'], 400);
    //     }

    //     // Determine filtering logic based on branch_id and is_main
    //     $showAllCompanyRequests = false;
    //     $filterBranchId = null;

    //     if ($branchId) {
    //         $currentBranch = Branch::find($branchId);
    //         if ($currentBranch && $currentBranch->is_main == 1) {
    //             $showAllCompanyRequests = true;
    //         } else {
    //             $filterBranchId = $branchId;
    //         }
    //     } else {
    //         $showAllCompanyRequests = true;
    //     }

    //     // Create a closure for the user filtering logic
    //     $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
    //         if ($showAllCompanyRequests) {
    //             $query->where('company_id', $companyId);
    //         } else {
    //             $query->where('branch_id', $filterBranchId);
    //         }
    //     };

    //     // Mark all unseen processed requests as seen
    //     VacationRequest::whereIn('status', ['accepted', 'rejected'])
    //         ->where('is_seen', 0)
    //         ->whereHas('user', $userFilterClosure)
    //         ->update(['is_seen' => 1]);

    //     ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
    //         ->where('is_seen', 0)
    //         ->whereHas('user', $userFilterClosure)
    //         ->update(['is_seen' => 1]);

    //     Resignation::whereIn('status', ['accepted', 'rejected'])
    //         ->where('is_seen', 0)
    //         ->whereHas('user', $userFilterClosure)
    //         ->update(['is_seen' => 1]);

    //     LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
    //         ->where('is_seen', 0)
    //         ->whereHas('user', $userFilterClosure)
    //         ->update(['is_seen' => 1]);

    //     return response()->json(['success' => true]);
    // }


    private function belongsToCurrentCompany($model)
    {
        $companyId = session('company_id');

        // Load the user relationship if not already loaded
        if (!$model->relationLoaded('user')) {
            $model->load('user');
        }

        return $model->user && $model->user->company_id == $companyId;
    }

    private function getModelInstance($type, $id)
    {
        switch ($type) {
            case 'vacation':
                return VacationRequest::with('user')->findOrFail($id);
            case 'extra_time':
                return ExtraTimeRequest::with('user')->findOrFail($id);
            case 'resignation':
                return Resignation::with('user')->findOrFail($id);
            case 'late_removal':
                return LateRemovalRequest::with('user')->findOrFail($id);
            case 'early_leave':
                return EarlyLeaveRequest::with('user')->findOrFail($id);
            default:
                abort(404);
        }
    }


    // actions after the acceptance or rejecting
    private function saveVacationToVacationsTable(VacationRequest $vacationRequest)
    {
        // Check if vacation already exists to prevent duplicates
        $existingVacation = Vacation::where('employee_id', $vacationRequest->user_id)
            ->where('start_date', $vacationRequest->start_date)
            ->where('end_date', $vacationRequest->end_date)
            ->first();

        if (!$existingVacation) {
            // Calculate total days if not available in request
            $totalDays = $vacationRequest->total ??
                        $vacationRequest->total_days ??
                        $vacationRequest->days ??
                        $this->calculateVacationDays($vacationRequest->start_date, $vacationRequest->end_date);

            // Get company_id from user relationship if not in request
            $companyId = $vacationRequest->company_id ??
                        $vacationRequest->user->company_id ??
                        null;

            // Get branch_id from user relationship if not in request
            $branchId = $vacationRequest->branch_id ??
                       $vacationRequest->user->branch_id ??
                       null;

            Vacation::create([
                'employee_id' => $vacationRequest->user_id,
                'start_date' => $vacationRequest->start_date,
                'end_date' => $vacationRequest->end_date,
                'total' => $totalDays,
                'vacation_type' => $vacationRequest->vacation_type,
                'company_id' => $companyId,
                'branch_id' => $branchId,
            ]);
        }
    }

    private function calculateVacationDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Add 1 to include both start and end date
        return $start->diffInDays($end) + 1;
    }

    private function saveExtraTimeToTimesTable(ExtraTimeRequest $extraTimeRequest)
    {
        // Try different possible field names for employee ID
        $employeeId = $extraTimeRequest->user_id ??
                     $extraTimeRequest->employee_id ??
                     $extraTimeRequest->emp_id ??
                     null;

        // Try different possible field names for hours
        $hours = $extraTimeRequest->hours ??
                $extraTimeRequest->extra_hours ??
                $extraTimeRequest->bonus_hours ??
                $extraTimeRequest->overtime_hours ??
                null;

        // Get the date from extra time request
        $requestDate = $extraTimeRequest->date ??
                      $extraTimeRequest->request_date ??
                      $extraTimeRequest->work_date ??
                      $extraTimeRequest->created_at ??
                      now();

        \Log::info('ExtraTimeRequest data:', [
            'all_data' => $extraTimeRequest->toArray(),
            'employee_id' => $employeeId,
            'hours' => $hours,
            'request_date' => $requestDate,
        ]);

        if (!$employeeId) {
            \Log::error('Employee ID is null for ExtraTimeRequest ID: ' . $extraTimeRequest->id);
            return;
        }

        if (!$hours) {
            \Log::error('Hours is null for ExtraTimeRequest ID: ' . $extraTimeRequest->id);
            return;
        }

        // Check if time entry already exists to prevent duplicates
        $existingTime = Time::where('employee_id', $employeeId)
            ->where('hours', $hours)
            ->whereDate('created_at', Carbon::parse($requestDate)->toDateString())
            ->first();

        if (!$existingTime) {
            // Get company_id from user relationship if not in request
            $companyId = $extraTimeRequest->company_id ??
                        $extraTimeRequest->user->company_id ??
                        null;

            // Get branch_id from user relationship if not in request
            $branchId = $extraTimeRequest->branch_id ??
                       $extraTimeRequest->user->branch_id ??
                       null;

            $timeEntry = new Time([
                'employee_id' => $employeeId,
                'hours' => $hours,
                'company_id' => $companyId,
                'branch_id' => $branchId,
            ]);

            // Set the created_at to the date from the extra time request
            $timeEntry->created_at = Carbon::parse($requestDate);
            $timeEntry->updated_at = now();
            $timeEntry->save();

            \Log::info('Successfully saved time entry for employee: ' . $employeeId . ' with date: ' . $requestDate);
        } else {
            \Log::info('Time entry already exists, skipping duplicate.');
        }
    }

    private function updateAttendanceForLateRemoval(LateRemovalRequest $lateRemovalRequest)
    {
        // Get employee ID and date from the late removal request
        $employeeId = $lateRemovalRequest->employee_id;
        $attendanceDate = $lateRemovalRequest->day;

        \Log::info('LateRemovalRequest data:', [
            'request_id' => $lateRemovalRequest->id,
            'employee_id' => $employeeId,
            'attendance_date' => $attendanceDate,
            'attendance_id' => $lateRemovalRequest->attendance_id,
        ]);

        if (!$employeeId) {
            \Log::error('Employee ID is null for LateRemovalRequest ID: ' . $lateRemovalRequest->id);
            return;
        }

        if (!$attendanceDate) {
            \Log::error('Attendance date is null for LateRemovalRequest ID: ' . $lateRemovalRequest->id);
            return;
        }

        // Find the attendance record using the attendance_id from the request
        $attendance = null;

        if ($lateRemovalRequest->attendance_id) {
            $attendance = Attendance::find($lateRemovalRequest->attendance_id);
        }

        // If not found by ID, try to find by employee_id and date
        if (!$attendance) {
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('attendance_date', Carbon::parse($attendanceDate)->toDateString())
                ->first();
        }

        if ($attendance) {
            // Update attendance_type to 1 (present)
            $attendance->attendance_type = '1';
            $attendance->save();

            \Log::info('Successfully updated attendance for employee: ' . $employeeId . ' on date: ' . $attendanceDate);
        } else {
            \Log::error('No attendance record found for employee: ' . $employeeId . ' on date: ' . $attendanceDate);
        }
    }

    /**
     * Handle early leave request when accepted
     */
    private function handleEarlyLeaveRequest(EarlyLeaveRequest $earlyLeaveRequest)
    {
        // Get employee ID and date from the early leave request
        $employeeId = $earlyLeaveRequest->employee_id;
        $requestDate = $earlyLeaveRequest->request_date;
        $leaveTime = $earlyLeaveRequest->requested_leave_time;

        \Log::info('EarlyLeaveRequest data:', [
            'request_id' => $earlyLeaveRequest->id,
            'employee_id' => $employeeId,
            'request_date' => $requestDate,
            'leave_time' => $leaveTime,
            'urgent' => $earlyLeaveRequest->urgent_request,
        ]);

        if (!$employeeId) {
            \Log::error('Employee ID is null for EarlyLeaveRequest ID: ' . $earlyLeaveRequest->id);
            return;
        }

        if (!$requestDate) {
            \Log::error('Request date is null for EarlyLeaveRequest ID: ' . $earlyLeaveRequest->id);
            return;
        }

        // Find the attendance record for this date
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('attendance_date', Carbon::parse($requestDate)->toDateString())
            ->first();

        if ($attendance) {
            // You can update the checkout time or add a note
            // Depending on your business logic, you might want to:
            // 1. Update the checkout time to the requested leave time
            // 2. Add a flag indicating early leave was approved
            // 3. Calculate any deductions if needed

            // Example: Update checkout time if not already set or if later than requested time
            if (!$attendance->check_out || Carbon::parse($attendance->check_out)->greaterThan(Carbon::parse($leaveTime))) {
                $attendance->check_out = $leaveTime;
                $attendance->save();

                \Log::info('Successfully updated checkout time for employee: ' . $employeeId . ' on date: ' . $requestDate);
            }
        } else {
            \Log::warning('No attendance record found for employee: ' . $employeeId . ' on date: ' . $requestDate);
        }
    }
}
