<?php

namespace App\Filament\Resources\ListChoferResource\Pages;

use App\Filament\Resources\ListChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListChofer extends EditRecord
{
    protected static string $resource = ListChoferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
