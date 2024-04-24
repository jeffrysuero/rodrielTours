<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getRedirectUrl(): string
    {

        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
           
                if ($reservation->vehicleId !== null) {
                 
                    $reservation->status = 'ASIGNADO';
                } 
                $reservation->save();
        
        }

        return $this->getResource()::getUrl('index');
    }
}
