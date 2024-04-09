<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;

class ReservationOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $user = Auth()->user();
      
        if ($user->roles[0]->name === 'Administrador') {

            return $table
                 ->query(ReservationResource::getEloquentQueryTableDashboard())
                ->defaultPaginationPageOption(5)
                ->defaultSort('created_at', 'desc')
                ->columns([
    
                    Tables\Columns\TextColumn::make('client.name')->label('Cliente')
                        ->searchable()
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('vehicle.marca')->label('Vehiculo')
                        ->searchable()
                        ->numeric()
                        ->sortable(),
    
                    Tables\Columns\TextColumn::make('vehicle.users.name')->label('Chofer')
                        ->searchable()
                        ->numeric()
                        ->sortable(),
    
                    // Tables\Columns\TextColumn::make('start_date')->label('Fecha de Inicio')
                    //     ->dateTime()
                    //     ->sortable(),
                    // Tables\Columns\TextColumn::make('end_date')->label('Fecha Final')
                    //     ->dateTime()
                    //     ->sortable(),
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
                ->actions([
                    Tables\Actions\Action::make('open')->label('Ver Orden')
                        ->url(fn (Reservation $record): string => ReservationResource::getUrl('edit', ['record' => $record])),
    
                ]);
        }
        return $table
        ->query(ReservationResource::getEloquentQueryTableDashboard())
        ->columns([

        ]);

    }
}
