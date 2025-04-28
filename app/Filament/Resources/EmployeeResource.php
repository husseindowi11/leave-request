<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\RelationManagers\UserRelationManager;
use App\Models\Employee;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
    protected static ?string $model = User::class;

    protected static ?string $label = 'Employee';

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

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn($get) => $get('id') == null)
                    ->dehydrated(fn($state) => !empty($state))
                    ->confirmed()
                    ->minLength(8)
                    ->maxLength(255)
                    ->visible(fn($get) => $get('id') == null),

                TextInput::make('password_confirmation')
                    ->label('Password Confirm')
                    ->password()
                    ->required(fn($get) => $get('id') == null)
                    ->dehydrated(fn($state) => !empty($state))
                    ->minLength(8)
                    ->maxLength(255)
                    ->visible(fn($get) => $get('id') == null),

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


                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->columns(2)
                    ->label('Assigned Roles'),



                FileUpload::make('image')
                    ->label('Profile Image')
                    ->image()
                    ->disk('public')
                    ->directory('employees')
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

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Image'),

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
