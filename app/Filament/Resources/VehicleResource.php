<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Vehiculos';

    public static function form(Form $form): Form
    {
        $users = User::pluck('name', 'id')->toArray();
        return $form

            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('marca')->label('Marca ')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('modelo')->label('Modelo')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('color')->label('Color')
                                    ->required(),

                                Forms\Components\TextInput::make('type')->label('Tipo')
                                    ->required()
                                    ->maxLength(255),
                            ])
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('passenger_capacity')->label('Capasidad de Pasagero')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('luggage_capacity')->label('Capasidad de Equipaje')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('placa')->label('Placa')
                                    ->required(),

                                Select::make('userId')
                                    ->required()
                                    ->label('Chefer')
                                    ->options($users),
                            ])
                    ]),

                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\FileUpload::make('image')->label('Imagen del Vehiculo')
                                    ->image()
                                    ->required()
                                    ->directory('vehicles'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Tables\Columns\TextColumn::make('users.name')->label('Chofer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')->label('Color')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')->label('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('passenger_capacity')->label('Cap.Pasagero')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('luggage_capacity')->label('Cap.Equipaje')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image')
                    ->height(90)
                    ->circular(),
                Tables\Columns\TextColumn::make('placa')->label('Placa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
