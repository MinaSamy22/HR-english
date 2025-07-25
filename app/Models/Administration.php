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

    // شرط الفلترة حسب الفرع أو الشركة
    if ($branch_id) {
        $query->where('administrations.branch_id', $branch_id);
    } else {
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
