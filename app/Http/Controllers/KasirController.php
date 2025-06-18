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

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'customerName' => 'nullable|string|max:255', // Ubah dari required menjadi nullable
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
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
