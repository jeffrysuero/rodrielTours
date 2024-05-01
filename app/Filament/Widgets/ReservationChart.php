<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ReservationChart extends ChartWidget
{
    protected static ?string $heading = 'Estadistica mesuales';
    protected static ?int $sort = 2;
    protected static string $color = 'success';
    protected function getData(): array
    {

        $user = Auth()->user();
        if ($user && $user->roles[0]->name === 'Administrador') {

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
            return [
                'datasets' => [
                    [
                        'label' => 'Ordenes creadas',
                        'data' => $data,
                        'fill' => 'start',
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ];
        }

        if ($user && $user->roles[0]->name === 'Operador') {

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
            return [
                'datasets' => [
                    [
                        'label' => 'Ordenes creadas',
                        'data' => $data,
                        'fill' => 'start',
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ];
        }

        if ($user && $user->roles[0]->name === 'Super Admin') {

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
            return [
                'datasets' => [
                    [
                        'label' => 'Ordenes creadas',
                        'data' => $data,
                        'fill' => 'start',
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ];
        }
        return [

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
