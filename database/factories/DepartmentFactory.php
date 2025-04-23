<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    public static array $departments = [
        'Human Resources',
        'Finance',
        'Marketing',
        'Engineering',
        'Sales',
        'Customer Support',
        'IT',
        'Operations',
        'Legal',
        'Research & Development',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(self::$departments);
        return [
            'name' => $name,
        ];
    }
}
