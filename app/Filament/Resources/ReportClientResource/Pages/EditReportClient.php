<?php

namespace App\Filament\Resources\ReportClientResource\Pages;

use App\Filament\Resources\ReportClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportClient extends EditRecord
{
    protected static string $resource = ReportClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
