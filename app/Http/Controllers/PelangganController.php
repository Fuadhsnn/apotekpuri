<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        return view('pelanggan.index');
    }

    public function getObat()
    {
        try {
            $obat = Obat::select([
                'id',
                'kode_obat',
                'nama_obat',
                'deskripsi',
                'kategori',
                'stok',
                'harga_jual as harga',
                'tanggal_kadaluarsa',
                'gambar'
            ])->get();
            
            // Clean data untuk memastikan tidak ada karakter UTF-8 yang bermasalah
            $cleanedObat = $obat->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_obat' => clean_utf8($item->kode_obat ?? ''),
                    'nama_obat' => clean_utf8($item->nama_obat ?? ''),
                    'deskripsi' => clean_utf8($item->deskripsi ?? ''),
                    'kategori' => clean_utf8($item->kategori ?? ''),
                    'stok' => (int) $item->stok,
                    'harga' => (float) $item->harga,
                    'tanggal_kadaluarsa' => $item->tanggal_kadaluarsa,
                    'gambar' => $item->gambar
                ];
            });
            
            return safe_json_response($cleanedObat);
            
        } catch (\Exception $e) {
            return safe_json_response([
                'error' => 'Gagal memuat data obat',
                'message' => clean_utf8($e->getMessage())
            ], 500);
        }
    }

    public function show($id)
    {
        $obat = Obat::findOrFail($id);
        return view('pelanggan.detail', compact('obat'));
    }
}
