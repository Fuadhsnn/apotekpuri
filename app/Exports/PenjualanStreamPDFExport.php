<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Helpers\PenjualanHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanStreamPDFExport
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
                // Konversi ke format yang aman
                $nomorNota = StringHelper::cleanUtf8($penjualan->nomor_nota ?? '');
                $namaPelanggan = StringHelper::cleanUtf8($penjualan->nama_pelanggan ?? 'Pelanggan');
                $metodePembayaran = StringHelper::cleanUtf8($penjualan->metode_pembayaran ?? '');
                
                $totalHarga = (float) ($penjualan->total_harga ?? 0);
                $totalKeseluruhan += $totalHarga;

                // Hitung total jumlah item
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
            
            // Set headers untuk download
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];
            
            // Stream PDF langsung ke browser
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename . '.pdf', $headers);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Stream PDF Export Error', [
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