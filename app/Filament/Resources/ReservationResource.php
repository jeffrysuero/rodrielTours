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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Servicios';

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
                                    ->searchable()
                                    ->noSearchResultsMessage('Cliente no encontrado')
                                    ->options($client),
                                Select::make('vehicleId')
                                    ->label('Vehiculo/Chofer')
                                    ->searchable()
                                    ->noSearchResultsMessage('Chofer no encontrado')
                                    ->options($vehicle),
                                Forms\Components\TextInput::make('min_KM')->label('Minuto Y Kilometro')
                                    ->required(),
                                Forms\Components\TextInput::make('suitcases')->label('Maletas')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('numPeople')->label('Numero de Persona')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('total_cost')->label('Costo Total')
                                    ->required()
                                    ->numeric(),
                            ])
                    ]),


            ]);
    }




    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\Layout\Panel::make([
                Tables\Columns\TextColumn::make('client.airport')->icon('heroicon-m-paper-airplane'),
                Tables\Columns\TextColumn::make('client.hotel')->icon('heroicon-m-building-office')->alignEnd(),
                Tables\Columns\TextColumn::make('client.arrivalDate')->icon('heroicon-m-clock')->alignLeft(),
                Tables\Columns\TextColumn::make('client.num_air')->icon('heroicon-m-document-text')->alignEnd(),
                Tables\Columns\TextColumn::make('min_KM')->icon('heroicon-m-chart-bar-square'),
                Tables\Columns\TextColumn::make('suitcases')->icon('heroicon-m-inbox-stack')->alignEnd(),
                Tables\Columns\TextColumn::make('numPeople')->icon('heroicon-m-user-group'),
                Tables\Columns\TextColumn::make('client.name')->icon('heroicon-m-user-circle')->alignEnd(),
                Tables\Columns\TextColumn::make('client.phone')->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')->alignEnd(),
                ImageColumn::make('vehicle.image')->label('Vehiculo')->height(90)->circular(),
                Tables\Columns\TextColumn::make('vehicle.users.name')->icon('heroicon-m-academic-cap')->alignEnd(),
                Tables\Columns\TextColumn::make('vehicle.placa')->icon('heroicon-m-bars-3'),
            ]),
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

    public static function getEloquentQueryTableDashboard(): Builder
    {
        $user = Auth()->user();

        if ($user->roles[0]->name === 'Administrador') {
            return parent::getEloquentQuery();
        } else {

            return parent::getEloquentQuery();
        }
    }
}
