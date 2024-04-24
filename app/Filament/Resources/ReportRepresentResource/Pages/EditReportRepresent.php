<?php

namespace App\Filament\Resources\ReportRepresentResource\Pages;

use App\Filament\Resources\ReportRepresentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportRepresent extends EditRecord
{
    protected static string $resource = ReportRepresentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
