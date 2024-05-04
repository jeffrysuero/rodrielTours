<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Represent;


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

        $data = $this->getResource()::getModel()::latest()->first();
    //   dd($reservationId);
        if ($reservations->representId !== null) {

            $represent = Represent::create([
                'userId' => $data['representId'],
                'reservationId' => $data['id'],
                'choferId' => $data['userId']
            ]);

            $reservations->status = 'REPRESENTANTE';
            $reservations->save();
        } 

        return $this->getResource()::getUrl('index');
    }

    
}
