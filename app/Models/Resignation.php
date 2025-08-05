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
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
