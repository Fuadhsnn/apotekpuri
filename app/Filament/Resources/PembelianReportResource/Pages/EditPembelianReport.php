<?php

namespace App\Filament\Resources\PembelianReportResource\Pages;

use App\Filament\Resources\PembelianReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelianReport extends EditRecord
{
    protected static string $resource = PembelianReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
