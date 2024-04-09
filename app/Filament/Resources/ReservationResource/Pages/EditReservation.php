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

        // $data = Reservation::whereNotNull('vehicleId')->get();

        // foreach ($data as $dato) {
        //     $dato->status = 'ASIGNADO';
        //     $dato->save();
        // }

        // $data1 = Reservation::whereNull('vehicleId')->get();

        // foreach ($data1 as $dato1) {
        //     $dato1->status = 'SIN ASIGNAR';
        //     $dato1->save();
        // }

        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
            if ($reservation->status !== 'COMPLETADO') {
                // Si el estado no es "COMPLETADO", cambia el estado según si hay un vehículo asignado o no
                if ($reservation->vehicleId !== null) {
                    // Si hay un vehículo asignado, establecer el estado como 'ASIGNADO'
                    $reservation->status = 'ASIGNADO';
                } else {
                    // Si no hay vehículo asignado, establecer el estado como 'SIN ASIGNAR'
                    $reservation->status = 'SIN ASIGNAR';
                }
                
                // Guardar los cambios en la reserva
                $reservation->save();
            }
            // Si el estado es "COMPLETADO", no hagas ningún cambio
        }


        return $this->getResource()::getUrl('index');
    }
}
