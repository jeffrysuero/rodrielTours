<?php

// use App\Models\Reservation;
// use Filament\Tables\Actions\Action;
// use Illuminate\Support\Facades\Redirect;
// class CustomUpdateAction extends Action
// {
//     // public static $view = 'filament.actions.custom-update';
//     protected ?string $name = 'custom_update_action';
//     public function handle()
//     {
//         dd([]);
//         // Aquí colocas la lógica de actualización
//         // $record = Reservation::find();
//         // dd('hola');
//         // Por ejemplo:
//         // $record->update(['propiedad' => 'nuevo valor']);

//         // Si deseas redirigir a una página después de la actualización, puedes usar:
//         return Redirect::to('/dashboard');
//     }
// }



namespace Filament\Tables\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class CustomUpdateAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'Custom';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions::delete.single.label'));

        // $this->modalHeading(fn (): string => __('filament-actions::delete.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));

        $this->color('success');

        $this->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-play');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-truck');

        $this->hidden(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });

        $this->action(function (): void {
            $result = $this->process(static function (Model $record) {
                $record->update(['status' => 'EN PROGRESO']);
        
                return true;
            });

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
