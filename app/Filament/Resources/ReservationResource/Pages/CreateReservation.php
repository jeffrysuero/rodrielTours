<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Represent;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

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

    protected function afterCreate(): void
    {
        $reservation = $this->getResource()::getModel()::latest()->first();
        $data = $this->record;

        if ($reservation->userId !== null) {

            $userId = $reservation->userId;

            $user = User::find($userId);

            if ($user) {
                Notification::make()
                    ->success()
                    ->title('Nueva Reservacion')
                    ->body('Reservacion asignado al chofer : ' . $user->name)
                    ->actions([
                        Action::make('ver')->url(
                            ReservationResource::getUrl('index', ['record' => $data])
                        )
                            ->button()
                            ->markAsRead()

                    ])
                    ->sendToDatabase($user);
               
            } else {
                dd("Usuario no encontrado");
            }
        } else {
            dd("No se envió ninguna notificación porque userId es nulo");
        }
    }
}
