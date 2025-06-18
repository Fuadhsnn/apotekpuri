<x-filament::page>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Detail Transaksi Pembelian</h2>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">
                    No. Faktur: {{ $record->nomor_faktur }}
                </span>
                <span class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">
                    Tanggal: {{ $record->tanggal_pembelian->format('d/m/Y') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-500 mb-2">Informasi Transaksi</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Supplier:</span> {{ $record->supplier->nama_supplier }}</p>
                    <p><span class="font-medium">Total:</span> {{ number_format($record->total_harga, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-500 mb-2">Status Pembayaran</h3>
                <div class="space-y-2">
                    <span @class([
                        'px-3 py-1 rounded-full text-sm font-medium',
                        'bg-green-100 text-green-800' => $record->status_pembayaran === 'lunas',
                        'bg-red-100 text-red-800' => $record->status_pembayaran === 'belum_lunas',
                    ])>
                        {{ $record->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
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
