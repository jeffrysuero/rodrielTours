<?php

namespace App\Filament\Resources\ReportServicePagosResource\Pages;

use App\Filament\Resources\ReportServicePagosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportServicePagos extends ListRecords
{
    protected static string $resource = ReportServicePagosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
