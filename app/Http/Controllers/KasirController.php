<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('kasir.index', compact('obats'));
    }

    public function getObat($id)
    {
        $obat = Obat::find($id);
        return response()->json($obat);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $obats = Obat::where('nama_obat', 'LIKE', "%{$query}%")
                     ->orWhere('kode_obat', 'LIKE', "%{$query}%")
                     ->orWhere('kategori', 'LIKE', "%{$query}%")
                     ->get();
                     
        return response()->json($obats);
    }
}