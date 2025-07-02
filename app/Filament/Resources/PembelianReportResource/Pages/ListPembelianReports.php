<?php

namespace App\Filament\Resources\PembelianReportResource\Pages;

use App\Exports\PembelianExport;
use App\Exports\PembelianDomPDFExport;
use App\Filament\Resources\PembelianReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class ListPembelianReports extends ListRecords
{
    protected static string $resource = PembelianReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Cetak Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    // Simpan filter tanggal ke session untuk digunakan saat ekspor
                    $filter = $this->getTableFiltersForm()->getRawState();
                    if (isset($filter['tanggal']['dari_tanggal'])) {
                        // Konversi ke string jika objek Carbon
                        $dariTanggal = $filter['tanggal']['dari_tanggal'];
                        if (is_object($dariTanggal) && method_exists($dariTanggal, 'format')) {
                            $dariTanggal = $dariTanggal->format('Y-m-d');
                        }
                        Session::put('filter_dari_tanggal', $dariTanggal);
                    }
                    
                    if (isset($filter['tanggal']['sampai_tanggal'])) {
                        // Konversi ke string jika objek Carbon
                        $sampaiTanggal = $filter['tanggal']['sampai_tanggal'];
                        if (is_object($sampaiTanggal) && method_exists($sampaiTanggal, 'format')) {
                            $sampaiTanggal = $sampaiTanggal->format('Y-m-d');
                        }
                        Session::put('filter_sampai_tanggal', $sampaiTanggal);
                    }
                    
                    $export = new PembelianExport($this->getFilteredTableQuery());
                    return $export->download('laporan-pembelian-' . date('Y-m-d'));
                }),
                
            Actions\Action::make('exportPDF')
                ->label('Cetak PDF')
                ->icon('heroicon-o-document-text')
                ->action(function () {
                    // Simpan filter tanggal ke session untuk digunakan saat ekspor
                    $filter = $this->getTableFiltersForm()->getRawState();
                    if (isset($filter['tanggal']['dari_tanggal'])) {
                        // Konversi ke string jika objek Carbon
                        $dariTanggal = $filter['tanggal']['dari_tanggal'];
                        if (is_object($dariTanggal) && method_exists($dariTanggal, 'format')) {
                            $dariTanggal = $dariTanggal->format('Y-m-d');
                        }
                        Session::put('filter_dari_tanggal', $dariTanggal);
                    }
                    
                    if (isset($filter['tanggal']['sampai_tanggal'])) {
                        // Konversi ke string jika objek Carbon
                        $sampaiTanggal = $filter['tanggal']['sampai_tanggal'];
                        if (is_object($sampaiTanggal) && method_exists($sampaiTanggal, 'format')) {
                            $sampaiTanggal = $sampaiTanggal->format('Y-m-d');
                        }
                        Session::put('filter_sampai_tanggal', $sampaiTanggal);
                    }
                    
                    $export = new PembelianDomPDFExport($this->getFilteredTableQuery());
                    return $export->download('laporan-pembelian-' . date('Y-m-d'));
                }),
        ];
    }
}
