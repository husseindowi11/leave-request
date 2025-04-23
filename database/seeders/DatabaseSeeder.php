<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobPosition;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
        // Create 10 departments
        Department::factory()->count(10)->create();
        // Create 10 job positions
        JobPosition::factory()->count(10)->create();
        // Create 50 employees
        Employee::factory()->count(50)->create();
    }
}
