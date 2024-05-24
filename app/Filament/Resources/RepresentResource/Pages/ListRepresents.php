<?php

namespace App\Filament\Resources\RepresentResource\Pages;

use App\Filament\Resources\RepresentResource;
use App\Models\Reservation;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\DB;

class ListRepresents extends ListRecords
{
    protected static string $resource = RepresentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()->label('Asignar servicio a Representante')->icon('heroicon-m-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        $user = Auth()->user();
        if ($user->roles[0]->name === 'Administrador') {
            return [
                'REPRESENTANTE' => Tab::make('Servicios')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'REPRESENTANTE')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                                $query->where('status', 'REPRESENTANTE');
                            });
                        }),

                'DESP_CHOFER' => Tab::make('Despachar chofer')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'DESP_CHOFER')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                               $data = $query->where('status', 'DESP_CHOFER');
                            //    dd($data);
                            });
                        }),
                // ->modifyQueryUsing(function (Builder $query) {
                //     return $query->where('status', 'SIN ASIGNAR');
                // }),
            ];
        }

        if ($user->roles[0]->name === 'Operador') {
            return [
                'REPRESENTANTE' => Tab::make('Servicios')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'REPRESENTANTE')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                                $query->where('status', 'REPRESENTANTE');
                            });
                        }),

                'DESP_CHOFER' => Tab::make('Despachar chofer')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'DESP_CHOFER')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                               $data = $query->where('status', 'DESP_CHOFER');
                            //    dd($data);
                            });
                        }),
                // ->modifyQueryUsing(function (Builder $query) {
                //     return $query->where('status', 'SIN ASIGNAR');
                // }),
            ];
        }

        if ($user->roles[0]->name === 'Super Admin') {
            return [
                'REPRESENTANTE' => Tab::make('Servicios')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'REPRESENTANTE')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                                $query->where('status', 'REPRESENTANTE');
                            });
                        }),

                'DESP_CHOFER' => Tab::make('Despachar chofer')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'DESP_CHOFER')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                               $data = $query->where('status', 'DESP_CHOFER');
                            //    dd($data);
                            });
                        }),
                // ->modifyQueryUsing(function (Builder $query) {
                //     return $query->where('status', 'SIN ASIGNAR');
                // }),
            ];
        }

        if ($user->roles[0]->name === 'Representante') {

            return [
                'REPRESENTANTE' => Tab::make('Servicios')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'REPRESENTANTE')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                                $query->where('status', 'REPRESENTANTE');
                            });
                        }),

                'DESP_CHOFER' => Tab::make('Despachar chofer')
                    ->icon('heroicon-m-x-circle')
                    ->badge(Reservation::where('status', 'DESP_CHOFER')
                        ->count() ?? 0)
                        ->modifyQueryUsing(function (Builder $query) {
                            return $query->whereHas('reservations', function ($query) {
                                $query->where('status', 'DESP_CHOFER');
                            });
                        }),
                // ->modifyQueryUsing(function (Builder $query) {
                //     $user = Auth()->user();
                //     return $query->where('userId', $user->id);
                // }),
            ];
        }

        return [];
    }
}
