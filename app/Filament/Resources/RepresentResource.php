<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepresentResource\Pages;
use App\Filament\Resources\RepresentResource\RelationManagers;
use App\Models\Represent;
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
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class RepresentResource extends Resource
{
    protected static ?string $model = Represent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Servicios Representante';
    public static function form(Form $form): Form
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'details' => $vehicle->marca . ' - ' . $vehicle->placa,
            ];
        })->pluck('details', 'id')->toArray();

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

        // $vehicle = Vehicle::all()->mapWithKeys(function ($vehicles) {
        //     $user = User::where('id', $vehicles->userId)->first();
        //     return [$vehicles->id => $vehicles->marca . ' - ' . $vehicles->modelo . ' - ' . $user->name];
        // })->toArray();
        $conductores = DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->where('roles.name', 'Conductores')
        ->pluck('users.name', 'users.id')
        ->toArray();

        $reservation = Reservation::where('status', 'SIN ASIGNAR')->get();
        $reservationOptions = $reservation->pluck('numServcice', 'id')->toArray();

        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Select::make('userId')
                                    ->label('Representante')
                                    ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Cliente no encontrado')
                                    ->options($modelRoleOptions),

                                Select::make('choferId')
                                    ->label('Chofer')
                                    ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Chofer no encontrado')
                                    ->options($conductores),

                                    Select::make('vehicleId')
                                    ->label('Vehiculo')
                                    ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Vehiculo no encontrado')
                                    ->options($vehicles),

                                Select::make('reservationId')
                                    ->label('Servicios')
                                    ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('servicio no encontrado')
                                    ->options($reservationOptions),

                            ])
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\Layout\Panel::make([
                Tables\Columns\TextColumn::make('reservations.numServcice')
                    ->icon('heroicon-m-document-minus')
                    ->iconColor('success')->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('reservations.client.airport')
                    ->icon('heroicon-m-paper-airplane')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('reservations.client.hotel')->icon('heroicon-m-building-office')->iconColor('primary'),
                Tables\Columns\TextColumn::make('reservations.client.arrivalDate')
                    ->icon('heroicon-m-clock')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('reservations.client.num_air')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservations.min_KM')
                    ->icon('heroicon-m-arrow-trending-up')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservations.suitcases')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservations.numPeople')
                    ->icon('heroicon-m-user-group')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservations.client.name')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservations.client.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable(),

                ImageColumn::make('reservations.users.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),

                Tables\Columns\TextColumn::make('reservations.users.name')
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

                 Tables\Columns\TextColumn::make('reservations.users.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable(),

                Tables\Columns\TextColumn::make('reservations.arrivalDate')->icon('heroicon-m-calendar-days')->iconColor('primary')->searchable(),


                // Tables\Columns\TextColumn::make('reservations.status')->icon('heroicon-m-swatch')
                //     ->iconColor('success'),
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

                Tables\Actions\EditAction::make()
                    ->hidden(static function ($record) {
                        return $record->status === 'COMPLETADO';
                    }),

                Tables\Actions\DeleteRepresent::make(),

                //     Tables\Actions\DespacheRepresent::make()
                //     ->label('Despachar el Chofer')
                //     ->recordTitle('¿Está seguro de Despachar?')
                //     ->hidden(static function ($record) {
                //         $reserv = Reservation::findOrFail($record->reservationId);
                //         return $reserv->status !== 'DESP_CHOFER';
                //     }),

                // Tables\Actions\RepresentUpdateAction::make()
                //     ->label('Tengo el Cliente')
                //     ->recordTitle('¿Está seguro de Iniciar el Viaje?')
                //     ->hidden(static function ($record) {
                //         $reserv = Reservation::findOrFail($record->reservationId);
                //         return $reserv->status !== 'REPRESENTANTE';
                //     }),

                Tables\Actions\RepresentUpdateAction::make()
                    ->label('Tengo el Cliente')
                    ->recordTitle('Esta seguro')
                    ->hidden(static function ($record) {
                        $reserv = Reservation::findOrFail($record->reservationId);
                        return $reserv->status !== 'REPRESENTANTE';
                    }),
                Tables\Actions\DespacheRepresent::make()
                    ->label('Despachar el Chofer')
                    ->recordTitle('Esta seguro de de Despachar')
                    ->hidden(static function ($record) {
                        $reserv = Reservation::findOrFail($record->reservationId);
                        return $reserv->status !== 'DESP_CHOFER';
                    }),


            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    // ExportBulkAction::make()
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
            'index' => Pages\ListRepresents::route('/'),
            'create' => Pages\CreateRepresent::route('/create'),
            'edit' => Pages\EditRepresent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {

        // $user = User::all();

        // $modelRole = DB::table('model_has_roles')->get()->mapWithKeys(function ($role) {
        //     $user = User::find($role->model_id); 
        //     $roles = DB::table('roles')->where('id', $role->role_id)->first();
        //     return [$user->id => ['user' => $user->name, 'roles' => $roles->name]];
        // })->toArray();

        // $modelRole = DB::table('model_has_roles')
        //     ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //     ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        //     ->where('roles.name', 'Administrador') 
        //     ->select('users.*', 'roles.name as role') 
        //     ->get();

        $user = Auth()->user();
        if ($user->roles[0]->name === 'Administrador') {
            return parent::getEloquentQuery()->whereHas('reservations', function ($query) {
                $query->where('status', 'REPRESENTANTE')->orWhere('status', 'DESP_CHOFER');;
            });
        }

        if ($user->roles[0]->name === 'Operador') {
            return parent::getEloquentQuery()->whereHas('reservations', function ($query) {
                $query->where('status', 'REPRESENTANTE')->orWhere('status', 'DESP_CHOFER');;
            });
        }
        if ($user->roles[0]->name === 'Super Admin') {
            return parent::getEloquentQuery()->whereHas('reservations', function ($query) {
                $query->where('status', 'REPRESENTANTE')->orWhere('status', 'DESP_CHOFER');;
            });
        }

        // return parent::getEloquentQuery()->whereHas('reservations', function ($query) {
            //     $query->where('status', 'REPRESENTANTE')->where('userId', $user->id);
            // });
        $user = Auth()->user();
        return parent::getEloquentQuery()->where('userId', $user->id);
    }
}
