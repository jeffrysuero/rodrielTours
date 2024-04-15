<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
// use CustomUpdateAction;
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
// use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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

        $user = Auth()->user();

        if ($user->roles[0]->name === 'Conductores') {
            return $table->columns([
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('client.airport')
                        ->icon('heroicon-m-paper-airplane')
                        ->iconColor('primary'),
                    Tables\Columns\TextColumn::make('client.hotel')->icon('heroicon-m-building-office')->iconColor('primary'),
                    Tables\Columns\TextColumn::make('client.arrivalDate')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary'),

                    Tables\Columns\TextColumn::make('client.num_air')
                        ->icon('heroicon-m-document-text')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('min_KM')
                        ->icon('heroicon-m-arrow-trending-up')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('suitcases')
                        ->icon('heroicon-m-inbox-stack')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('numPeople')
                        ->icon('heroicon-m-user-group')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.name')
                        ->icon('heroicon-m-user-circle')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable(),
                    // Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')
                    //     ->alignEnd()
                    //     ->iconColor('warning'),

                    ImageColumn::make('vehicle.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),
                    Tables\Columns\TextColumn::make('vehicle.placa')
                        ->icon('heroicon-m-bars-3')
                        ->alignCenter()
                        ->iconColor('primary')
                        ->searchable(),


                    ImageColumn::make('vehicle.users.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),

                    Tables\Columns\TextColumn::make('vehicle.users.name')
                        ->icon('heroicon-m-identification')
                        ->alignCenter()
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('status')->icon('heroicon-m-swatch')
                        ->iconColor('success'),
                ]),
            ])

                ->filters([
                    //
                ])
                ->contentGrid([
                    'md' => 1,
                    'xl' => 2,
                ])
                ->paginated([
                    18,
                    36,
                    72,
                    'all',
                ])
                ->actions([
                    Tables\Actions\Action::make('url')
                        ->label('Visit link')
                        ->icon('heroicon-m-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Reservation $record): string => '#' . urlencode($record->url)),
                    Tables\Actions\EditAction::make()
                        ->hidden(static function ($record) {
                            return $record->status === 'COMPLETADO';
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->hidden(static function ($record) {
                            return $record->status === 'COMPLETADO';
                        }),


                    Tables\Actions\CustomUpdateAction::make()
                        ->label('Iniciar Viaje')
                        ->recordTitle('Esta seguro de Iniciar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'EN PROGRESO', 'SIN ASIGNAR']);
                        }),
                    Tables\Actions\CompletedService::make()
                        ->label('Terminar Servicio')
                        ->recordTitle('Esta seguro de Terminar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'ASIGNADO', 'SIN ASIGNAR']);
                        }),
                ])
                // ->actions([

                //     Tables\Actions\ActionGroup::make([
                //         Tables\Actions\ViewAction::make()->color('info'),
                //         Tables\Actions\EditAction::make()->color('warning'),
                //         Tables\Actions\DeleteAction::make(),
                //     ])
                // ])
                ->bulkActions([
                    // Tables\Actions\BulkActionGroup::make([
                    //     Tables\Actions\DeleteBulkAction::make(),
                    // ]),
                ]);
        } else {

            return $table->columns([
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('client.airport')
                        ->icon('heroicon-m-paper-airplane')
                        ->iconColor('primary'),
                    Tables\Columns\TextColumn::make('client.hotel')->icon('heroicon-m-building-office')->iconColor('primary'),
                    Tables\Columns\TextColumn::make('client.arrivalDate')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary'),

                    Tables\Columns\TextColumn::make('client.num_air')
                        ->icon('heroicon-m-document-text')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('min_KM')
                        ->icon('heroicon-m-arrow-trending-up')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('suitcases')
                        ->icon('heroicon-m-inbox-stack')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('numPeople')
                        ->icon('heroicon-m-user-group')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.name')
                        ->icon('heroicon-m-user-circle')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable(),
                    Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')
                        ->alignEnd()
                        ->iconColor('warning'),

                    ImageColumn::make('vehicle.users.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),

                    Tables\Columns\TextColumn::make('vehicle.users.name')
                        ->icon('heroicon-m-identification')
                        ->alignCenter()
                        ->iconColor('primary')
                        ->searchable(),

                    ImageColumn::make('vehicle.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),
                    Tables\Columns\TextColumn::make('vehicle.placa')
                        ->icon('heroicon-m-bars-3')
                        ->alignCenter()
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('status')->icon('heroicon-m-swatch')
                        ->iconColor('success'),
                ]),
            ])

                ->filters([
                    //
                ])
                ->contentGrid([
                    'md' => 1,
                    'xl' => 2,
                ])
                ->paginated([
                    18,
                    36,
                    72,
                    'all',
                ])
                ->actions([
                    Tables\Actions\Action::make('url')
                        ->label('Visit link')
                        ->icon('heroicon-m-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Reservation $record): string => '#' . urlencode($record->url)),
                    Tables\Actions\EditAction::make()
                        ->hidden(static function ($record) {
                            return $record->status === 'COMPLETADO';
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->hidden(static function ($record) {
                            return $record->status === 'COMPLETADO';
                        }),


                    Tables\Actions\CustomUpdateAction::make()
                        ->label('Iniciar Viaje')
                        ->recordTitle('Esta seguro de Iniciar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'EN PROGRESO', 'SIN ASIGNAR']);
                        }),
                    Tables\Actions\CompletedService::make()
                        ->label('Terminar Servicio')
                        ->recordTitle('Esta seguro de Terminar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'ASIGNADO', 'SIN ASIGNAR']);
                        }),


                ])

                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                        ExportBulkAction::make()
                    ]),
                ]);
        }
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
            return parent::getEloquentQuery()->where('status', '!=', 'COMPLETADO');
        } else {

            return parent::getEloquentQuery();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth()->user();
        $vehicle = Vehicle::all()->where('userId', $user->id)->first();
        // dd($vehicle);

        if ($user->roles[0]->name === 'Administrador') {
            return parent::getEloquentQuery();
        }

        // $franchise = Franchise::where('id', $user->franchiseId)->first();
        // $service = Service::where('id', $franchise->serviceId)->first();

        // if ($service  && $service->name === 'stamp') {
        //     return parent::getEloquentQuery();
        // }

        // return parent::getEloquentQuery()->where('franchiseId');
        return parent::getEloquentQuery()->where('vehicleId', $vehicle->id);
    }

    public static function getEloquentQueryNomina(): Builder
    {
       
        return parent::getEloquentQuery();
    }
}
