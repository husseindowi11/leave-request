<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->nullable(),

                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->nullable(),

                Select::make('job_position_id')
                    ->label('Job Position')
                    ->relationship('jobPosition', 'name')
                    ->nullable(),

                TextInput::make('address')
                    ->label('Address')
                    ->maxLength(255)
                    ->nullable(),

                FileUpload::make('image')
                    ->label('Profile Image')
                    ->image()
                    ->columnSpan(2)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Image'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->sortable(),

                TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable(),

                TextColumn::make('jobPosition.name')
                    ->label('Job Position')
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Department')
                    ->relationship('department', 'name'),

                SelectFilter::make('jobPosition')
                    ->label('Job Position')
                    ->relationship('jobPosition', 'name'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
