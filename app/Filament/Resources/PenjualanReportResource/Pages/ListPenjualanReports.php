<?php

namespace App\Filament\Resources\PenjualanReportResource\Pages;

use App\Exports\PenjualanExport;
use App\Filament\Resources\PenjualanReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPenjualanReports extends ListRecords
{
    protected static string $resource = PenjualanReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $export = new PenjualanExport($this->getFilteredTableQuery());
                    return $export->download('laporan-penjualan-' . date('Y-m-d'));
                }),
        ];
    }
}
