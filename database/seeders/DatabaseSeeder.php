<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\JobPosition;
use App\Models\LeaveType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create departments
        Department::factory()
            ->count(count(\Database\Factories\DepartmentFactory::$departments))
            ->create();
        // Create 10 job positions
        JobPosition::factory()->count(10)->create();
        // Create leave types
        LeaveType::factory()
            ->count(count(\Database\Factories\LeaveTypeFactory::$types))
            ->create();
        // Create users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
        User::factory()->count(30)->create();
    }
}
