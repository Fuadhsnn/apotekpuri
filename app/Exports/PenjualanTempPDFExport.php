<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Helpers\PenjualanHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanTempPDFExport
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