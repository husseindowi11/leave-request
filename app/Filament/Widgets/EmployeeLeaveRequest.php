<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeLeaveRequest extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;
    protected function getStats(): array
    {

        $pending_leave_requests = LeaveRequest::where('status', 'pending')
            ->where('user_id', auth()->id())
            ->count();
        $approved_leave_requests = LeaveRequest::where('status', 'approved')
            ->where('user_id', auth()->id())
            ->count();
        $rejected_leave_requests = LeaveRequest::where('status', 'rejected')
            ->where('user_id', auth()->id())
            ->count();

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
        return 'My Leave Requests';
    }
}
