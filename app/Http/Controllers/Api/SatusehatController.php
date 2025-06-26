<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Services\SatusehatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SatusehatController extends Controller
{
    protected $satusehatService;

    public function __construct(SatusehatService $satusehatService)
    {
        $this->satusehatService = $satusehatService;
    }

    /**
     * Mencari obat dari API SATUSEHAT
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMedications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:100',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $search = $request->input('search');
        $limit = $request->input('limit', 10);

        $medications = $this->satusehatService->getMedicationList($search, $limit);

        if ($medications === null) {
            return response()->json(['error' => 'Gagal mendapatkan data dari SATUSEHAT API'], 500);
        }

        return response()->json($medications);
    }

    /**
     * Mendapatkan detail obat dari API SATUSEHAT
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicationDetail(Request $request, $id)
    {
        $medication = $this->satusehatService->getMedicationDetail($id);

        if ($medication === null) {
            return response()->json(['error' => 'Gagal mendapatkan detail obat dari SATUSEHAT API'], 500);
        }

        return response()->json($medication);
    }

    /**
     * Menambahkan obat dari SATUSEHAT ke database lokal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importMedication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medication_id' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'required|date|after:today',
            'kategori' => 'required|string|in:antibiotik,vitamin,analgesik,antipiretik,antihistamin,lainnya',
            'jenis_obat' => 'required|string|in:obat_bebas,obat_bebas_terbatas,obat_keras,narkotika',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Mendapatkan detail obat dari SATUSEHAT
        $medicationId = $request->input('medication_id');
        $medicationDetail = $this->satusehatService->getMedicationDetail($medicationId);

        if ($medicationDetail === null) {
            return response()->json(['error' => 'Gagal mendapatkan detail obat dari SATUSEHAT API'], 500);
        }

        try {
            // Ekstrak informasi yang diperlukan dari response SATUSEHAT
            $nama_obat = $medicationDetail['code']['text'] ?? $medicationDetail['code']['coding'][0]['display'] ?? null;
            $deskripsi = $medicationDetail['form']['text'] ?? '';
            
            if (!$nama_obat) {
                return response()->json(['error' => 'Informasi nama obat tidak ditemukan'], 422);
            }

            // Generate kode obat
            $kategori = $request->input('kategori');
            $tahun = date('y');
            $kode_kategori = strtoupper(substr($kategori, 0, 3));
            
            // Cari nomor urut terakhir untuk kategori ini
            $lastObat = Obat::where('kode_obat', 'like', "{$kode_kategori}{$tahun}%")
                ->orderBy('kode_obat', 'desc')
                ->first();
            
            $urutan = 1;
            if ($lastObat) {
                $lastUrutan = (int) substr($lastObat->kode_obat, 5);
                $urutan = $lastUrutan + 1;
            }
            
            $kode_obat = "{$kode_kategori}{$tahun}" . str_pad($urutan, 3, '0', STR_PAD_LEFT);

            // Simpan obat ke database
            DB::beginTransaction();
            
            $obat = new Obat();
            $obat->kode_obat = $kode_obat;
            $obat->nama_obat = $nama_obat;
            $obat->deskripsi = $deskripsi;
            $obat->kategori = $request->input('kategori');
            $obat->jenis_obat = $request->input('jenis_obat');
            $obat->stok = $request->input('stok');
            $obat->harga_beli = $request->input('harga_beli');
            $obat->harga_jual = $request->input('harga_jual');
            $obat->tanggal_kadaluarsa = $request->input('tanggal_kadaluarsa');
            $obat->save();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Obat berhasil ditambahkan',
                'data' => $obat
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Medication Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data obat: ' . $e->getMessage()], 500);
        }
    }
}