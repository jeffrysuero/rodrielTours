<?php

namespace App\Filament\Resources\ReportChoferResource\Pages;

use App\Filament\Resources\ReportChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportChofer extends EditRecord
{
    protected static string $resource = ReportChoferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
