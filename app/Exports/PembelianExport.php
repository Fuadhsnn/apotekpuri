<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class PembelianExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Pembelian::query();
    }

    public function download($filename)
    {

        // Tambahkan filter tanggal dari session jika ada
        $query = $this->query;

        $dariTanggal = Session::get('filter_dari_tanggal');
        $sampaiTanggal = Session::get('filter_sampai_tanggal');

        if ($dariTanggal) {
            $query->whereDate('tanggal_pembelian', '>=', $dariTanggal);
        }

        if ($sampaiTanggal) {
            $query->whereDate('tanggal_pembelian', '<=', $sampaiTanggal);
        }

        return Excel::download(new class($this->query) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $query;

            public function __construct($query)
            {
                $this->query = $query;
            }

            public function collection()
            {
                return $this->query->get()->map(function ($pembelian) {
                    return [
                        'nomor_faktur' => $pembelian->nomor_faktur,
                        'supplier' => $pembelian->supplier->nama_supplier ?? '-',
                        'tanggal' => $pembelian->tanggal_pembelian instanceof \Carbon\Carbon ? $pembelian->tanggal_pembelian->format('d/m/Y') : (is_string($pembelian->tanggal_pembelian) ? date('d/m/Y', strtotime($pembelian->tanggal_pembelian)) : $pembelian->tanggal_pembelian),
                        'total' => $pembelian->total_harga,
                        'status_pembayaran' => $pembelian->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas',
                        'jumlah_item' => $pembelian->pembelianDetails->count(),
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'No. Faktur',
                    'Supplier',
                    'Tanggal',
                    'Total',
                    'Status Pembayaran',
                    'Jumlah Item',
                ];
            }
        }, $filename . '.xlsx');
    }
}
