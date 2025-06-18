<?php

namespace App\Filament\Resources\PenjualanReportResource\Pages;

use App\Filament\Resources\PenjualanReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenjualanReport extends EditRecord
{
    protected static string $resource = PenjualanReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
