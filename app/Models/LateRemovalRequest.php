<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateRemovalRequest extends Model
{
    use HasFactory;

    protected $table = 'late_removal_requests';

    protected $fillable = [
        'attendance_id',
        'employee_id',
        'reason',
        'status'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    // Relationship with attendance
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    // Relationship with employee (if you have Employee model)
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
