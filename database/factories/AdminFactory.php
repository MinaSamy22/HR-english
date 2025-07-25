<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition()
    {
        return [
            'name' => 'Super Admin',
            'email' => 'info@prosofteg.com',
            'password' => Hash::make('AAAaaa@123'), // make sure to hash the password!
        ];
    }

}

