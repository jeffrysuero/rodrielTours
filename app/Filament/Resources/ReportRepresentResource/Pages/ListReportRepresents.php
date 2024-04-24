<?php

namespace App\Filament\Resources\ReportRepresentResource\Pages;

use App\Filament\Resources\ReportRepresentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportRepresents extends ListRecords
{
    protected static string $resource = ReportRepresentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
