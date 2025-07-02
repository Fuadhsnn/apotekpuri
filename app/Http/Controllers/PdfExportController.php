<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Exports\PenjualanStreamPDFExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PdfExportController extends Controller
{
    public function exportPenjualan(Request $request)
    {
        try {
            // Ambil parameter filter dari request
            $dariTanggal = $request->get('dari_tanggal');
            $sampaiTanggal = $request->get('sampai_tanggal');
            
            // Simpan ke session untuk digunakan di export
            if ($dariTanggal) {
                Session::put('filter_dari_tanggal', $dariTanggal);
            }
            if ($sampaiTanggal) {
                Session::put('filter_sampai_tanggal', $sampaiTanggal);
            }
            
            // Buat query
            $query = Penjualan::query();
            
            // Export PDF
            $export = new PenjualanStreamPDFExport($query);
            return $export->download('laporan-penjualan-' . date('Y-m-d'));
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengexport PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}