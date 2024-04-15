<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Ganancia Ordenes Completadas';
    protected static ?int $sort = 1;
    protected static string $color = 'success';

    protected function getData(): array
    {
        $user = Auth()->user();
        if ($user->roles[0]->name === 'Administrador') {

            $monthlyTotal = Reservation::selectRaw('SUM(total_cost) as total, MONTH(updated_at) as month')
                ->where('status', 'COMPLETADO')
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
                        'label' => 'Ordenes Completadas',
                        'data' => $data,
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    
            ];
        }else{

            return [];
        }
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
