<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRule extends Model
{
    use HasFactory;
    protected $fillable = [
    'company_id',
    'late_deduction_percentage',
    'half_day_deduction_percentage',
    'late_threshold_minutes',
        'half_day_threshold_minutes',
    'work_hours_per_day',
    'working_days',
    'official_holidays',
    'vacation_balance',
    'bonus_per_hour',
    'timezone'
];
    protected $casts = [
        'working_days' => 'array',
        'official_holidays' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
