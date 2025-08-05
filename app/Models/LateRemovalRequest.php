<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateRemovalRequest extends Model
{
    protected $fillable = [
        'attendance_id',
        'employee_id',
        'reason',
        'status',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
