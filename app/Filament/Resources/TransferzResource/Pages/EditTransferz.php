<?php

namespace App\Filament\Resources\TransferzResource\Pages;

use App\Filament\Resources\TransferzResource;
use App\Models\Represent;
use App\Models\Transferz;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Database\Eloquent\Model;
class EditTransferz extends EditRecord
{
    protected static string $resource = TransferzResource::class;

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
        // dd($data['representId']);
        $reservation = Transferz::all()->where('id', $data['id'])->first();
        if ($reservation && $data['userId'] != null) {
              if($data['representId'] === null){

                $reservation->update([
                
                    'status' => 'ASIGNADO',
                    'vehicleId' => $data['vehicleId'],
                    'userId' => $data['userId'],
                    'representId' => $data['representId'],
                ]);

                $represent = Represent::create([
                    'userId' => $data['representId'],
                    'transferzId' => $data['id'],
                    'choferId' => $data['userId']
                ]);
                return $reservation;
              }
              if($data['representId'] != null){
                $reservation->update([
                   
                    'status' => 'REPRESENTANTE',
                    'vehicleId' => $data['vehicleId'],
                    'userId' => $data['userId'],
                    'representId' => $data['representId'],
                ]);
                return $reservation;
              }
            $reservation->update([
                
                'status' => 'ASIGNADO',
                'vehicleId' => $data['vehicleId'],
                'userId' => $data['userId'],
                'representId' => null,
            ]);
            return $reservation;
        }
        if ($reservation && $data['representId'] != null){
            $reservation->update([
                'status' => 'REPRESENTANTE',
                'vehicleId' =>$data['vehicleId'],
                'userId' => $data['userId'],
                'representId' => $data['representId'],
            ]);
            $represent = Represent::where('transferzId', $data['id'])->first();

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
                    'transferzId' => $data['id'],
                    'choferId' => $data['userId']
                ]);
            }
            
            return $reservation;
        }
        $reservation->update([
            'status' => 'SIN ASIGNAR',
            'vehicleId' => null,
            'userId' => null,
            'representId' => $data['representId'],
        ]);

        return $reservation;
    }

    protected function afterSave(): void
    {
        $transfers = $this->getResource()::getModel()::latest()->first();
        $data = $this->record;

        if ($transfers->userId !== null) {

            $userId = $transfers->userId;

            $user = User::find($userId);

            if ($user) {
                Notification::make()
                    ->success()
                    ->title('Nueva Reservacion')
                    ->body('Reservacion asignado al chofer : ' . $user->name)
                    // ->actions([
                    //     Action::make('ver')->url(
                    //         TransferzResource::getUrl('index', ['record' => $data])
                    //     )
                    //         ->button()
                    //         ->markAsRead()

                    // ])
                    ->sendToDatabase($user);
               
            } else {
                // dd("Usuario no encontrado");
            }
        } else {
            // dd("No se envió ninguna notificación porque userId es nulo");
        }
    }
}
