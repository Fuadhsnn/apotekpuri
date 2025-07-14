<?php

namespace App\Exports;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PembelianSafePDFExport
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
            
            if ($dariTanggal) {
                try {
                    $dariTanggalFormatted = is_string($dariTanggal) ? Carbon::parse($dariTanggal)->format('d/m/Y') : $dariTanggal->format('d/m/Y');
                } catch (\Exception $e) {
                    \Log::error('Error formatting dari_tanggal', ['error' => $e->getMessage(), 'tanggal' => $dariTanggal]);
                    $dariTanggalFormatted = is_string($dariTanggal) ? $dariTanggal : null;
                }
            }
            
            if ($sampaiTanggal) {
                try {
                    $sampaiTanggalFormatted = is_string($sampaiTanggal) ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : $sampaiTanggal->format('d/m/Y');
                } catch (\Exception $e) {
                    \Log::error('Error formatting sampai_tanggal', ['error' => $e->getMessage(), 'tanggal' => $sampaiTanggal]);
                    $sampaiTanggalFormatted = is_string($sampaiTanggal) ? $sampaiTanggal : null;
                }
            }

            // Bersihkan dan format data
            $cleanedPembelians = [];
            $totalKeseluruhan = 0;
            $totalItems = 0;

            foreach ($pembelians as $pembelian) {
                // Sanitasi data
                $nomorFaktur = $this->sanitizeText($pembelian->nomor_faktur ?? '');
                $namaSupplier = $this->sanitizeText($pembelian->supplier->nama_supplier ?? 'Supplier');
                $statusPembayaran = $this->sanitizeText($pembelian->status_pembayaran ?? '');
                
                $totalHarga = (float) ($pembelian->total_harga ?? 0);
                $totalKeseluruhan += $totalHarga;

                // Hitung jumlah item
                $jumlahItem = $pembelian->pembelianDetails->count();
                $totalItems += $jumlahItem;

                $cleanedPembelians[] = [
                    'nomor_faktur' => $nomorFaktur,
                    'tanggal_pembelian' => $pembelian->tanggal_pembelian ? Carbon::parse($pembelian->tanggal_pembelian)->format('d/m/Y') : '-',
                    'nama_supplier' => $namaSupplier,
                    'status_pembayaran' => $statusPembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas',
                    'total_harga' => $totalHarga,
                    'jumlah_item' => $jumlahItem
                ];
            }

            // Data untuk view
            $viewData = [
                'pembelians' => $cleanedPembelians,
                'tanggal' => date('d/m/Y'),
                'dari_tanggal' => $dariTanggalFormatted,
                'sampai_tanggal' => $sampaiTanggalFormatted,
                'total_keseluruhan' => $totalKeseluruhan,
                'total_transaksi' => count($cleanedPembelians),
                'total_items' => $totalItems
            ];

            // Generate PDF
            $pdf = PDF::loadView('exports.pembelian-safe-pdf', $viewData);
            $pdf->setPaper('A4', 'portrait');
            
            // Download PDF langsung
            return $pdf->download($filename . '.pdf');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Safe PDF Export Error', [
                'message' => 'PDF export failed',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'error' => $e->getMessage()
            ]);

            // Return simple error response
            abort(500, 'Gagal mengexport PDF');
        }
    }

    /**
     * Sanitize text untuk menghindari masalah encoding
     */
    private function sanitizeText($text)
    {
        if ($text === null || $text === '') {
            return '-';
        }

        // Konversi ke string
        $text = (string) $text;

        // Hapus karakter yang bermasalah
        $text = preg_replace('/[^\x20-\x7E]/', '', $text);
        
        // Trim dan cek jika kosong
        $text = trim($text);
        
        return empty($text) ? '-' : $text;
    }
}