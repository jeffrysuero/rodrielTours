<?php

namespace App\Filament\Resources\ReportServicePagosResource\Pages;

use App\Filament\Resources\ReportServicePagosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportServicePagos extends EditRecord
{
    protected static string $resource = ReportServicePagosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
