<?php

namespace Filament\Tables\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
            if (!$record->vehicleId) {
    
                return $self->failure();
            }

            $record->update(['status' => 'EN PROGRESO', 'dateInitiated' => Carbon::now()]);

            return $self->success();
        });
    }
}
