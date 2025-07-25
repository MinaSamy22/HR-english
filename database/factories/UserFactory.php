<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Job;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'           => $this->faker->name,
            'email'          => $this->faker->unique()->safeEmail,
            'phone_number'   => $this->faker->phoneNumber,
            'hire_date'      => $this->faker->date('Y-m-d'),
            'job_id'         => $this->faker->randomElement(Job::pluck('id')->toArray()), // Random valid job ID
            'salary'         => $this->faker->numberBetween(5000, 20000), // Random salary
            'manager_id'     => $this->faker->randomElement(Manager::pluck('id')->toArray()), // Random valid manager ID
            'department_id'  => $this->faker->randomElement(Department::pluck('id')->toArray()), // Random valid department ID
        ];
    }

}
