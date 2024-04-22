<?php

namespace App\Filament\Resources\RepresentResource\Pages;

use App\Filament\Resources\RepresentResource;
use App\Models\Represent;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRepresent extends CreateRecord
{
    protected static string $resource = RepresentResource::class;

    protected function getRedirectUrl(): string
    {
        // $id = $record->id;
        $reservations = Reservation::all();
        $represent = Represent::all();

        foreach ($represent as $rep) {
            // Encuentra la reservación correspondiente al reservationId en represent
            $reservationToUpdate = $reservations->where('id', $rep->reservationId)->first();

            if ($reservationToUpdate) {
                // Actualiza el estado de la reservación
                $reservationToUpdate->status = 'REPRESENTANTE';
                $reservationToUpdate->vehicleId = $rep->vehicleId;
                $reservationToUpdate->save();
            }
        }

        return $this->getResource()::getUrl('index');
    }

    // protected function afterSave(Model $record): void
    // {

    //     $id = $record->id;
    //     dd($id);
    //     // Realiza aquí la lógica que deseas ejecutar después de que se guarde el registro.
    //     // Puedes acceder al registro recién guardado utilizando el parámetro $record.

    //     // Por ejemplo, puedes realizar un update en el registro aquí.
    //     // Supongamos que $record->id es el ID del registro recién guardado.
    //     // $record->update([...]); // Aquí deberías definir los campos y valores para actualizar.

    //     // Luego, si deseas redirigir al usuario a otra página, puedes hacerlo utilizando
    //     // el método redirectTo.
    //     // $this->redirectTo('ruta.de.tu.pagina');
    // }
}
