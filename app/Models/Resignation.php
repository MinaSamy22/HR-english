<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'resignation_date',
        'reason',
        'status'
    ];
        protected $attributes = [
        'status' => 'pending'
    ];

public function user()
{
    return $this->belongsTo(User::class, 'employee_id');
}
}
