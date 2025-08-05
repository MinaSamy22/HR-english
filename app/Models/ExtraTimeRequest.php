<?php
// app/Models/ExtraTimeRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraTimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'hours',
        'reason',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
