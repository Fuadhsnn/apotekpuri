<?php

namespace App\Filament\Resources\PembelianReportResource\Pages;

use App\Exports\PembelianExport;
use App\Filament\Resources\PembelianReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPembelianReports extends ListRecords
{
    protected static string $resource = PembelianReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $export = new PembelianExport($this->getFilteredTableQuery());
                    return $export->download('laporan-pembelian-' . date('Y-m-d'));
                }),
        ];
    }
}
