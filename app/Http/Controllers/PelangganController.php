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
        return response()->json($obat);
    }

    public function show($id)
    {
        $obat = Obat::findOrFail($id);
        return view('pelanggan.detail', compact('obat'));
    }
}
