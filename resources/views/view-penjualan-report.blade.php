<x-filament::page>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Detail Transaksi Penjualan</h2>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">
                    No. Nota: {{ $record->nomor_nota }}
                </span>
                <span class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">
                    Tanggal: {{ $record->tanggal_penjualan->format('d/m/Y') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-500 mb-2">Informasi Transaksi</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Pelanggan:</span> {{ $record->nama_pelanggan ?? 'Tidak dicatat' }}</p>
                    <p><span class="font-medium">Total:</span> {{ number_format($record->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-500 mb-2">Pembayaran</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Dibayar:</span> {{ number_format($record->bayar, 0, ',', '.') }}</p>
                    <p><span class="font-medium">Kembalian:</span> {{ number_format($record->kembalian, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-3 border-b">
                <h3 class="font-medium text-gray-700">Daftar Obat</h3>
            </div>
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>