<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Helpers\PenjualanHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PenjualanSafePDFExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Penjualan::query();
    }

    public function download($filename)
    {
        try {
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

            // Ambil data dengan eager loading
            $penjualans = $query->with(['penjualanDetails.obat'])->get();

            // Konversi data tanggal ke format yang benar
            $dariTanggalFormatted = $dariTanggal ? Carbon::parse($dariTanggal)->format('d/m/Y') : null;
            $sampaiTanggalFormatted = $sampaiTanggal ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : null;

            // Bersihkan dan format data
            $cleanedPenjualans = [];
            $totalKeseluruhan = 0;

            foreach ($penjualans as $penjualan) {
                // Konversi ke format yang aman menggunakan StringHelper
                $nomorNota = StringHelper::cleanUtf8($penjualan->nomor_nota ?? '');
                $namaPelanggan = StringHelper::cleanUtf8($penjualan->nama_pelanggan ?? 'Pelanggan');
                $metodePembayaran = StringHelper::cleanUtf8($penjualan->metode_pembayaran ?? '');
                
                $totalHarga = (float) ($penjualan->total_harga ?? 0);
                $totalKeseluruhan += $totalHarga;

                // Hitung total jumlah item menggunakan helper
                $totalJumlahItem = PenjualanHelper::calculateTotalItems($penjualan->penjualanDetails);

                $cleanedPenjualans[] = [
                    'nomor_nota' => $nomorNota,
                    'tanggal_penjualan' => $penjualan->tanggal_penjualan ? Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') : '-',
                    'nama_pelanggan' => $namaPelanggan,
                    'total_harga' => $totalHarga,
                    'metode_pembayaran' => $metodePembayaran,
                    'jumlah_item' => $totalJumlahItem
                ];
            }

            // Data untuk view
            $viewData = [
                'penjualans' => $cleanedPenjualans,
                'tanggal' => date('d/m/Y'),
                'dari_tanggal' => $dariTanggalFormatted,
                'sampai_tanggal' => $sampaiTanggalFormatted,
                'total_keseluruhan' => $totalKeseluruhan,
                'total_transaksi' => count($cleanedPenjualans)
            ];

            // Generate PDF
            $pdf = PDF::loadView('exports.penjualan-safe-pdf', $viewData);
            $pdf->setPaper('A4', 'portrait');
            
            // Download PDF langsung
            return $pdf->download($filename . '.pdf');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Safe PDF Export Error', [
                'message' => 'PDF export failed',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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