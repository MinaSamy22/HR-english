<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Enums\VacationType;
use App\Models\Resignation;
use App\Models\VacationRequest;
use App\Http\Resources\AttendanceResource;
use Illuminate\Support\Facades\Validator;


class EmployeeService
{
    protected $employee;

    public function __construct(User $employee)
    {
        $this->employee = $employee;
    }

    public function getUser()
    {
        $totalUsed = $this->getVacations()->sum('total');
        $vacationLimit = $this->employee->vacation_balance;
        $this->employee->total_used = $totalUsed;
        $this->employee->remaing = $vacationLimit - $totalUsed;
        return $this->employee;
    }

    public function getVacations($from = null, $to = null)
    {
        if (!$from || !$to) {
            $year = now()->year;
            $from = $from ?? "$year-01-01";
            $to   = $to ?? "$year-12-31";
        }

        $vacations = $this->employee->vacations()
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereDate('start_date', '<=', $to)
                    ->whereDate('end_date', '>=', $from);
            })
            ->get();

        return $vacations;
    }

    public function getAttendances()
    {
        $now = now();

        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $now->copy()->endOfMonth()->toDateString();

        $attendances = $this->employee->attendances()
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get();

        return $this->sendResponse(__('dashboard.success'), [
            'attend_count' => $attendances->where('attendance_type', 1)->count(),
            'late_count' => $attendances->where('attendance_type', 2)->count(),
            'absent_count' => $attendances->where('attendance_type', 3)->count(),
            'half_day_count' => $attendances->where('attendance_type', 4)->count(),
            'attendances' => AttendanceResource::collection($attendances),
        ], 1);
    }


    public function getSalaries($year = null, $month = null)
    {
        $query = $this->employee->payrolls();

        if ($year && $month) {
            // Build date range for the given year/month
            $date = Carbon::createFromDate($year, $month, 1);
            $from = $date->copy()->startOfMonth()->toDateString();
            $to   = $date->copy()->endOfMonth()->toDateString();

            $query->where(function ($query) use ($from, $to) {
                $query->whereBetween('start_date', [$from, $to])
                    ->orWhereBetween('end_date', [$from, $to])
                    ->orWhere(function ($query) use ($from, $to) {
                        $query->where('start_date', '<=', $from)
                            ->where('end_date', '>=', $to);
                    });
            });
        }

        return $query->get();
    }




    public function getVacationRequests($from = null, $to = null)
    {
        if (!$from || !$to) {
            $year = now()->year;
            $from = $from ?? "$year-01-01";
            $to   = $to ?? "$year-12-31";
        }

        $requests = $this->employee->vacationRequests()
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereDate('start_date', '<=', $to)
                    ->whereDate('end_date', '>=', $from);
            })
            ->get();

        return $requests;
    }


    public function vacationRequest(array $data)
    {
        return $this->employee->vacationRequests()->create($data);
    }

    public function deleteVacationRequest($id)
    {
        if ($vacationRequest = VacationRequest::where('status', VacationType::PENDING->value)->find($id)) {
            $vacationRequest->delete();
            return true;
        }
        return false;
    }

    public function getResignationRequests($from = null, $to = null)
    {
        return $this->employee->resignations;
    }

    public function resignationRequest($data)
    {
        return $this->employee->resignations()->create($data);
    }

    public function deleteResignationRequest($id)
    {
        if ($resignationRequest = Resignation::where('status', VacationType::PENDING->value)->find($id)) {
            $resignationRequest->delete();
            return true;
        }
        return false;
    }
    public function getNews()
    {
        return $this->employee->company->news()->whereDate('news_date', '>=', now()->subDays(30))
            ->orderBy('news_date', 'desc')->get();
    }

    public function checkIn()
    {
        $settings = $this->employee->company->attendanceSetting;
        $now = now($settings->timezone ?? config('app.timezone'));
        $employee = $this->employee;

        $validator = Validator::make(request()->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(__('dashboard.invalid_location'), $validator->errors(), 0);
        }

        $lat = request()->lat;
        $lng = request()->lng;

        $alreadyCheckedIn = $this->employee->attendances()
            ->where('attendance_date', $now->format('Y-m-d'))
            ->exists();

        if ($alreadyCheckedIn) {
            return $this->sendResponse(__('dashboard.already_checked_in'), [], 0);
        }

        $attendance_type = 1;

        if ($employee->is_biometric == 0) {

            $locations = $employee->locations;
            $allowed = false;
            foreach ($locations as $location) {
                if ($this->pointInPolygon($lat, $lng, $location->polygon)) {
                    $allowed = true;
                    break;
                }
            }

            if (! $allowed) {
                return $this->sendResponse(__('dashboard.not_in_checkin_area'), [], 0);
            }

            if (! $employee->work_start_time) {
                return $this->sendResponse(__('dashboard.invalid_start_time'), [], 0);
            }

            $workStart = Carbon::createFromFormat('H:i:s', $employee->work_start_time);
            $attendance_type = $now->lessThanOrEqualTo($workStart) ? 1 : 2; // 1 = On time, 2 = Late

        }

        $attendance = $this->employee->attendances()->create([
            'attendance_date' => $now->toDateString(),
            'check_in'        => $now->toTimeString(),
            'attendance_type' => $attendance_type,
        ]);

        return $this->sendResponse(__('dashboard.checked_in'), AttendanceResource::make($attendance), 1);
    }

    public function checkOut()
    {
        $settings = $this->employee->company->attendanceSetting;
        $now = now($settings->timezone ?? config('app.timezone'));
        $employee = $this->employee;

        $validator = Validator::make(request()->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(__('dashboard.invalid_location'), $validator->errors(), 0);
        }

        $lat = request()->lat;
        $lng = request()->lng;

        if ($employee->is_biometric == 0) {

            // Check if employee is inside any assigned location polygon
            $allowed = false;
            foreach ($employee->locations as $location) {
                if ($this->pointInPolygon($lat, $lng, $location->polygon)) {
                    $allowed = true;
                    break;
                }
            }

            if (! $allowed) {
                return $this->sendResponse(__('dashboard.not_in_checkout_area'), [], 0);
            }
        }

        if (! $employee->work_end_time) {
            return $this->sendResponse(__('dashboard.invalid_end_time'), [], 0);
        }

        // Find today's attendance record
        $attendance = $employee->attendances()
            ->where('attendance_date', $now->format('Y-m-d'))
            ->first();

        if (!$attendance) {
            return $this->sendResponse(__('dashboard.not_checked_in_today'), [], 0);
        }

        if ($attendance->check_out) {
            return $this->sendResponse(__('dashboard.already_checked_out'), [], 0);
        }

        // Save checkout with location
        $attendance->update([
            'check_out'  => $now->toTimeString(),
            // 'latitude_out'  => $lat,
            // 'longitude_out' => $lng,
        ]);

        return $this->sendResponse(__('dashboard.checked_out'), AttendanceResource::make($attendance), 1);
    }



    public function sendResponse($message, $data, $status = 1)
    {
        return [
            'msg' => $message,
            'data' => $data,
            'status' => $status,
        ];
    }

    private function pointInPolygon($lat, $lng, $polygon)
    {
        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            $xi = $polygon[$i]['lng']; // X = lng
            $yi = $polygon[$i]['lat']; // Y = lat
            $xj = $polygon[$j]['lng'];
            $yj = $polygon[$j]['lat'];

            $intersect = (($yi > $lat) != ($yj > $lat)) &&
                ($lng < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
            $j = $i;
        }

        return $inside;
    }
}
