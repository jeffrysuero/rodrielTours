<?php

namespace App\Filament\Resources\ListChoferResource\Pages;

use App\Filament\Resources\ListChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListChofers extends ListRecords
{
    protected static string $resource = ListChoferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nuevo Chofer')->icon('heroicon-m-plus-circle'),
        ];
    }
}
