<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
// use Filament\Actions\Action;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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

        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $reservation = Reservation::all()->where('id', $record->id)->first();

        if($reservation->vehicleId){
            $reservation->update(['status' => 'SIN ASIGNAR', 'vehicleId' => null]); 
             return $reservation;     
        }

        $reservation->update(['status' => 'ASIGNADO', 'vehicleId' => $data['vehicleId']]);
        
        return $reservation;

        
    }
}
