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
        $reservationId = $this->getResource()::getModel()::latest()->first()->id;
        
        $reservations = Reservation::where('id', $reservationId)->first();
        if ($reservations->userId !== null) {
                 
            $reservations->status = 'ASIGNADO';
            $reservations->save();
        } 

        return $this->getResource()::getUrl('index');
    }

    
}
