<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeaveRequestOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $pending_leave_requests = LeaveRequest::where('status', 'pending')->count();
        $approved_leave_requests = LeaveRequest::where('status', 'approved')->count();
        $rejected_leave_requests = LeaveRequest::where('status', 'rejected')->count();
        return [
            Stat::make('Pending Leave Requests', $pending_leave_requests)
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Approved Leave Requests', $approved_leave_requests)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Rejected Leave Requests', $rejected_leave_requests)
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }

    protected function getHeading(): ?string
    {
        return 'Leave Requests';
    }

}
