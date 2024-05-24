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
use Illuminate\Support\Facades\DB;
// use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components;
use Filament\Forms\Components\TimePicker;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Reservaciones';

    public static function form(Form $form): Form
    {
        $client = Client::pluck('name', 'id')->toArray();

        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'details' => $vehicle->marca . ' - ' . $vehicle->placa,
            ];
        })->pluck('details', 'id')->toArray();

        $conductores = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('roles.name', 'Conductores')
            ->pluck('users.name', 'users.id')
            ->toArray();

        /** generator the number the servcie*/

        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeroServicio = mt_rand(100000, 999999);
        $letraAleatoria = $letras[rand(0, strlen($letras) - 1)];

        $numeroServicioConLetra = $numeroServicio . $letraAleatoria;

        $existe = Reservation::where('numServcice', $numeroServicioConLetra)->exists();
        while ($existe) {
            $numeroServicio = mt_rand(100000, 999999);
            $letraAleatoria = $letras[rand(0, strlen($letras) - 1)];
            $numeroServicioConLetra = $numeroServicio . $letraAleatoria;
            $existe = Reservation::where('numServcice', $numeroServicioConLetra)->exists();
        }
        /**  */

        $modelRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('roles.name', 'Representante')
            ->select('users.*', 'roles.name as role')
            ->get()->toArray();

        $modelRoleOptions = [];
        foreach ($modelRole as $user) {
            $modelRoleOptions[$user->id] = $user->name;
        }
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\Hidden::make('id')->label('Id'),
                                Select::make('clientId')
                                    ->label('Cliente')
                                    ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Cliente no encontrado')
                                    ->options($client),

                                Select::make('userId')
                                    ->label('Chofer')
                                    ->searchable()
                                    ->noSearchResultsMessage('Chofer no encontrado')
                                    ->options($conductores),

                                Select::make('vehicleId')
                                    ->label('Vehiculo')
                                    ->searchable()
                                    ->noSearchResultsMessage('Vehiculo no encontrado')
                                    ->options($vehicles),

                                Components\DatePicker::make('arrivalDate')
                                    ->label('Fecha de Reservacion')
                                    ->displayFormat('d/m/Y')
                                    ->required(),



                                TimePicker::make('hour')
                                    ->prefixIcon('heroicon-m-check-circle')
                                    ->Format('h:m:m A')
                                    ->prefixIconColor('success'),

                                Forms\Components\TextInput::make('num_air')->label('Numero del Vuelo')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('numInfant')->label('Numero de Infantes de (0-2 años)')->numeric()
                                    ->maxLength(255),
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('min_KM')->label('Minuto Y Kilometro')
                                    ->required(),
                                Forms\Components\TextInput::make('suitcases')->label('Maletas')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('numPeople')->label('Numero de Adulto')
                                    ->required()
                                    ->numeric(),

                                Forms\Components\TextInput::make('total_cost')->label('Costo Total')
                                    ->required()
                                    ->numeric(),

                                Select::make('representId')
                                    ->label('Representante')
                                    // ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Cliente no encontrado')
                                    ->options($modelRoleOptions),

                                Forms\Components\Hidden::make('numServcice')->default($numeroServicioConLetra),

                                Forms\Components\TextInput::make('numChildren')->label('Numero de niños de (2-12 años)')->numeric()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('Datellegada')
                                    ->displayFormat('d/m/Y')
                                    ->label('Fecha de llegada'),
                                   
                            ])
                    ]),

                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\MarkdownEditor::make('airport')->label('Aeropuerto')
                                    ->required()
                                    ->maxLength(255),
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([

                                Forms\Components\MarkdownEditor::make('hotel')->label('Hotel')
                                    ->required()
                                    ->maxLength(255)
                            ])
                    ])

            ]);
    }




    public static function table(Table $table): Table
    {

        $user = Auth()->user();

        if ($user->roles[0]->name === 'Conductores') {
            return $table->columns([
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('numServcice')
                        ->icon('heroicon-m-document-minus')
                        ->iconColor('success')->alignCenter()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('airport')
                        ->icon('heroicon-m-paper-airplane')
                        ->iconColor('primary'),
                    Tables\Columns\TextColumn::make('hotel')->icon('heroicon-m-building-office')->iconColor('primary'),
                    Tables\Columns\TextColumn::make('arrivalDate')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary'),

                    Tables\Columns\TextColumn::make('num_air')
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
                    Tables\Columns\TextColumn::make('arrivalDate')->icon('heroicon-m-calendar-days')->iconColor('primary')->searchable(),
                    Tables\Columns\TextColumn::make('active')->icon('heroicon-m-banknotes')->label('estado')
                        ->alignEnd()
                        ->iconColor('warning'),
                    // Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')
                    //     ->alignEnd()
                    //     ->iconColor('warning'),

                    ImageColumn::make('users.image')->label('User')->height(90)->circular()->alignCenter(),

                    Tables\Columns\TextColumn::make('users.name')
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




                    Tables\Columns\TextColumn::make('users.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable()->alignCenter(),

                    Tables\Columns\TextColumn::make('arrivalDate')
                        ->icon('heroicon-m-calendar-days')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('hour')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary')
                        ->searchable(),


                    Tables\Columns\TextColumn::make('represent.users.name')
                        ->icon('heroicon-m-user-circle')
                        ->iconColor('success')
                        ->searchable()
                        ->alignEnd(),

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
                        ->url(fn (Reservation $record): string => route('trajectory', ['id' => $record->id])),

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
                        ->failureNotificationTitle('no tiene vehiculo asignado')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'EN PROGRESO', 'SIN ASIGNAR', 'REPRESENTANTE', 'DESP_CHOFER']);
                        }),
                    Tables\Actions\CompletedService::make()
                        ->label('Terminar Servicio')
                        ->recordTitle('Esta seguro de Terminar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'ASIGNADO', 'SIN ASIGNAR', 'REPRESENTANTE', 'DESP_CHOFER']);
                        }),

                    Tables\Actions\RepresenChofer::make()
                        ->label('Estoy Aqui')
                        ->recordTitle('Esta seguro de que has llegado')
                        ->hidden(static function ($record) {

                            return $record->arrive !== 0;
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
                    Tables\Columns\TextColumn::make('page')
                        ->icon('heroicon-m-globe-alt')
                        ->iconColor('success')->alignCenter()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('numServcice')
                        ->icon('heroicon-m-document-minus')
                        ->iconColor('success')->alignCenter()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('airport')
                        ->icon('heroicon-m-paper-airplane')
                        ->iconColor('primary'),
                    Tables\Columns\TextColumn::make('hotel')->icon('heroicon-m-building-office')->iconColor('primary'),
                    Tables\Columns\TextColumn::make('arrivalDate')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary'),

                    Tables\Columns\TextColumn::make('num_air')
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
                        ->icon('heroicon-m-user')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('numChildren')
                        ->icon('heroicon-m-users')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('numInfant')
                        ->icon('heroicon-m-face-smile')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.name')
                        ->icon('heroicon-m-user-circle')
                        ->iconColor('success')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('client.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable(),
                    Tables\Columns\TextColumn::make('arrivalDate')->icon('heroicon-m-calendar-days')->iconColor('primary')->searchable(),
                    Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')->alignEnd()
                        ->iconColor('warning'),
                    Tables\Columns\TextColumn::make('active')->icon('heroicon-m-banknotes')->label('estado')
                        ->alignEnd()
                        ->iconColor('warning'),

                    ImageColumn::make('users.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),

                    Tables\Columns\TextColumn::make('users.name')
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

                    Tables\Columns\TextColumn::make('users.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable()->alignCenter(),

                    Tables\Columns\TextColumn::make('Datellegada')
                        ->icon('heroicon-m-calendar-days')
                        ->iconColor('primary')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('hour')
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary')
                        ->searchable(),



                    Tables\Columns\TextColumn::make('represent.users.name')
                        ->icon('heroicon-m-user-circle')
                        ->iconColor('success')
                        ->searchable()
                        ->alignEnd(),

                    Tables\Columns\TextColumn::make('status')->icon('heroicon-m-swatch')
                        ->iconColor('success')
                        ->alignEnd(),
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
                        ->url(fn (Reservation $record): string => route('trajectory', ['id' => $record->id])),

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
                        ->failureNotificationTitle('no tiene vehiculo asignado')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'REPRESENTANTE', 'EN PROGRESO', 'SIN ASIGNAR']);
                        }),
                    Tables\Actions\CompletedService::make()
                        ->label('Terminar Servicio')
                        ->recordTitle('Esta seguro de Terminar el Viaje')
                        ->hidden(static function ($record) {
                            return in_array($record->status, ['COMPLETADO', 'ASIGNADO', 'REPRESENTANTE', 'SIN ASIGNAR']);
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
        $reservaction = Reservation::where('status', 'COMPLETADO')->first();
        // if($reservaction){
        //     return [
        //         'index' => Pages\ListReservations::route('/'),
        //         'create' => Pages\CreateReservation::route('/create'),
        //         // 'edit' => Pages\EditReservation::route('/{record}/edit'),

        //     ];
        // }
        // dd($reservaction);

        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),

        ];
    }

    public static function getEloquentQueryTableDashboard(): Builder
    {
        $user = Auth()->user();

        if ($user->roles[0]->name === 'Administrador' || $user->roles[0]->name === 'Operador' || $user->roles[0]->name === 'Super Admin') {
            return parent::getEloquentQuery()->where('status', '!=', 'COMPLETADO');
        } else {

            return parent::getEloquentQuery();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth()->user();
        // $vehicle = Vehicle::all()->where('userId', $user->id)->first();
        // dd($vehicle);

        if ($user->roles[0]->name === 'Administrador') {
            return parent::getEloquentQuery();
        }
        if ($user->roles[0]->name === 'Operador') {
            return parent::getEloquentQuery();
        }
        if ($user->roles[0]->name === 'Super Admin') {
            return parent::getEloquentQuery();
        }
        if ($user->roles[0]->name === 'Representante') {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('userId', $user->id);
    }

    public static function getEloquentQueryNomina(): Builder
    {

        return parent::getEloquentQuery()
            ->join('users', 'reservations.userId', '=', 'users.id')
            // ->orderBy('reservations.created_at', 'DESC')
            ->select('reservations.*');
    }
}
