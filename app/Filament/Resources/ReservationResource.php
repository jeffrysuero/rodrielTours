<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Reservaciones';

    public static function form(Form $form): Form
    {
        $client = Client::pluck('name', 'id')->toArray();

        $vehicle = Vehicle::all()->mapWithKeys(function ($vehicles) {
            $user = User::where('id', $vehicles->userId)->first();
            return [$vehicles->id => $vehicles->marca . ' - ' . $vehicles->modelo . ' - ' . $user->name];
        })->toArray();


        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Select::make('clientId')
                                    ->label('Cliente')
                                    ->options($client),
                                Select::make('vehicleId')
                                    ->label('Vehiculo')
                                    ->options($vehicle),
                                Forms\Components\DateTimePicker::make('start_date')->label('Fecha de Inicio')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('end_date')->label('Fecha Final')
                                    ->required(),
                                Forms\Components\TextInput::make('total_cost')->label('Costo Total')
                                    ->required()
                                    ->numeric(),
                            ])
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')->label('Cliente')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.marca')->label('Vehiculo')
                    ->searchable()
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')->label('Fecha de Inicio')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')->label('Fecha Final')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')->label('Costo Total')
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
