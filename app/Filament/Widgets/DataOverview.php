<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\JobPosition;
use App\Models\LeaveType;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class DataOverview extends BaseWidget
{
    use HasWidgetShield;
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $departments = Department::count();
        $users = User::count();
        $jobs = JobPosition::count();
        $roles = Role::count();
        $leave_types = LeaveType::count();
        return [
            Stat::make('Departments', $departments)
                ->color('primary')
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Users', $users)
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('Job Positions', $jobs)
                ->color('warning')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Roles', $roles)
                ->color('danger')
                ->icon('heroicon-o-shield-check'),
            Stat::make('Leave Types', $leave_types)
                ->color('info')
                ->icon('heroicon-o-document-text'),
        ];
    }

    protected function getHeading(): ?string
    {
        return 'Data Overview';
    }
}
