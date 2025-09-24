<?php
namespace App\Http\Controllers;
use App\Models\AttendanceRule;
use App\Models\EmployeeWorkHours;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceRulesController extends Controller
{
    public function index(Request $request)
    {
        $data['header_title'] = "Employee Attendance";
        $company_id = session('company_id');
        $data['setting'] = AttendanceRule::where('company_id', $company_id)->first();

        // Add employees data
        $data['employees'] = User::where('company_id', $company_id)
            ->orderBy('name')
            ->get();

        return view('backend.attendance.attendance-rule', $data);
    }

    public function saveRules(Request $request)
    {
        $request->validate([
            'late_deduction_percentage'          => 'required|integer|min:0|max:100',
            'half_day_deduction_percentage'      => 'required|integer|min:0|max:100',
            'work_hours_per_day'                 => 'required|numeric|min:1|max:24',
            'working_days'                       => 'required|array',
            'holiday_dates'                      => 'array',
            'holiday_titles'                     => 'array',
            'vacation_balance'                   => 'required|integer|min:0',
            'bonus_per_hour'                     => 'required|numeric|min:0',
        ]);

        $company_id = session('company_id');
        $holidays = [];

        // Process holidays if they exist
        if ($request->has('holiday_dates') && $request->has('holiday_titles')) {
            foreach ($request->holiday_dates as $index => $date) {
                // Only add if both date and title are provided
                if (!empty($date) && !empty($request->holiday_titles[$index])) {
                    $holidays[] = [
                        'date' => $date,
                        'title' => $request->holiday_titles[$index]
                    ];
                }
            }
        }

        AttendanceRule::updateOrCreate(
            ['company_id' => $company_id],
            [
                'late_deduction_percentage'      => $request->late_deduction_percentage,
                'half_day_deduction_percentage'  => $request->half_day_deduction_percentage,
                'work_hours_per_day'             => $request->work_hours_per_day,
                'working_days'                   => json_encode($request->working_days),
                'official_holidays'              => json_encode($holidays),
                'vacation_balance'               => $request->vacation_balance,
                'bonus_per_hour'                 => $request->bonus_per_hour,
                'timezone'                       => $request->timezone,
            ]
        );

        return redirect()->back()->with('success', 'Attendance rules saved successfully.');
    }

    // New method to update working days for multiple employees
    public function updateEmployeeWorkingDays(Request $request)
    {
        try {
            $companyId = auth()->user()->company_id;

            // Check if it's a bulk update
            if ($request->has('bulk_update') && $request->bulk_update) {
                $request->validate([
                    'employee_ids' => 'required|array',
                    'employee_ids.*' => 'required|exists:users,id',
                    'working_days' => 'required|array',
                ]);

                $updatedCount = 0;
                foreach ($request->employee_ids as $employeeId) {
                    // Verify employee belongs to the same company and update directly in users table
                    $employee = User::where('id', $employeeId)
                        ->where('company_id', $companyId)
                        ->first();

                    if ($employee) {
                        $employee->working_days = json_encode($request->working_days);
                        $employee->save();
                        $updatedCount++;
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.working_days_updated_for_employees', ['count' => $updatedCount])
                ]);
            } else {
                // Single employee update
                $request->validate([
                    'employee_id' => 'required|exists:users,id',
                    'working_days' => 'required|array',
                ]);

                // Verify employee belongs to the same company
                $employee = User::where('id', $request->employee_id)
                    ->where('company_id', $companyId)
                    ->first();

                if (!$employee) {
                    return response()->json([
                        'success' => false,
                        'message' => __('dashboard.employee_not_found_or_access_denied')
                    ], 403);
                }

                // Update working days directly in users table
                $employee->working_days = json_encode($request->working_days);
                $employee->save();

                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.working_days_updated_successfully')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            'message' => __('dashboard.failed_to_update_working_days'),
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function updateLateDeduction(Request $request)
    {
        $request->validate([
            'late_deduction_percentage' => 'required|integer|min:0|max:100',
        ]);

        $company_id = session('company_id');

        // Use updateOrCreate to ensure record exists
        $rule = AttendanceRule::updateOrCreate(
            ['company_id' => $company_id],
            ['late_deduction_percentage' => $request->late_deduction_percentage]
        );

        return response()->json(['message' => 'Late deduction percentage updated successfully']);
    }

    public function updateHalfDayDeduction(Request $request)
    {
        $request->validate([
            'half_day_deduction_percentage' => 'required|integer|min:0|max:100',
        ]);

        $company_id = session('company_id');

        // Use updateOrCreate to ensure record exists
        $rule = AttendanceRule::updateOrCreate(
            ['company_id' => $company_id],
            ['half_day_deduction_percentage' => $request->half_day_deduction_percentage]
        );

        return response()->json(['message' => 'Half day deduction percentage updated successfully']);
    }

    // Updated method for updating individual employee work hours - now saves to users table
    public function updateEmployeeWorkHours(Request $request)
    {
        try {
            $companyId = auth()->user()->company_id;

            // Check if it's a bulk update
            if ($request->has('bulk_update') && $request->bulk_update) {
                $request->validate([
                    'employee_ids' => 'required|array',
                    'employee_ids.*' => 'required|exists:users,id',
                    'work_hours_per_day' => 'required|numeric|min:1|max:24',
                ]);

                $updatedCount = 0;
                foreach ($request->employee_ids as $employeeId) {
                    // Verify employee belongs to the same company and update directly in users table
                    $employee = User::where('id', $employeeId)
                        ->where('company_id', $companyId)
                        ->first();

                    if ($employee) {
                        $employee->work_hours_per_day = $request->work_hours_per_day;
                        $employee->save();
                        $updatedCount++;
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => __('rules.work_hours_updated_for', ['count' => $updatedCount])
                ]);
            } else {
                // Single employee update
                $request->validate([
                    'employee_id' => 'required|exists:users,id',
                    'work_hours_per_day' => 'required|numeric|min:1|max:24',
                ]);

                // Verify employee belongs to the same company
                $employee = User::where('id', $request->employee_id)
                    ->where('company_id', $companyId)
                    ->first();

                if (!$employee) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Employee not found or access denied.'
                    ], 403);
                }

                // Update work hours directly in users table
                $employee->work_hours_per_day = $request->work_hours_per_day;
                $employee->save();

                return response()->json([
                    'success' => true,
                    'message' => __('rules.success_message')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('rules.error_message'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateVacationBalance(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'vacation_balance' => 'required|numeric|min:0',
            ]);

            // Find or create the record
            $setting = AttendanceRule::firstOrNew(['company_id' => auth()->user()->company_id]);

            // Update the vacation balance
            $setting->vacation_balance = $request->vacation_balance;
            $setting->save();

            return response()->json([
                'success' => true,
                'message' => 'Vacation balance saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vacation balance.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // New method to add a holiday
    public function addHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
        ]);

        $company_id = session('company_id');
        $rule = AttendanceRule::where('company_id', $company_id)->first();

        if (!$rule) {
            return response()->json(['error' => 'Attendance rule not found'], 404);
        }

        // Get current holidays
        $holidays = json_decode($rule->official_holidays, true) ?? [];

        // Add new holiday
        $holidays[] = [
            'date' => $request->date,
            'title' => $request->title
        ];

        // Update holidays
        $rule->official_holidays = json_encode($holidays);
        $rule->save();

        return response()->json([
            'message' => 'Holiday added successfully',
            'holiday' => [
                'date' => $request->date,
                'title' => $request->title
            ]
        ]);
    }

    // New method to delete a holiday
    public function deleteHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string',
        ]);

        $company_id = session('company_id');
        $rule = AttendanceRule::where('company_id', $company_id)->first();

        if (!$rule) {
            return response()->json(['error' => 'Attendance rule not found'], 404);
        }

        // Get current holidays
        $holidays = json_decode($rule->official_holidays, true) ?? [];

        // Remove the matching holiday
        $updatedHolidays = array_filter($holidays, function($holiday) use ($request) {
            return $holiday['date'] != $request->date || $holiday['title'] != $request->title;
        });

        // Reset array keys
        $updatedHolidays = array_values($updatedHolidays);

        // Update holidays
        $rule->official_holidays = json_encode($updatedHolidays);
        $rule->save();

        return response()->json([
            'message' => 'Holiday deleted successfully'
        ]);
    }

    public function updateHolidays(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'official_holidays' => 'required|array',
                'official_holidays.*.date' => 'required|date',
                'official_holidays.*.title' => 'required|string|max:255',
            ]);

            // Find or create the settings record
            $setting = AttendanceRule::firstOrNew(['company_id' => auth()->user()->company_id]);

            // Update the official_holidays field
            $setting->official_holidays = json_encode($validatedData['official_holidays']);
            $setting->save();

            return response()->json([
                'success' => true,
                'message' => 'Holidays updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holidays',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBonusPerHour(Request $request)
    {
        try {
            $request->validate([
                'bonus_per_hour' => 'required|numeric|min:0',
            ]);

            $setting = AttendanceRule::firstOrNew(['company_id' => auth()->user()->company_id]);

            $setting->bonus_per_hour = $request->bonus_per_hour;
            $setting->save();

            return response()->json([
                'success' => true,
                'message' => 'Bonus per hour saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to saved bonus per hour.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function policy(Request $request)
    {
        $data['header_title'] = "Company Attendance Policy";
        $company_id = session('company_id');
        $data['setting'] = AttendanceRule::where('company_id', $company_id)->first();

        // If no settings found, create default empty object to prevent errors
        if (!$data['setting']) {
            $data['setting'] = (object) [
                'late_deduction_percentage' => 0,
                'half_day_deduction_percentage' => 0,
                'work_hours_per_day' => 8,
                'bonus_per_hour' => 0,
                'working_days' => json_encode(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'vacation_balance' => 0,
                'official_holidays' => json_encode([])
            ];
        }

        return view('EmployeeInterface.policy.index', $data);
    }
}
