<?php

namespace App\Exports;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianDomPDFExport
{
    protected $query;

    public function __construct($query = null)
    {   
        $this->query = $query ?: Pembelian::query();
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
        
        // Konversi ke UTF-8 dan hapus karakter yang tidak valid
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        
        // Mengganti karakter yang mungkin menyebabkan masalah
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);
        
        // Mengganti karakter non-printable dengan spasi
        $string = preg_replace('/[\p{C}]/u', ' ', $string);
        
        // Menghapus karakter UTF-8 yang tidak valid dengan pendekatan lain
        $regex = <<<'END'
/
  [\x00-\x7F]++                      # ASCII
| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
/x
END;
        
        // Menghapus karakter UTF-8 yang tidak valid
        $string = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', ' ', $string);
        
        // Konversi ke HTML entities untuk menghindari masalah encoding
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
        
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
        
        $pembelians = $query->with('pembelianDetails.obat', 'supplier')->get();
        
        // Bersihkan data dari karakter UTF-8 yang tidak valid
        $pembelians = $this->cleanUtf8Data($pembelians);
        
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
        
        // Pastikan data yang dikirim ke view sudah dalam format yang benar
        $viewData = [
            'pembelians' => $pembelians,
            'tanggal' => date('d/m/Y'),
            'dari_tanggal' => $dariTanggalFormatted,
            'sampai_tanggal' => $sampaiTanggalFormatted,
        ];
        
        // Pastikan semua data pembelian memiliki format tanggal yang benar dan tidak ada karakter UTF-8 yang tidak valid
        foreach ($pembelians as $pembelian) {
            if ($pembelian->tanggal_pembelian && !($pembelian->tanggal_pembelian instanceof Carbon)) {
                $pembelian->tanggal_pembelian = Carbon::parse($pembelian->tanggal_pembelian);
            }
            
            // Pastikan nomor faktur tidak memiliki karakter UTF-8 yang tidak valid
            if (isset($pembelian->nomor_faktur)) {
                $pembelian->nomor_faktur = $this->cleanUtf8($pembelian->nomor_faktur);
            }
            
            // Pastikan status pembayaran tidak memiliki karakter UTF-8 yang tidak valid
            if (isset($pembelian->status_pembayaran)) {
                $pembelian->status_pembayaran = $this->cleanUtf8($pembelian->status_pembayaran);
            }
            
            // Pastikan data supplier tidak memiliki karakter UTF-8 yang tidak valid
            if ($pembelian->supplier && isset($pembelian->supplier->nama_supplier)) {
                $pembelian->supplier->nama_supplier = $this->cleanUtf8($pembelian->supplier->nama_supplier);
            }
            
            // Pastikan detail pembelian tidak memiliki karakter UTF-8 yang tidak valid
            if ($pembelian->pembelianDetails) {
                foreach ($pembelian->pembelianDetails as $detail) {
                    if ($detail->obat && isset($detail->obat->nama_obat)) {
                        $detail->obat->nama_obat = $this->cleanUtf8($detail->obat->nama_obat);
                    }
                }
            }
        }
        
        // Render view ke HTML menggunakan DomPDF
        $pdf = PDF::loadView('exports.pembelian-pdf', $viewData);
        
        // Konfigurasi PDF dengan pengaturan yang lebih baik untuk menangani UTF-8
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => false,
            'isFontSubsettingEnabled' => true,
            'defaultMediaType' => 'print',
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'portrait',
            'dpi' => 96,
            'fontHeightRatio' => 1.1,
        ]);
        
        // Download PDF
        return $pdf->download($filename . '.pdf');
    }
}