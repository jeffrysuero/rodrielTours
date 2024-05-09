<?php

namespace App\Filament\Resources\TransferzResource\Pages;

use App\Filament\Resources\TransferzResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransferzs extends ListRecords
{
    protected static string $resource = TransferzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
