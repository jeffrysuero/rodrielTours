<?php

namespace App\Filament\Resources\ListChoferResource\Pages;

use App\Filament\Resources\ListChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateListChofer extends CreateRecord
{
    protected static string $resource = ListChoferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
