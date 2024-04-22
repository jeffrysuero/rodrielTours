<?php

namespace Filament\Tables\Actions;

use App\Models\Reservation;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class DespacheRepresent extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'Custom1';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions::delete.single.label'));

        // $this->modalHeading(fn (): string => __('filament-actions::delete.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));

        $this->color('success');

        $this->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-clipboard-document-check');

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
                // dd($record->reservationId);

                $reserv = Reservation::all()->where('id',$record->reservationId)->first();
            
                $reserv->update(['status' => 'EN PROGRESO']);
        
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
