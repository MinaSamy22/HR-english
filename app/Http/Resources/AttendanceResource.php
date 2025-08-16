<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attendanceTypes = [
            1 => __('dashboard.present'),
            2 => __('dashboard.late'),
            3 => __('dashboard.absent'),
            4 => __('dashboard.half_day'),
        ];

        return [
            'id'              => $this->id,
            'date'            => Carbon::parse($this->attendance_date)->toDateString(),
            'attendance_type' => $attendanceTypes[$this->attendance_type] ?? '',
            'check_in'        => $this->check_in ? Carbon::parse($this->check_in)->format('H:i:s') : null,
            'check_out'       => $this->check_out ? Carbon::parse($this->check_out)->format('H:i:s') : null,
        ];
    }
}
