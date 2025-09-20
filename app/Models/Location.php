<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'polygon','company_id','branch_id'];

    protected $casts = [
        'polygon' => 'array', // auto decode JSON
    ];

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_location');
    }
}
