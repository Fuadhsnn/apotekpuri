<x-filament::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight">
                Notifikasi Stok Menipis
            </h2>
            @if($lowStockObats->count() > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                    {{ $lowStockObats->count() }} obat perlu diperhatikan
                </span>
            @endif
        </div>

        @if($lowStockObats->count() > 0)
            <div class="mt-4 space-y-4" id="stock-notifications" wire:poll.10s>
                @foreach($stockNotifications as $notification)
                    @php $obat = $notification->obat; @endphp
                    @if($obat)
                        <div class="p-4 bg-warning-50 border border-warning-200 rounded-lg" id="notification-{{ $notification->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-warning-100">
                                            <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-warning-600" />
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ $obat->nama_obat }}</h3>
                                        <p class="text-sm text-gray-500">
                                            Stok tersisa: <span class="font-bold text-warning-600">{{ $obat->stok }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Kode: {{ $obat->kode_obat }} | Kategori: {{ ucfirst($obat->kategori) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="markAsRead({{ $notification->id }})"
                                        class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    >
                                        <x-heroicon-o-check class="w-4 h-4 mr-1" />
                                        Tandai Dibaca
                                    </button>
                                    <a 
                                        href="{{ route('filament.admin.resources.obats.edit', ['record' => $obat]) }}" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    >
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <script>
                document.addEventListener('notification-marked-as-read', function () {
                    // Refresh komponen setelah notifikasi ditandai sebagai dibaca
                    window.livewire.emit('$refresh');
                });
            </script>
        @else
            <div class="p-4 mt-4 bg-success-50 border border-success-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-success-100">
                            <x-heroicon-o-check-circle class="w-6 h-6 text-success-600" />
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Stok Obat Aman</h3>
                        <p class="text-sm text-gray-500">Semua obat memiliki stok yang cukup.</p>
                    </div>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament::widget>