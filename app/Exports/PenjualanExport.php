<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class PenjualanExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Penjualan::query();
    }

    public function download($filename)
    {
        // Tambahkan filter tanggal dari session jika ada
        $query = $this->query;
        
        $dariTanggal = Session::get('filter_dari_tanggal');
        $sampaiTanggal = Session::get('filter_sampai_tanggal');
        
        if ($dariTanggal) {
            $query->whereDate('tanggal_penjualan', '>=', $dariTanggal);
        }
        
        if ($sampaiTanggal) {
            $query->whereDate('tanggal_penjualan', '<=', $sampaiTanggal);
        }
        
        return Excel::download(new class($query) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
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
                        'tanggal' => $penjualan->tanggal_penjualan instanceof \Carbon\Carbon ? $penjualan->tanggal_penjualan->format('d/m/Y') : $penjualan->tanggal_penjualan,
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
