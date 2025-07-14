<?php

namespace App\Exports;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianTempPDFExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Pembelian::query();
    }

    public function download($filename)
    {
        try {
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

            // Ambil data dengan eager loading
            $pembelians = $query->with(['pembelianDetails.obat', 'supplier'])->get();

            // Konversi data tanggal ke format yang benar
            $dariTanggalFormatted = null;
            $sampaiTanggalFormatted = null;
            
            try {
                $dariTanggalFormatted = $dariTanggal ? Carbon::parse($dariTanggal)->format('d/m/Y') : null;
            } catch (\Exception $e) {
                \Log::warning('Error parsing dari_tanggal', [
                    'tanggal' => $dariTanggal,
                    'error' => $e->getMessage()
                ]);
            }
            
            try {
                $sampaiTanggalFormatted = $sampaiTanggal ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : null;
            } catch (\Exception $e) {
                \Log::warning('Error parsing sampai_tanggal', [
                    'tanggal' => $sampaiTanggal,
                    'error' => $e->getMessage()
                ]);
            }

            // Bersihkan dan format data
            $cleanedPembelians = [];
            $totalKeseluruhan = 0;

            foreach ($pembelians as $pembelian) {
                // Konversi ke format yang aman
                $nomorFaktur = $pembelian->nomor_faktur ?? '';
                $namaSupplier = $pembelian->supplier ? ($pembelian->supplier->nama_supplier ?? 'Supplier') : 'Supplier';
                $statusPembayaran = $pembelian->status_pembayaran ?? '';
                
                $totalHarga = (float) ($pembelian->total_harga ?? 0);
                $totalKeseluruhan += $totalHarga;

                // Hitung total jumlah item
                $totalJumlahItem = $pembelian->pembelianDetails->sum('jumlah');

                $cleanedPembelians[] = [
                    'nomor_faktur' => $nomorFaktur,
                    'tanggal_pembelian' => $pembelian->tanggal_pembelian ? Carbon::parse($pembelian->tanggal_pembelian)->format('d/m/Y') : '-',
                    'nama_supplier' => $namaSupplier,
                    'total_harga' => $totalHarga,
                    'status_pembayaran' => $statusPembayaran,
                    'jumlah_item' => $totalJumlahItem
                ];
            }

            // Data untuk view
            $viewData = [
                'pembelians' => $cleanedPembelians,
                'tanggal' => date('d/m/Y'),
                'dari_tanggal' => $dariTanggalFormatted,
                'sampai_tanggal' => $sampaiTanggalFormatted,
                'total_keseluruhan' => $totalKeseluruhan,
                'total_transaksi' => count($cleanedPembelians)
            ];

            // Generate PDF
            $pdf = PDF::loadView('exports.pembelian-safe-pdf', $viewData);
            $pdf->setPaper('A4', 'portrait');
            
            // Simpan ke temporary file
            $tempFileName = 'temp_' . uniqid() . '.pdf';
            $tempPath = storage_path('app/temp/' . $tempFileName);
            
            // Pastikan direktori temp ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            // Simpan PDF ke file temporary
            file_put_contents($tempPath, $pdf->output());
            
            // Download file dan hapus setelah download
            return response()->download($tempPath, $filename . '.pdf')->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Temp PDF Export Error', [
                'message' => 'PDF export failed',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'error' => $e->getMessage()
            ]);

            // Return error
            abort(500, 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }
}