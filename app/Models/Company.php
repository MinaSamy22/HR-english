<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'email',
        'phone_number',
        'commercial_registration',
        'tax_card',
    ];

    // Company.php
    public function attendanceSetting()
    {
        return $this->hasOne(AttendanceRule::class);
    }

    // Static method to retrieve records with filtering options
    static public function getRecord()
    {
        $query = self::select('companies.*');

        // Retrieve records ordered by id and paginated
        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // Updated method to handle logos from shared folder
public function getLogoUrlAttribute()
{
    if ($this->logo) {
        // Check if file exists in the shared folder
        $filePath = public_path('../../HR-Uploads/company_logos/' . $this->logo);
        if (file_exists($filePath)) {
            return route('view.logo', $this->logo);
        }
    }
    return asset('dist/img/default-logo.png'); // Default logo if none exists
}

public function getLogoPathAttribute()
{
    return public_path('../../HR-Uploads/company_logos/' . $this->logo);
}

// New method to check if logo is SVG
public function getLogoIsSvgAttribute()
{
    if ($this->logo) {
        $extension = pathinfo($this->logo, PATHINFO_EXTENSION);
        return strtolower($extension) === 'svg';
    }
    return false;
}

// New method to get logo MIME type
public function getLogoMimeTypeAttribute()
{
    if ($this->logo) {
        $extension = strtolower(pathinfo($this->logo, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'svg':
                return 'image/svg+xml';
            case 'png':
                return 'image/png';
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            default:
                return 'image/png';
        }
    }
    return 'image/png';
}

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function manager()
    {
        return $this->hasMany(Manager::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function job()
    {
        return $this->hasMany(Job::class);
    }

    public function deduction()
    {
        return $this->hasMany(Deduction::class);
    }

    public function vacation()
    {
        return $this->hasMany(Vacation::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function task()
    {
        return $this->hasMany(Task::class);
    }

    public function time()
    {
        return $this->hasMany(Time::class);
    }
}
