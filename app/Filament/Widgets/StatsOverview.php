<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;
    protected function getStats(): array
    {

        $user = Auth()->user();
        $vehicle = Vehicle::all()->where('userId', $user->id)->first();

        if ($user->roles[0]->name === 'Administrador') {
            return [
                Stat::make('Total Choferes ', User::count())
                    ->description('Increasing Choferes')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([6, 4, 9, 5, 3, 0, 7]),

                Stat::make('Total Clientes ', Client::count())
                    ->description('Increasing Clientes')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([6, 4, 9, 5, 3, 0, 7]),

                Stat::make('Total Reservaciones ', Reservation::count())
                    ->description('Increasing Reservaciones')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([1, 9, 9, 8, 3, 7, 7])
            ];
        }

        return [
            Stat::make('Total de Servicios ', Reservation::where('vehicleId',$vehicle->id)->count())
            ->description('Servicios Asignados')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([1, 9, 9, 8, 3, 7, 7])
        ];
    }
}
