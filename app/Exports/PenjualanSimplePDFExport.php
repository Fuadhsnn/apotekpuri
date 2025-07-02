<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanSimplePDFExport
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
            $penjualans = $query->with(['penjualanDetails.obat', 'user'])->get();

            // Konversi data tanggal ke format yang benar
            $dariTanggalFormatted = $dariTanggal ? Carbon::parse($dariTanggal)->format('d/m/Y') : null;
            $sampaiTanggalFormatted = $sampaiTanggal ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : null;

            // Bersihkan dan format data dengan cara yang lebih sederhana
            $cleanedPenjualans = [];
            $totalKeseluruhan = 0;

            foreach ($penjualans as $penjualan) {
                // Bersihkan string dengan cara yang lebih sederhana
                $nomorNota = $this->cleanString($penjualan->nomor_nota ?? '');
                $namaPelanggan = $this->cleanString($penjualan->nama_pelanggan ?? 'Pelanggan');
                $metodePembayaran = $this->cleanString($penjualan->metode_pembayaran ?? '');
                
                $totalHarga = (float) ($penjualan->total_harga ?? 0);
                $totalKeseluruhan += $totalHarga;

                // Hitung total jumlah item (sum dari kolom jumlah di penjualan_details)
                $totalJumlahItem = 0;
                if ($penjualan->penjualanDetails) {
                    foreach ($penjualan->penjualanDetails as $detail) {
                        $totalJumlahItem += (int) ($detail->jumlah ?? 0);
                    }
                }

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

            // Render view ke HTML menggunakan DomPDF
            $pdf = PDF::loadView('exports.penjualan-simple-pdf', $viewData);
            
            // Konfigurasi PDF yang lebih sederhana
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 96,
            ]);
            
            // Download PDF
            return $pdf->download($filename . '.pdf');

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Simple PDF Export Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Bersihkan pesan error dari karakter UTF-8 yang bermasalah
            $cleanErrorMessage = $this->cleanString($e->getMessage());
            
            // Return error
            abort(500, 'Gagal mengexport PDF: ' . $cleanErrorMessage);
        }
    }

    /**
     * Membersihkan string dengan cara yang sederhana
     */
    private function cleanString($string)
    {
        if ($string === null) {
            return '';
        }

        // Konversi ke string jika bukan string
        $string = (string) $string;

        // Hapus karakter yang bermasalah
        $string = preg_replace('/[^\x20-\x7E\x{00A0}-\x{00FF}]/u', '', $string);
        
        // Trim whitespace
        $string = trim($string);

        return $string;
    }
}