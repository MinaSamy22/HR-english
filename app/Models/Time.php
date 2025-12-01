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
        $company_id = session('company_id');
        $branch_id = session('branch_id');

        // 🆕 Add branch join to retrieve branch_name
        $return = self::select('times.*', 'users.name', 'branches.name as branch_name')
            ->join('users', 'users.id', '=', 'times.employee_id')
            ->leftJoin('branches', 'times.branch_id', '=', 'branches.id')  // Add this line
            ->where('users.company_id', $company_id)
            ->orderBy('times.id', 'desc');

        // 🔍 Filter by branch_id if available, otherwise fallback to company_id or main branch
        if (!empty($branch_id)) {
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                $return->where('times.company_id', $company_id);
            } else {
                $return->where('times.branch_id', $branch_id);
            }
        } else {
            $return->where('times.company_id', $company_id);
        }

        // Apply search filters
        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }

        // Add branch filter
        if (!empty(Request::get('filter_branch_id'))) {
            $return = $return->where('times.branch_id', Request::get('filter_branch_id'));
        }
        // 🆕 Filter: From Date
        if (!empty(Request::get('from_date'))) {
            $return = $return->whereDate('times.created_at', '>=', Request::get('from_date'));
        }

        // 🆕 Filter: To Date
        if (!empty(Request::get('to_date'))) {
            $return = $return->whereDate('times.created_at', '<=', Request::get('to_date'));
        }

        return $return->paginate(5);
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
