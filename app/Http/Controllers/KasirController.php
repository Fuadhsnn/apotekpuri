<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return safe_json_response($obat);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        $obats = Obat::where('nama_obat', 'LIKE', "%{$query}%")
            ->orWhere('kode_obat', 'LIKE', "%{$query}%")
            ->orWhere('kategori', 'LIKE', "%{$query}%")
            ->get();

        return safe_json_response($obats);
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'customerName' => 'nullable|string|max:255', // Ubah dari required menjadi nullable
                'doctorName' => 'nullable|string|max:255', // Nama dokter (opsional)
                'prescriptionNumber' => 'nullable|string|max:255', // Nomor resep (opsional)
                'paymentMethod' => 'required|string|in:Tunai,QRIS',
                'amountReceived' => 'required_if:paymentMethod,Tunai|numeric|min:0', // Hanya wajib jika metode pembayaran Tunai
                'orderItems' => 'required|array',
                'orderItems.*.id' => 'nullable',  // Ubah dari required|exists menjadi nullable
                'orderItems.*.quantity' => 'required|integer|min:1',
                'orderItems.*.price' => 'required|numeric|min:0',
            ]);

            // Generate nomor nota
            $nomorNota = 'PJ-' . now()->format('Ymd-His');

            // Buat record penjualan
            $penjualan = Penjualan::create([
                'nomor_nota' => $nomorNota,
                'tanggal_penjualan' => now(),
                'total_harga' => collect($validated['orderItems'])->sum(fn($item) => $item['price'] * $item['quantity']),
                'metode_pembayaran' => $validated['paymentMethod'],
                'nama_pelanggan' => $validated['customerName'],
                'nama_dokter' => $validated['doctorName'] ?? null,
                'nomor_resep' => $validated['prescriptionNumber'] ?? null,
                'user_id' => auth()->id(),
                'bayar' => $validated['amountReceived'],
                'kembalian' => $validated['amountReceived'] - collect($validated['orderItems'])->sum(fn($item) => $item['price'] * $item['quantity']),
            ]);


            // Proses item penjualan
            foreach ($validated['orderItems'] as $item) {
                // Jika item memiliki ID obat (bukan racikan)
                if (!empty($item['id'])) {
                    $obat = Obat::findOrFail($item['id']);
                    if ($obat->stok < $item['quantity']) {
                        throw new \Exception("Stok tidak mencukupi untuk obat {$obat->nama_obat}");
                    }

                    PenjualanDetail::create([
                        'penjualan_id' => $penjualan->id,
                        'obat_id' => $item['id'],
                        'jumlah' => $item['quantity'],
                        'harga' => $item['price'],
                        'harga_jual' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Update stok obat
                    $obat->decrement('stok', $item['quantity']);
                } else {
                    // Untuk obat racikan atau resep dokter (tanpa ID obat)
                    PenjualanDetail::create([
                        'penjualan_id' => $penjualan->id,
                        'obat_id' => null,  // Tidak ada obat_id
                        'jumlah' => $item['quantity'],
                        'harga' => $item['price'],
                        'harga_jual' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'keterangan' => $item['name'] ?? 'Obat Racikan/Resep',  // Simpan nama racikan sebagai keterangan
                    ]);
                }
            }

            DB::commit();
            return safe_json_response([
                'success' => true, 
                'message' => 'Transaksi berhasil diproses',
                'penjualan_id' => $penjualan->id,
                'nomor_nota' => $nomorNota
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return safe_json_response([
                'success' => false, 
                'message' => clean_utf8($e->getMessage())
            ], 500);
        }
    }
    
    public function printStruk($id)
    {
        $penjualan = Penjualan::with('penjualanDetails.obat', 'user')->findOrFail($id);
        return view('kasir.struk', compact('penjualan'));
    }
}
