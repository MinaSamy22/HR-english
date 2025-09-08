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

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('managers.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('managers.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('managers.company_id', $company_id);
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
