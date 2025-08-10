<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Time extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'hours',
        'company_id',
        'branch_id',
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


    static public function getRecord($request)
    {
        // Get the company_id from the session or request
        $company_id = session('company_id');  // You can adjust this to get from request if needed.
        $branch_id = session('branch_id');

        $return = self::select('times.*', 'users.name')
            ->join('users', 'users.id', '=', 'times.employee_id')
            ->where('users.company_id', $company_id)  // Filter by company_id
            ->orderBy('times.id', 'desc');  // Ensure ordering by times ID

                        // 🔍 Filter by branch_id or fallback to company_id
    if (!empty($branch_id)) {
        $return->where('times.branch_id', $branch_id);
    } else {
        $return->where('times.company_id', $company_id);
    }

        // logic of the search box
        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');  // Search by name
        }

        // End logic of search

        $return = $return->paginate(5);
        return $return;
    }




        public function user()
        {
            return $this->belongsTo(User::class, 'employee_id');
        }

        public function company()
        {
            return $this->belongsTo(Company::class);
        }



}
