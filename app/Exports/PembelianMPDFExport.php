<?php

namespace App\Exports;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Mpdf\Mpdf;

class PembelianMPDFExport
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

        $string = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', ' ', $string);
        
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
        $dariTanggalFormatted = $dariTanggal ? (is_string($dariTanggal) ? Carbon::parse($dariTanggal)->format('d/m/Y') : $dariTanggal->format('d/m/Y')) : null;
        $sampaiTanggalFormatted = $sampaiTanggal ? (is_string($sampaiTanggal) ? Carbon::parse($sampaiTanggal)->format('d/m/Y') : $sampaiTanggal->format('d/m/Y')) : null;
        
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
        
        // Render view ke HTML
        $html = view('exports.pembelian-pdf', $viewData)->render();
        
        // Buat direktori temp jika belum ada
        if (!file_exists(storage_path('app/mpdf'))) {
            mkdir(storage_path('app/mpdf'), 0777, true);
        }
        
        // Konfigurasi mPDF dengan dukungan UTF-8 yang lebih baik
        $defaultConfig = (
            new \Mpdf\Config\ConfigVariables()
        )->getDefaults();
        
        $fontDirs = $defaultConfig['fontDir'];
        
        $defaultFontConfig = (
            new \Mpdf\Config\FontVariables()
        )->getDefaults();
        
        $fontData = $defaultFontConfig['fontdata'];
        
        // Buat instance mPDF dengan konfigurasi UTF-8 yang ditingkatkan
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'fontDir' => array_merge($fontDirs, [
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData,
            'default_font' => 'dejavusans', // Font dengan dukungan UTF-8 yang lebih baik
            'debug' => true,
            'tempDir' => storage_path('app/mpdf'),
            'allow_charset_conversion' => true,
            'charset_in' => 'utf-8',
            'autoLangToFont' => true, // Otomatis memilih font berdasarkan bahasa
            'autoScriptToLang' => true, // Otomatis memilih bahasa berdasarkan script
            'ignore_invalid_utf8' => true, // Mengabaikan karakter UTF-8 yang tidak valid
            'useSubstitutions' => true, // Menggunakan substitusi untuk karakter yang tidak didukung
            'biDirectional' => false, // Nonaktifkan dukungan bidirectional text untuk performa lebih baik
            'showWatermarkText' => false,
            'showWatermarkImage' => false,
            'watermark_font' => 'dejavusans',
            'watermarkTextAlpha' => 0.1,
        ]);
        
        // Set HTML spacing
        $mpdf->setHtmlVSpace([
            'p' => ['margin-top' => 2, 'margin-bottom' => 2],
        ]);
        
        try {
            // Konversi encoding dan bersihkan HTML dari karakter UTF-8 yang tidak valid
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $html);
            
            try {
                // Tambahkan HTML ke mPDF dengan penanganan error
                $mpdf->WriteHTML($html);
                
                // Output PDF sebagai download
                $pdfContent = $mpdf->Output($filename . '.pdf', \Mpdf\Output\Destination::STRING_RETURN);
                
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
            } catch (\Exception $e) {
                // Jika error terkait UTF-8, coba dengan konfigurasi yang lebih ketat
                if (strpos($e->getMessage(), 'UTF-8') !== false || 
                    strpos($e->getMessage(), 'character') !== false || 
                    strpos($e->getMessage(), 'encoding') !== false ||
                    strpos($e->getMessage(), 'Malformed') !== false) {
                    
                    \Illuminate\Support\Facades\Log::warning('Mencoba render ulang PDF dengan konfigurasi UTF-8 yang lebih ketat: ' . $e->getMessage());
                    
                    // Reset mPDF instance dengan konfigurasi yang lebih ketat
                    $mpdf = new Mpdf([
                        'mode' => 'utf-8',
                        'format' => 'A4',
                        'default_font' => 'dejavusans',
                        'tempDir' => storage_path('app/mpdf'),
                        'ignore_invalid_utf8' => true,
                        'useSubstitutions' => true,
                        'simpleTables' => true, // Gunakan tabel sederhana untuk menghindari masalah kompleks
                    ]);
                    
                    // Bersihkan HTML dengan lebih agresif
                    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
                    $html = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $html);
                    $html = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}<>\/="\'\-_:;.,()\[\]{}#@!?*+&^%$|]/u', ' ', $html);
                    
                    // Coba render ulang
                    $mpdf->WriteHTML($html);
                    $pdfContent = $mpdf->Output($filename . '.pdf', \Mpdf\Output\Destination::STRING_RETURN);
                    
                    return response($pdfContent)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
                }
                
                // Jika bukan error UTF-8, lempar exception
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Error generating PDF: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            
            // Tampilkan pesan error yang lebih informatif
            abort(500, 'Error generating PDF: ' . $e->getMessage());
        }
    }
}