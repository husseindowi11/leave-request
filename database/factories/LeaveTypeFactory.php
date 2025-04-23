<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveType>
 */
class LeaveTypeFactory extends Factory
{

    /**
     * Predefined set of leave type names.
     */
    public static array $types = [
        'Sick Leave',
        'Vacation',
        'Personal Leave',
        'Maternity Leave',
        'Paternity Leave',
        'Bereavement Leave',
        'Jury Duty',
        'Unpaid Leave',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(self::$types);
        return [
            'name' => $name,
        ];
    }
}
