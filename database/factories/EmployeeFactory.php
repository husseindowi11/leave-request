<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'phone'             => $this->faker->phoneNumber(),
            'department_id'     => Department::inRandomOrder()->first()->id,
            'job_position_id'   => JobPosition::inRandomOrder()->first()->id,
            'address'           => $this->faker->address(),
//            'image'             => $this->faker->optional()->imageUrl(300, 300, 'people'),
        ];
    }
}
