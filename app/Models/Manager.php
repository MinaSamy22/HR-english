<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Manager extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    static public function getRecord() {
        // Retrieve the company_id from the session
    $company_id = session('company_id');
    $branch_id = session('branch_id');

        // Start the query for retrieving managers
        $query = self::select('managers.*'); // Change 'users.*' to 'managers.*' since you're working with the Manager model

        // Apply company_id filter if available in the session
    // 🔍 Filter by branch_id if available, otherwise fallback to company_id
    if ($branch_id) {
        $query->where('branch_id', $branch_id);
    } elseif ($company_id) {
        $query->where('company_id', $company_id);
    }

        // Apply search filters if any
        if (!empty(Request::get('id'))) {
            $query = $query->where('id', '=', Request::get('id'));
        }

        if (!empty(Request::get('name'))) {
            $query = $query->where('name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('email'))) {
            $query = $query->where('email', 'like', '%' . Request::get('email') . '%');
        }

        // Retrieve records, order by id, and paginate
        return $query->orderBy('id', 'desc')->paginate(5);
    }






public function attendances()
{
    return $this->hasMany(Attendance::class);
}


public function get_department_single(){
    return $this->belongsTo(Department::class, "department_id");
}




}
