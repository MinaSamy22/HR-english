<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Deduction extends Model
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


    static public function getRecord($request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select('deductions.*', 'users.name')
        ->join('users', 'users.id', '=', 'deductions.employee_id')
        ->orderBy('deductions.id', 'desc');


     // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('deductions.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('deductions.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('deductions.company_id', $company_id);
    }


    // 🔎 Filter by employee name
    if (!empty(Request::get('name'))) {
        $query->where('users.name', 'like', '%' . Request::get('name') . '%');
    }

    return $query->paginate(10); // Use ->get() if you don't want pagination
}



        public function company()
        {
            return $this->belongsTo(Company::class);
        }
         }
