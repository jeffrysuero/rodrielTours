<?php

namespace App\Filament\Resources\ViewResource\Pages;

use App\Filament\Resources\ViewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViews extends ListRecords
{
    protected static string $resource = ViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
