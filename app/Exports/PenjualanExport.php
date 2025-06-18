<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Penjualan::query();
    }

    public function download($filename)
    {
        return Excel::download(new class($this->query) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $query;
            
            public function __construct($query)
            {
                $this->query = $query;
            }
            
            public function collection()
            {
                return $this->query->get()->map(function ($penjualan) {
                    return [
                        'nomor_nota' => $penjualan->nomor_nota,
                        'tanggal' => $penjualan->tanggal_penjualan->format('d/m/Y'),
                        'pelanggan' => $penjualan->nama_pelanggan ?? 'Pelanggan',
                        'total' => $penjualan->total_harga,
                        'metode_pembayaran' => $penjualan->metode_pembayaran,
                        'jumlah_item' => $penjualan->penjualanDetails->count(),
                    ];
                });
            }
            
            public function headings(): array
            {
                return [
                    'No. Nota',
                    'Tanggal',
                    'Pelanggan',
                    'Total',
                    'Metode Pembayaran',
                    'Jumlah Item',
                ];
            }
        }, $filename . '.xlsx');
    }
}
