<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Administration extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'manager_id',
    ];

     protected $table = 'administrations';  //this tell to laravel that i need to use this table for this model


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
public static function getRecord($request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $query = self::select('administrations.*', 'managers.name as manager_name')
                 ->leftJoin('managers', 'administrations.manager_id', '=', 'managers.id')
                 ->orderBy('administrations.id', 'desc');

        // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('administrations.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('administrations.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('administrations.company_id', $company_id);
    }

    // فلترة بالاسم إن وُجد
    if ($request->filled('name')) {
        $query->where('administrations.name', 'like', '%' . $request->input('name') . '%');
    }

    return $query->paginate(5);
}





public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function company()
{
    return $this->belongsTo(Company::class);
}

public function get_manager_single(){
    return $this->belongsTo(Manager::class, "manager_id");
}

public function users()
{
    return $this->hasMany(User::class);
}


}
