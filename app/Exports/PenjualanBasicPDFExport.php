<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanBasicPDFExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Penjualan::query();
    }

    public function download($filename)
    {
        try {
            // Set locale untuk memastikan format yang benar
            setlocale(LC_ALL, 'C');
            
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

            // Bersihkan dan format data dengan cara yang sangat sederhana
            $cleanedPenjualans = [];
            $totalKeseluruhan = 0;

            foreach ($penjualans as $penjualan) {
                // Konversi semua ke ASCII safe
                $nomorNota = $this->toAsciiSafe($penjualan->nomor_nota ?? '');
                $namaPelanggan = $this->toAsciiSafe($penjualan->nama_pelanggan ?? 'Pelanggan');
                $metodePembayaran = $this->toAsciiSafe($penjualan->metode_pembayaran ?? '');
                
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

            // Data untuk view - semua dalam format ASCII safe
            $viewData = [
                'penjualans' => $cleanedPenjualans,
                'tanggal' => date('d/m/Y'),
                'dari_tanggal' => $dariTanggalFormatted,
                'sampai_tanggal' => $sampaiTanggalFormatted,
                'total_keseluruhan' => $totalKeseluruhan,
                'total_transaksi' => count($cleanedPenjualans)
            ];

            // Render view ke HTML menggunakan DomPDF
            $pdf = PDF::loadView('exports.penjualan-basic-pdf', $viewData);
            
            // Konfigurasi PDF yang sangat sederhana
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => false,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 96,
            ]);
            
            // Download PDF
            return $pdf->download($filename . '.pdf');

        } catch (\Exception $e) {
            // Log error untuk debugging dengan pesan yang aman
            \Log::error('Basic PDF Export Error', [
                'message' => 'Error occurred during PDF export',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Return dengan pesan error yang aman
            abort(500, 'Gagal mengexport PDF. Silakan coba lagi.');
        }
    }

    /**
     * Konversi string ke ASCII safe (hanya karakter yang aman)
     */
    private function toAsciiSafe($string)
    {
        if ($string === null) {
            return '';
        }

        // Konversi ke string
        $string = (string) $string;

        // Hapus semua karakter non-ASCII dan non-printable
        $string = preg_replace('/[^\x20-\x7E]/', '', $string);
        
        // Trim whitespace
        $string = trim($string);

        // Jika string kosong setelah pembersihan, berikan default
        if (empty($string)) {
            return '-';
        }

        return $string;
    }
}