<?php

namespace Filament\Tables\Actions;

use App\Models\Reservation;
use App\Models\User;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RepresenChofer extends Action
{
    // protected static string $resource = ReservationResource::class;

    // // protected function getRedirectUrl(): string
    // // {
    // //     $reservationId = $this->getResource()::getModel()::latest()->first()->id;

    // //     $reservations = Reservation::where('id', $reservationId)->first();
    // //     if ($reservations->userId !== null) {

    // //         $reservations->status = 'ASIGNADO';
    // //         $reservations->save();
    // //     }

    // //     $data = $this->getResource()::getModel()::latest()->first();
    // //     //   dd($reservationId);
    // //     if ($reservations->representId !== null) {

    // //         $represent = Represent::create([
    // //             'userId' => $data['representId'],
    // //             'reservationId' => $data['id'],
    // //             'choferId' => $data['userId']
    // //         ]);

    // //         $reservations->status = 'REPRESENTANTE';
    // //         $reservations->save();
    // //     }

    // //     return $this->getResource()::getUrl('index');
    // // }
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'representChofer';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label(__('Iniciar Viaje'));

        $this->recordTitle(__('¿Está seguro de iniciar el viaje?'));

        $this->label(__('filament-actions::delete.single.label'));

        // $this->modalHeading(fn (): string => __('filament-actions::delete.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));

        $this->color('success');

        $this->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-play');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-truck');

        $this->hidden(static function (Model $record): bool {
            if (!method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });
        $self = $this;
        $this->action(function (Model $record) use ($self) {


            $user = User::find($record->representId);
            $chofer = User::find($record->userId);
            // dd($user);

            if ($user) {
                Notification::make()
                    ->success()
                    ->title('El Chofer a llegado')
                    ->body('El chofer  : ' . $chofer->name . ' ha llegado al lugar para aborda el cliente')
                    ->sendToDatabase($user);
                } else {
                    // dd("Usuario no encontrado");
                }
                $reserv = Reservation::find($record->id); 
                if ($reserv) {
                    $reserv->arrive = true;
                    $reserv->save(); 
                } else {
            
                }
              
        });
    }

   
}
