<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition()
    {
        return [
            'job_title'     => $this->faker->jobTitle,
            'min_salary'    => $this->faker->numberBetween(3000, 6000),
            'max_salary'    => $this->faker->numberBetween(6000, 12000),
            'department_id' => $this->faker->randomElement(Department::pluck('id')->toArray()), // Random valid department ID
        ];
    }

}

