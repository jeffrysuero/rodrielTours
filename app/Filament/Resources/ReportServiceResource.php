<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportServiceResource\Pages;
use App\Filament\Resources\ReportServiceResource\RelationManagers;
use App\Models\ReportService;
use App\Models\Reservation;
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

class ReportServiceResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Reposrtes';
    protected static ?string $navigationLabel = 'Servicios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\Layout\Panel::make([
                Tables\Columns\TextColumn::make('numServcice')->label('Numero de Servicio')
                    ->icon('heroicon-m-document-minus')
                    ->iconColor('success')->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.airport')->label('Aereo Puerto')
                    ->icon('heroicon-m-paper-airplane')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('client.hotel')->icon('heroicon-m-building-office')->iconColor('primary')->label('Hotel'),
                Tables\Columns\TextColumn::make('client.arrivalDate')->label('Fecha de llegada Cliente')
                    ->icon('heroicon-m-clock')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('client.num_air')->label('Numero de vuelo Cliente')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('min_KM')->label('Kilometro')
                    ->icon('heroicon-m-arrow-trending-up')
                    ->iconColor('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('suitcases')->label('cantidad de maletas')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('numPeople')->label('Cantidad de personas ')
                    ->icon('heroicon-m-user-group')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('client.name')->label('Nombre de cliente')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('client.phone')->icon('heroicon-m-phone')->iconColor('primary')->searchable()->label('Telefono'),
                Tables\Columns\TextColumn::make('total_cost')->icon('heroicon-m-currency-dollar')->label('Costo del servicio')
                    ->alignEnd()
                    ->iconColor('warning'),

                    Tables\Columns\TextColumn::make('active')->icon('heroicon-m-banknotes')->label('estado')
                    ->alignEnd()
                    ->iconColor('warning'),

                ImageColumn::make('vehicle.users.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),

                Tables\Columns\TextColumn::make('vehicle.users.name')->label('Chofer')
                    ->icon('heroicon-m-identification')
                    ->alignCenter()
                    ->iconColor('primary')
                    ->searchable(),

                ImageColumn::make('vehicle.image')->label('Vehiculo')->height(90)->circular()->alignCenter(),
                Tables\Columns\TextColumn::make('vehicle.placa')->label('Placa del vehiculo')
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
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReportServices::route('/'),
            'create' => Pages\CreateReportService::route('/create'),
            // 'edit' => Pages\EditReportService::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
       
        // $modelRole = DB::table('model_has_roles')
        //     ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //     ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        //     ->where('roles.name', 'Representante')
        //     ->pluck('users.id');

        // $allUserIds = $modelRole->toArray();
        // return parent::getEloquentQuery()->whereIn('id', $allUserIds);
        return parent::getEloquentQuery();
    }
}