<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'total',
        'reason',
        'status',
        'vacation_type',
        'company_id',
        'approved_by',
        'approved_at'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }


}
