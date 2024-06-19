<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferzResource\Pages;
use App\Filament\Resources\TransferzResource\RelationManagers;
use App\Models\Transferz;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;

class TransferzResource extends Resource
{
    protected static ?string $model = Transferz::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Viajes';
    public static function form(Form $form): Form
    {
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

        $existe = Transferz::where('numServcice', $numeroServicioConLetra)->exists();
        while ($existe) {
            $numeroServicio = mt_rand(100000, 999999);
            $letraAleatoria = $letras[rand(0, strlen($letras) - 1)];
            $numeroServicioConLetra = $numeroServicio . $letraAleatoria;
            $existe = Transferz::where('numServcice', $numeroServicioConLetra)->exists();
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
                        
                                Select::make('userId')
                                    ->label('Chofer')
                                    ->searchable()
                                    ->noSearchResultsMessage('Chofer no encontrado')
                                    ->options($conductores),

                                Select::make('vehicleId')
                                    ->label('Vehiculo')
                                    ->searchable()
                                    ->noSearchResultsMessage('Vehiculo no encontrado')
                                    ->options($vehicles)

                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                            

                                    Select::make('representId')
                                    ->label('Representante')
                                    // ->required()
                                    ->searchable()
                                    ->noSearchResultsMessage('Cliente no encontrado')
                                    ->options($modelRoleOptions),

                                Forms\Components\Hidden::make('numServcice')->default($numeroServicioConLetra)
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\Layout\Panel::make([
                Tables\Columns\TextColumn::make('numServcice')
                    ->icon('heroicon-m-document-minus')
                    ->iconColor('success')->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('phone_number')->icon('heroicon-m-phone')->iconColor('primary'),
                Tables\Columns\TextColumn::make('driver_link')
                    ->icon('heroicon-m-link')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('journey_code')
                    ->icon('heroicon-m-command-line')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pickup_date')
                    ->icon('heroicon-m-clock')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('from_location')->icon('heroicon-m-paper-airplane')->iconColor('primary')->searchable(),
                Tables\Columns\TextColumn::make('to_location')->icon('heroicon-m-building-office')->iconColor('primary')->searchable(),


                Tables\Columns\TextColumn::make('flight_number')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('ship_name')
                    ->icon('heroicon-m-user-group')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('train_number')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('suitcases')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('travellers')->icon('heroicon-m-user-group')->label('estado')

                    ->iconColor('success'),
                // Tables\Columns\TextColumn::make('flight_number')->icon('heroicon-m-user-group')->alignEnd()
                // ->iconColor('success'),

                Tables\Columns\TextColumn::make('meet_greet')
                    ->icon('heroicon-m-document-minus')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('Add_ons')
                    ->icon('heroicon-m-bars-3')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('comments')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->iconColor('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_category')
                    ->icon('heroicon-m-tag')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('partner_reference')
                    ->icon('heroicon-m-arrow-right')
                    ->iconColor('success')
                    ->searchable(),


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
                    ->url(fn (Transferz $record): string => '#' . urlencode($record->url)),

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
                        return in_array($record->status, ['COMPLETADO', 'REPRESENTANTE','EN PROGRESO', 'SIN ASIGNAR']);
                    }),
                Tables\Actions\CompletedService::make()
                    ->label('Terminar Servicio')
                    ->recordTitle('Esta seguro de Terminar el Viaje')
                    ->hidden(static function ($record) {
                        return in_array($record->status, ['COMPLETADO', 'ASIGNADO','REPRESENTANTE', 'SIN ASIGNAR']);
                    }),


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
            'index' => Pages\ListTransferzs::route('/'),
            'create' => Pages\CreateTransferz::route('/create'),
            'edit' => Pages\EditTransferz::route('/{record}/edit'),
        ];
    }
}
