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
use App\Models\Represent;
use App\Models\User;

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
        //  dd($data);
        $reservation = Reservation::all()->where('id', $data['id'])->first();
        if ($reservation && $data['userId'] != null) {
            if ($data['representId'] === null) {

                $reservation->update([
                    'clientId' => $data['clientId'],
                    'status' => 'ASIGNADO',
                    'vehicleId' => $data['vehicleId'],
                    'min_KM' => $data['min_KM'],
                    'suitcases' => $data['suitcases'],
                    'numPeople' => $data['numPeople'],
                    'total_cost' => $data['total_cost'],
                    'numServcice' => $data['numServcice'],
                    'userId' => $data['userId'],
                    'arrivalDate' => $data['arrivalDate'],
                    'hour' => $data['hour'],
                    'representId' => $data['representId'],

                    // 'arrive' => $data['arrive'],
                    'airport' => $data['airport'],
                    'hotel' => $data['hotel'],
                    'num_air' => $data['num_air'],
                    'numChildren' => $data['numChildren'],
                    'numInfant' => $data['numInfant'],
                    'Datellegada' => $data['Datellegada'],
                ]);

                $represent = Represent::create([
                    'userId' => $data['representId'],
                    'reservationId' => $data['id'],
                    'choferId' => $data['userId']
                ]);
                return $reservation;
            }
            if ($data['representId'] != null) {
                $reservation->update([
                    'clientId' => $data['clientId'],
                    'status' => 'REPRESENTANTE',
                    'vehicleId' => $data['vehicleId'],
                    'min_KM' => $data['min_KM'],
                    'suitcases' => $data['suitcases'],
                    'numPeople' => $data['numPeople'],
                    'total_cost' => $data['total_cost'],
                    'numServcice' => $data['numServcice'],
                    'userId' => $data['userId'],
                    'arrivalDate' => $data['arrivalDate'],
                    'hour' => $data['hour'],
                    'representId' => $data['representId'],

                    // 'arrive' => $data['arrive'],
                    'airport' => $data['airport'],
                    'hotel' => $data['hotel'],
                    'num_air' => $data['num_air'],
                    'numChildren' => $data['numChildren'],
                    'numInfant' => $data['numInfant'],
                    'Datellegada' => $data['Datellegada'],
                ]);
                $represent = Represent::where('reservationId', $data['id'])->first();

                if ($represent) {
                    // Si se encontró un registro, actualizar sus campos
                    $represent->update([
                        'userId' => $data['representId'],
                        'choferId' => $data['userId']
                    ]);
                } else {
                    // Si no se encontró ningún registro, crear uno nuevo
                    Represent::create([
                        'userId' => $data['representId'],
                        'reservationId' => $data['id'],
                        'choferId' => $data['userId']
                    ]);
                }
                return $reservation;
            }
            $reservation->update([
                'clientId' => $data['clientId'],
                'status' => 'ASIGNADO',
                'vehicleId' => $data['vehicleId'],
                'min_KM' => $data['min_KM'],
                'suitcases' => $data['suitcases'],
                'numPeople' => $data['numPeople'],
                'total_cost' => $data['total_cost'],
                'numServcice' => $data['numServcice'],
                'userId' => $data['userId'],
                'arrivalDate' => $data['arrivalDate'],
                'hour' => $data['hour'],
                'representId' => null,

                // 'arrive' => $data['arrive'],
                'airport' => $data['airport'],
                'hotel' => $data['hotel'],
                'num_air' => $data['num_air'],
                'numChildren' => $data['numChildren'],
                'numInfant' => $data['numInfant'],
                'Datellegada' => $data['Datellegada'],
            ]);
            return $reservation;
        }
        if ($reservation && $data['representId'] != null) {
            $reservation->update([
                'status' => 'REPRESENTANTE',
                'vehicleId' => $data['vehicleId'],
                'clientId' => $data['clientId'],
                'min_KM' => $data['min_KM'],
                'suitcases' => $data['suitcases'],
                'numPeople' => $data['numPeople'],
                'total_cost' => $data['total_cost'],
                'numServcice' => $data['numServcice'],
                'userId' => $data['userId'],
                'arrivalDate' => $data['arrivalDate'],
                'hour' => $data['hour'],
                'representId' => $data['representId'],

                // 'arrive' => $data['arrive'],
                'airport' => $data['airport'],
                'hotel' => $data['hotel'],
                'num_air' => $data['num_air'],
                'numChildren' => $data['numChildren'],
                'numInfant' => $data['numInfant'],
                'Datellegada' => $data['Datellegada'],
            ]);
            $represent = Represent::where('reservationId', $data['id'])->first();

            if ($represent) {
                // Si se encontró un registro, actualizar sus campos
                $represent->update([
                    'userId' => $data['representId'],
                    'choferId' => $data['userId']
                ]);
            } else {
                // Si no se encontró ningún registro, crear uno nuevo
                Represent::create([
                    'userId' => $data['representId'],
                    'reservationId' => $data['id'],
                    'choferId' => $data['userId']
                ]);
            }

            return $reservation;
        }
        $reservation->update([
            'status' => 'SIN ASIGNAR',
            'vehicleId' => null,
            'clientId' => $data['clientId'],
            'min_KM' => $data['min_KM'],
            'suitcases' => $data['suitcases'],
            'numPeople' => $data['numPeople'],
            'total_cost' => $data['total_cost'],
            'numServcice' => $data['numServcice'],
            'userId' => null,
            'arrivalDate' => $data['arrivalDate'],
            'hour' => $data['hour'],
            'representId' => $data['representId'],

            // 'arrive' => $data['arrive'],
            'airport' => $data['airport'],
            'hotel' => $data['hotel'],
            'num_air' => $data['num_air'],
            'numChildren' => $data['numChildren'],
            'numInfant' => $data['numInfant'],
            'Datellegada' => $data['Datellegada'],
        ]);

        return $reservation;
    }

    protected function afterSave(): void
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
                // dd("Usuario no encontrado");
            }
        } else {
            // dd("No se envió ninguna notificación porque userId es nulo");
        }
    }
}
