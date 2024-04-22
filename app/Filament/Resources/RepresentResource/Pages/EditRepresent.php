<?php

namespace App\Filament\Resources\RepresentResource\Pages;

use App\Filament\Resources\RepresentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepresent extends EditRecord
{
    protected static string $resource = RepresentResource::class;

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
}
