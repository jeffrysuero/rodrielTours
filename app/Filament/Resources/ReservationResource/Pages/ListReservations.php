<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Vehicle;
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
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'SIN ASIGNAR')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'SIN ASIGNAR');
                    }),
                'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Reservation::where('status', 'ASIGNADO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'ASIGNADO');
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Reservation::where('status', 'EN PROGRESO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Reservation::where('status', 'COMPLETADO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        if ($user->roles[0]->name === 'Conductores') {
            $user = Auth()->user();
            $vehicle = Vehicle::all()->where('userId', $user->id)->first();

            return [
                'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Reservation::whereIn('status',['REPRESENTANTE', 'ASIGNADO'])
                    ->where('vehicleId', $vehicle->id)
                    // ->where('status', 'REPRESENTANTE')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->whereIn('status',['REPRESENTANTE','ASIGNADO']);
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Reservation::where('status', 'EN PROGRESO')->where('vehicleId', $vehicle->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Reservation::where('status', 'COMPLETADO')->where('vehicleId', $vehicle->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        return [];
    }
}
