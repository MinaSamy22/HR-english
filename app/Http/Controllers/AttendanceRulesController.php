<?php
namespace App\Http\Controllers;
use App\Models\AttendanceRule;
use Illuminate\Http\Request;

class AttendanceRulesController extends Controller
{
    public function index(Request $request)
    {
        $data['header_title'] = "Employee Attendance";
        $company_id = session('company_id');
        $data['setting'] = AttendanceRule::where('company_id', $company_id)->first();
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

            ]
        );

        return redirect()->back()->with('success', 'Attendance rules saved successfully.');
    }








    public function updateWorkingDays(Request $request)
    {
        $request->validate([
            'working_days' => 'required|array',
            'working_days.*' => 'string'
        ]);

        $user = auth()->user(); // or however you're identifying the current company/user
        $setting = AttendanceRule::where('company_id', $user->company_id)->first();

        $setting->working_days = json_encode($request->working_days);
        $setting->save();

        return response()->json(['message' => 'Working days updated successfully']);
    }




    public function updateLateDeduction(Request $request)
{
    $request->validate([
        'late_deduction_percentage' => 'required|integer|min:0|max:100',
    ]);

    $company_id = session('company_id');
    $rule = AttendanceRule::where('company_id', $company_id)->first();

    if (!$rule) {
        return response()->json(['error' => 'Attendance rule not found'], 404);
    }

    $rule->late_deduction_percentage = $request->late_deduction_percentage;
    $rule->save();

    return response()->json(['message' => 'Late deduction percentage updated successfully']);
}

public function updateHalfDayDeduction(Request $request)
{
    $request->validate([
        'half_day_deduction_percentage' => 'required|integer|min:0|max:100',
    ]);

    $company_id = session('company_id');
    $rule = AttendanceRule::where('company_id', $company_id)->first();

    if (!$rule) {
        return response()->json(['error' => 'Attendance rule not found'], 404);
    }

    $rule->half_day_deduction_percentage = $request->half_day_deduction_percentage;
    $rule->save();

    return response()->json(['message' => 'Half day deduction percentage updated successfully']);
}

public function updateWorkHoursPerDay(Request $request)
{
    try {
        $request->validate([
            'work_hours_per_day' => 'required|numeric|min:1|max:24',
        ]);

        $setting = AttendanceRule::firstOrNew(['company_id' => auth()->user()->company_id]);

        $setting->work_hours_per_day = $request->work_hours_per_day;
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Work hours per day saved successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to save work hours.',
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

// In your AttendanceRulesController.php or similar controller

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


}
