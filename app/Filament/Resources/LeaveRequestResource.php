<?php

namespace App\Filament\Resources;

use App\Enums\LeaveStatus;
use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('leave_type_id')
                    ->label('Leave Type')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),


                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->format('Y-m-d')
                    ->minDate(Carbon::today()),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->format('Y-m-d')
                    ->rules([
                        'after_or_equal:start_date',
                    ]),

                Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->columnSpan(2)
                    ->rows(3),


                Section::make('Status Controls')
                    ->columns(2)
                    ->schema([
                        TextInput::make('status')
                            ->hiddenLabel()
                            ->readOnly()
                            ->columnSpan(1),

                        Actions::make([
                            FormAction::make('approve')
                                ->label('Accept')
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->requiresConfirmation()
                                ->action(function ($livewire) {
                                    // update in database
                                    $livewire->record->update([
                                        'status' => LeaveStatus::Approved->value,
                                    ]);
                                })
                                ->after(function ($livewire) {
                                    // re‐load the record and refill the form with its fresh data
                                    $livewire->record = $livewire->record->refresh();
                                    $livewire->form->fill($livewire->record->toArray());
                                }),

                            FormAction::make('reject')
                                ->label('Reject')
                                ->icon('heroicon-o-x-circle')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->action(function ($livewire) {
                                    $livewire->record->update([
                                        'status' => LeaveStatus::Rejected->value,
                                    ]);
                                })
                                ->after(function ($livewire) {
                                    $livewire->record = $livewire->record->refresh();
                                    $livewire->form->fill($livewire->record->toArray());
                                }),
                        ])
                            ->columnSpan(1)
                            ->alignEnd()

                    ])->visible(fn($record) => $record?->status),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('leaveType.name')->label('Leave Type')->sortable()->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(LeaveStatus $state): string => match ($state) {
                        LeaveStatus::Pending => 'gray',
                        LeaveStatus::Approved => 'success',
                        LeaveStatus::Rejected => 'danger',
                    }),
                TextColumn::make('start_date')->label('Start')->date(),
                TextColumn::make('end_date')->label('End')->date(),
                TextColumn::make('created_at')->label('Requested At')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(LeaveStatus::cases())->mapWithKeys(fn($case) => [$case->value => ucfirst($case->value)]
                        )->toArray()
                    ),

                SelectFilter::make('leaveType')
                    ->label('Leave Type')
                    ->relationship('leaveType', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }

}
