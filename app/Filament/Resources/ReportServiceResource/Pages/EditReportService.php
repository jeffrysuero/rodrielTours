<?php

namespace App\Filament\Resources\ReportServiceResource\Pages;

use App\Filament\Resources\ReportServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportService extends EditRecord
{
    protected static string $resource = ReportServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
