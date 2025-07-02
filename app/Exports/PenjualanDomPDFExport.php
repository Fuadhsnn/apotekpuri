<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanDomPDFExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: Penjualan::query();
    }

    /**
     * Membersihkan string dari karakter UTF-8 yang tidak valid
     *
     * @param string $string String yang akan dibersihkan
     * @return string String yang sudah dibersihkan
     */
    protected function cleanUtf8($string)
    {
        if ($string === null) {
            return '';
        }

        // Konversi ke string jika bukan string
        if (!is_string($string)) {
            $string = (string) $string;
        }

        // Hapus BOM jika ada
        $string = str_replace("\xEF\xBB\xBF", '', $string);

        // Konversi encoding yang aman
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

        // Hapus karakter control yang tidak diinginkan
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);

        // Pastikan string valid UTF-8
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'auto');
        }

        // Trim whitespace
        $string = trim($string);

        return $string;
    }

    /**
     * Membersihkan array atau objek dari karakter UTF-8 yang tidak valid
     *
     * @param mixed $data Data yang akan dibersihkan
     * @return mixed Data yang sudah dibersihkan
     */
    protected function cleanUtf8Data($data)
    {
        if ($data === null) {
            return null;
        }

        if (is_string($data)) {
            return $this->cleanUtf8($data);
        }

        if (is_numeric($data) || is_bool($data)) {
            return $data;
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->cleanUtf8Data($value);
            }
            return $data;
        }

        if (is_object($data)) {
            // Jika objek adalah Carbon, kembalikan apa adanya
            if ($data instanceof \Carbon\Carbon) {
                return $data;
            }

            // Jika objek adalah Eloquent Collection, bersihkan setiap item
            if (method_exists($data, 'map') && method_exists($data, 'all')) {
                return $data->map(function ($item) {
                    return $this->cleanUtf8Data($item);
                });
            }

            // Jika objek adalah Eloquent Model, bersihkan atribut
            if (method_exists($data, 'getAttributes')) {
                $attributes = $data->getAttributes();
                foreach ($attributes as $key => $value) {
                    if (is_string($value)) {
                        $data->{$key} = $this->cleanUtf8($value);
                    } elseif (is_array($value) || is_object($value)) {
                        $data->{$key} = $this->cleanUtf8Data($value);
                    }
                }

                // Bersihkan relasi
                foreach ($data->getRelations() as $relationName => $relation) {
                    if ($relation !== null) {
                        $data->setRelation($relationName, $this->cleanUtf8Data($relation));
                    }
                }

                return $data;
            }

            // Objek biasa
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data->{$key} = $this->cleanUtf8($value);
                } elseif (is_array($value) || is_object($value)) {
                    $data->{$key} = $this->cleanUtf8Data($value);
                }
            }
        }

        return $data;
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
            $dariTanggalFormatted = $dariTanggal ? (is_string($dariTanggal) ? Carbon::parse($dariTanggal)->format('d/m/Y') : $dariTanggal->format('d/m/Y')) : null;
            $sampaiTanggalFormatted = $sampaiTanggal ? (is_string($sampaiTanggal) ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : $sampaiTanggal->format('d/m/Y')) : null;

            // Bersihkan dan format data
            $cleanedPenjualans = [];
            foreach ($penjualans as $penjualan) {
                $cleanedPenjualan = [
                    'id' => $penjualan->id,
                    'nomor_nota' => $this->cleanUtf8($penjualan->nomor_nota ?? ''),
                    'tanggal_penjualan' => $penjualan->tanggal_penjualan ? Carbon::parse($penjualan->tanggal_penjualan) : null,
                    'nama_pelanggan' => $this->cleanUtf8($penjualan->nama_pelanggan ?? 'Pelanggan'),
                    'total_harga' => (float) $penjualan->total_harga,
                    'metode_pembayaran' => $this->cleanUtf8($penjualan->metode_pembayaran ?? ''),
                    'bayar' => (float) $penjualan->bayar,
                    'kembalian' => (float) $penjualan->kembalian,
                    'penjualanDetails' => []
                ];

                // Bersihkan detail penjualan
                if ($penjualan->penjualanDetails) {
                    foreach ($penjualan->penjualanDetails as $detail) {
                        $jumlahItem = (int) ($detail->jumlah ?? 0);
                        
                        $cleanedDetail = [
                            'id' => $detail->id,
                            'jumlah' => $jumlahItem,
                            'harga' => (float) $detail->harga,
                            'subtotal' => (float) $detail->subtotal,
                            'obat' => null
                        ];

                        if ($detail->obat) {
                            $cleanedDetail['obat'] = [
                                'id' => $detail->obat->id,
                                'nama_obat' => $this->cleanUtf8($detail->obat->nama_obat ?? ''),
                                'kategori' => $this->cleanUtf8($detail->obat->kategori ?? ''),
                            ];
                        }

                        $cleanedPenjualan['penjualanDetails'][] = (object) $cleanedDetail;
                    }
                }

                $cleanedPenjualans[] = (object) $cleanedPenjualan;
            }

            // Pastikan data yang dikirim ke view sudah dalam format yang benar
            $viewData = [
                'penjualans' => collect($cleanedPenjualans),
                'tanggal' => date('d/m/Y'),
                'dari_tanggal' => $dariTanggalFormatted,
                'sampai_tanggal' => $sampaiTanggalFormatted,
            ];

            // Render view ke HTML menggunakan DomPDF
            $pdf = PDF::loadView('exports.penjualan-pdf', $viewData);
            
            // Konfigurasi PDF dengan pengaturan yang lebih baik untuk menangani UTF-8
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'a4',
                'defaultPaperOrientation' => 'portrait',
                'dpi' => 96,
                'fontHeightRatio' => 1.1,
                'chroot' => public_path(),
            ]);
            
            // Download PDF
            return $pdf->download($filename . '.pdf');

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('PDF Export Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Bersihkan pesan error dari karakter UTF-8 yang bermasalah
            $cleanErrorMessage = $this->cleanUtf8($e->getMessage());
            
            // Return error
            abort(500, 'Gagal mengexport PDF: ' . $cleanErrorMessage);
        }
    }
}