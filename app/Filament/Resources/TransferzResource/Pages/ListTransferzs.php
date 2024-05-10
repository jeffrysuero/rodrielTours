<?php

namespace App\Filament\Resources\TransferzResource\Pages;

use App\Filament\Resources\TransferzResource;
use App\Models\Transferz;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransferzs extends ListRecords
{
    protected static string $resource = TransferzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $user = Auth()->user();

        if ($user->roles[0]->name === 'Administrador') {
            return [
                'SIN ASIGNAR' => Tab::make('Servicios sin Asignar')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Transferz::where('status', 'SIN ASIGNAR')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'SIN ASIGNAR');
                    }),
                'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Transferz::whereIn('status',['REPRESENTANTE','ASIGNADO'])

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->whereIn('status',['REPRESENTANTE','ASIGNADO']);
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Transferz::where('status', 'EN PROGRESO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Transferz::where('status', 'COMPLETADO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        if ($user->roles[0]->name === 'Operador') {
            return [
                'SIN ASIGNAR' => Tab::make('Servicios sin Asignar')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Transferz::where('status', 'SIN ASIGNAR')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'SIN ASIGNAR');
                    }),
                'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Transferz::whereIn('status',['REPRESENTANTE','ASIGNADO'])

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->whereIn('status',['REPRESENTANTE','ASIGNADO']);
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Transferz::where('status', 'EN PROGRESO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Transferz::where('status', 'COMPLETADO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        if ($user->roles[0]->name === 'Super Admin') {
            return [
                'SIN ASIGNAR' => Tab::make('Servicios sin Asignar')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Transferz::where('status', 'SIN ASIGNAR')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'SIN ASIGNAR');
                    }),
                    'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Transferz::whereIn('status',['REPRESENTANTE','ASIGNADO'])

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->whereIn('status',['REPRESENTANTE','ASIGNADO']);
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Transferz::where('status', 'EN PROGRESO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Transferz::where('status', 'COMPLETADO')

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        if ($user->roles[0]->name === 'Conductores') {
            $user = Auth()->user();
            // $vehicle = Vehicle::all()->where('userId', $user->id)->first();

            return [
                'ASIGNADO' => Tab::make('Servicios Asignado a Choferes')
                    ->icon('heroicon-m-user-circle')
                    ->badge(Transferz::whereIn('status',['REPRESENTANTE', 'ASIGNADO'])
                    // ->where('vehicleId', $vehicle->id)
                    ->where('userId', $user->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->whereIn('status',['REPRESENTANTE','ASIGNADO']);
                    }),

                    'ON HOLD' => Tab::make('En espera a Recojer pasajero')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Transferz::where('status', 'DESP_CHOFER')->where('userId', $user->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'DESP_CHOFER');
                    }),

                'EN PROGRESO' => Tab::make('Servicios en Progreso')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->badge(Transferz::where('status', 'EN PROGRESO')->where('userId', $user->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'EN PROGRESO');
                    }),

                'COMPLETADO' => Tab::make('Servicios Completados')
                    ->icon('heroicon-m-hand-thumb-up')
                    ->badge(Transferz::where('status', 'COMPLETADO')->where('userId', $user->id)

                        ->count() ?? 0)
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status', 'COMPLETADO');
                    }),
            ];
        }

        return [];
    }
}
