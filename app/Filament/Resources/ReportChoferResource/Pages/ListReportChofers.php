<?php

namespace App\Filament\Resources\ReportChoferResource\Pages;

use App\Filament\Resources\ReportChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportChofers extends ListRecords
{
    protected static string $resource = ReportChoferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            // Actions\CreateAction::make(),
        ];
    }
}
