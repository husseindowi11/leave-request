<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If it wasn't provided (or the current user isn't admin),
        // force it to the authenticated user:
        $data['user_id'] = auth()->user()->hasRole('super_admin')
            ? $data['user_id'] ?? null
            : auth()->id();

        return $data;
    }
}
