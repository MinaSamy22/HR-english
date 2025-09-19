<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class Department extends Authenticatable
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
public static function getRecord($request)
{
    // Retrieve company_id from session or request
    $branch_id = session('branch_id');
    $company_id = session('company_id'); // Or use $request->company_id if passed in the request

    // Initialize the query with a join to include manager data and branch info
    $query = self::select(
                'departments.*',
                'managers.name as manager_name',
                'administrations.name as administration_name',
                'branches.name as branch_name',
                'branches.is_main'
            )
            ->leftJoin('managers', 'departments.manager_id', '=', 'managers.id')
            ->leftJoin('administrations', 'departments.administration_id', '=', 'administrations.id')
            ->leftJoin('branches', 'branches.id', '=', 'departments.branch_id')
            ->orderBy('departments.id', 'desc');

    // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
    if (!empty($branch_id)) {
        // Get the current branch info to check if it's main
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if ($currentBranch && $currentBranch->is_main == 1) {
            // If current branch is main branch, show all in the company
            $query->where('departments.company_id', $company_id);
        } else {
            // If current branch is not main, show only of this specific branch
            $query->where('departments.branch_id', $branch_id);
        }
    } else {
        // If no branch_id in session, show all in the company
        $query->where('departments.company_id', $company_id);
    }

    // Apply search filter for department name if provided
    if ($request->filled('department_name')) {
        $query->where('departments.department_name', 'like', '%' . $request->input('department_name') . '%');
    }

    // 🆕 NEW: Branch filter by ID (from dropdown)
    if (!empty($request->input('filter_branch_id'))) {
        $query->where('departments.branch_id', '=', $request->input('filter_branch_id'));
    }

    // Handle per_page parameter
    $perPage = $request->input('per_page', 5); // Default to 5

    if ($perPage === 'all') {
        return $query->get();
    } else {
        $paginatedResults = $query->paginate((int)$perPage);
        // 🔧 FIX: Append all request parameters to pagination links
        $paginatedResults->appends($request->all());
        return $paginatedResults;
    }
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
