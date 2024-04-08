<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {

        $data = Reservation::whereNotNull('vehicleId')->get();

        foreach ($data as $dato) {
            $dato->status = 'CREADO';
            $dato->save();
        }

        $data1 = Reservation::whereNull('vehicleId')->get();
        foreach ($data1 as $dato1) {
            $dato1->status = 'SIN ASIGNAR';
            $dato1->save();
        }

        return $this->getResource()::getUrl('index');
    }
}
