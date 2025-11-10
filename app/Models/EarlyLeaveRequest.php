<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'request_date',
        'requested_leave_time',
        'reason',
        'status',
        'urgent_request',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
