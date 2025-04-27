<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\JobPosition;
use App\Models\LeaveType;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

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
        // Create Manager (Super Admin)
        $manager = User::factory()->create([
            'name' => 'Manager Demo',
            'email' => 'manager@demo.com',
            'password' => bcrypt('password'),
        ]);
        // Create Employee
        $employee = User::factory()->create([
            'name' => 'Employee Demo',
            'email' => 'employee@demo.com',
            'password' => bcrypt('password'),
        ]);
        // Create 30 random users
        User::factory()->count(30)->create();
        // Create 40 random leave requests
        \App\Models\LeaveRequest::factory()->count(40)->create();
        // Create permissions
        Artisan::call('shield:install', [
            'panel' => 'admin',
            '--no-interaction' => true,
        ]);
        // Generate all Filament permissions/policies for the "admin" panel
        Artisan::call('shield:generate', [
            '--panel' => 'admin',
            '--all'   => true,
        ]);
        // Create roles and permissions
        $this->create_employee_role();
        $this->attach_role($employee,'employee');
        $this->attach_role($manager, 'super_admin');

    }

    public function create_employee_role()
    {
        $role = \Spatie\Permission\Models\Role::create([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);
        $permissionNames = [
            'create_department',
            'view_employee',
            'force_delete_employee',
            'restore_any_job::position',
            'replicate_job::position',
            'reorder_job::position',
            'delete_job::position',
            'delete_any_job::position',
            'force_delete_job::position',
            'force_delete_any_job::position',
            'view_leave::request',
            'view_any_leave::request',
            'create_leave::request',
            'replicate_leave::request',
            'restore_leave::type',
            'create_role',
        ];
        $permissions = Permission::whereIn('name', $permissionNames)->get();
        $role->syncPermissions($permissions);
    }

    public function attach_role(User $user, string $name)
    {
        $user->assignRole($name);
    }
}
