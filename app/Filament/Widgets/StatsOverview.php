<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    // protected static ?string $pollingInterval = null;
    // protected static bool $isLazy = false;
    protected static ?int $sort = 0;
    protected function getStats(): array
    {

        $user = Auth()->user();

        if ($user->roles[0]->name === 'Representante') {
            return [];
        }

        $vehicle = Vehicle::all()->where('userId', $user->id)->first();


        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfDay();

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now()->endOfDay();

        $monthlyTotal = Reservation::selectRaw('SUM(total_cost) as total, MONTH(created_at) as month')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::createFromFormat('!m', $i)->format('F');
            $labels[] = $monthName;
            $data[] = $monthlyTotal[$i] ?? 0;
        }


        if ($user->roles[0]->name === 'Administrador') {
            return [
                Stat::make('Total Choferes ', User::where('name', '!=', 'admin')->count())
                    ->description('Increasing Choferes')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('primary')
                    ->chart([6, 4, 9, 5, 3, 0, 7]),

                Stat::make('Total Clientes ', Client::count())
                    ->description('Increasing Clientes')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('primary')
                    ->chart([6, 4, 9, 5, 3, 0, 7]),

                Stat::make('Total por Servicios $', value(array_sum($data)))
                    ->description('Increasing Reservaciones')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('warning')
                    ->chart($data),

                Stat::make('Total Servicios Completados $', Reservation::where('status', 'COMPLETADO')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->sum('total_cost'))
                    ->description('Increasing Reservaciones')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([1, 9, 9, 8, 3, 7, 7]),

                Stat::make('Servicios Completados', Reservation::where('status', 'COMPLETADO')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->count())
                    ->description('Servicios Completados')
                    ->descriptionIcon('heroicon-m-hand-thumb-up')
                    ->color('success')
                    ->chart([1, 9, 9, 8, 3, 7, 7]),

                Stat::make(' ', "")
                    ->description('')
                    // ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('primary')
                    ->chart([2, 2, 2, 2, 2, 2, 2]),
            ];
        }

        $vehicle = Vehicle::where('userId', $user->id)->first();

        $totalCost = Reservation::where('vehicleId', $vehicle->id)
            ->where('status', 'COMPLETADO')
            ->where('active', 'SIN PAGAR')
            ->selectRaw('SUM(total_cost) as total')
            ->groupBy('vehicleId')
            ->first();

        $porc = Vehicle::where('id', $vehicle->id)->first();
        // dd($porc->percentage);
        if ($totalCost && $porc) {
            $total = $totalCost->total * $porc->percentage / 100;
            $total = number_format($total, 2);
        } else {
            $total = 0;
        }


        if($user->view === 1){
            return [
                Stat::make('Total de Servicios Pendiente ', Reservation::where('vehicleId', $vehicle->id)->where('status', '!=', 'COMPLETADO')->count())
                    ->description('Servicios Asignados')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([2, 2, 2, 2, 2, 2, 2]),
    
                Stat::make('Total a pagar', ($total ? $total : 0))
                    ->description('total a pagar por los servicios')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success')
                    ->chart([2, 2, 2, 2, 2, 2, 2])
            ];
        }

        return [
            Stat::make('Total de Servicios Pendiente ', Reservation::where('vehicleId', $vehicle->id)->where('status', '!=', 'COMPLETADO')->count())
                ->description('Servicios Asignados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([2, 2, 2, 2, 2, 2, 2]),

            // Stat::make('Total a pagar', ($total ? $total : 0))
            //     ->description('total a pagar por los servicios')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('success')
            //     ->chart([2, 2, 2, 2, 2, 2, 2])
        ];
    }
}
