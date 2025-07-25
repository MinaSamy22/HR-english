<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';

    public static function getRecord($request)
    {
        $company_id = session('company_id');

        $query = self::select('branches.*')
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc');

        if (!empty($request->get('name'))) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        return $query->paginate(5);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
