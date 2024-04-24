<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\ReservationResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VehicleResource;
use App\Models\ReservactionVehicle;
use App\Models\Reservation;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CompletedPay extends ManageRelatedRecords
{
    protected static string $resource = VehicleResource::class;

    protected static string $relationship = 'reservations';
    // protected static ?string $relationship = null;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Nomina Pagar Chofer";
    }

    // public function getBreadcrumb(): string
    // {
    //     return 'vehicle';
    // }

    public static function getNavigationLabel(): string
    {
        return 'Servicios Completados';
    }


    public function table(Table $table): Table
    {
        $record = $this->getRecord();

        $totalCost = Reservation::where('vehicleId', $record->id)
            ->where('status', 'COMPLETADO')
            ->where('active', 'SIN PAGAR')
            ->selectRaw('SUM(total_cost) as total')
            ->groupBy('vehicleId')
            ->first();

        $porc = Vehicle::where('id', $record->id)->first();
        // dd($porc->percentage);
        if ($totalCost && $porc) {
            $total = $totalCost->total * $porc->percentage / 100;
            $total = number_format($total, 2);
        } else {
            $total = 0;
        }

        return $table
            ->query(
                ReservationResource::getEloquentQueryNomina()
                    ->where('status', 'COMPLETADO')
                    ->where('vehicleId', $record->id)
                    ->where('active', 'SIN PAGAR')
            )

            ->defaultPaginationPageOption(10)
            // ->defaultSort('created_at', 'desc')

            ->heading('Dinero a Pagar $ ' . ($total ? $total : 0))
            // ->description('$'. 500.00)
            // ->recordTitleAttribute('Usuario')

            ->columns([

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('costo del viaje')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_KM')
                    ->label('K/M Recorrido')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Fecha')
                    ->dateTime()

                // Tables\Columns\TextColumn::make('customer.name')
                //     ->label('Customer')
                //     ->searchable()
                //     ->sortable(),

                // Tables\Columns\IconColumn::make('is_visible')
                //     ->label('Visibility')
                //     ->sortable(),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ])
            ->groupedBulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
                Tables\Actions\PayService::make()
                    ->label('Pagar Todo')
                    ->pluralModelLabel('Esta Seguro de Pagar todo ?')


                // ->recordTitle('Esta seguro de pagar los Servicios')

            ]);
    }
}
