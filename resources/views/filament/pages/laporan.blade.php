<x-filament-panels::page>
    <x-filament::card>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900">Total Penjualan</h3>
                <p class="mt-2 text-3xl font-bold text-primary-600">Rp {{ number_format($this->total_penjualan, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900">Jumlah Transaksi</h3>
                <p class="mt-2 text-3xl font-bold text-primary-600">{{ $this->jumlah_transaksi }}</p>
            </div>
        </div>
    </x-filament::card>

    {{ $this->table }}
</x-filament-panels::page>
