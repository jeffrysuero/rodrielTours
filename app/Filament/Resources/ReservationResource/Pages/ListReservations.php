<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nueva Reservacion')->icon('heroicon-m-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        $user = Auth()->user();
        
        if ($user->roles[0]->name === 'Administrador') {
            return [
                'SIN ASIGNAR' => Tab::make('Servicios sin Asignar')
                    //  ->icon('heroicon-m-document-check')
                    ->badge(Reservation::where('status', 'SIN ASIGNAR')
                        // ->where('franchiseId', $user->franchiseId)
                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'SIN ASIGNAR');
                    }),
                'CREADO' => Tab::make('Servicios Asignado a Choferes')
                    //  ->icon('heroicon-m-document-check')
                    ->badge(Reservation::where('status', 'CREADO')
                        // ->where('franchiseId', $user->franchiseId)
                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'CREADO');
                    }),
            ];
        }

        return [];
    }
}
