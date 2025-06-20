<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir - Apotek</title>
    <link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="flex justify-between items-center w-full">
                <h1>Apotek Puri Pasir Putih</h1>
                <div class="relative ml-auto"> <!-- Tambahkan ml-auto di sini -->
                    <button id="profileButton"
                        class="flex items-center space-x-3 focus:outline-none hover:bg-gray-100 rounded-lg p-2">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-right pr-2"> <!-- Tambahkan padding kanan -->
                            <div class="font-bold">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-600">{{ auth()->user()->role }}</div>
                        </div>
                    </button>

                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border">
                        <div class="px-4 py-3 border-b">
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="py-1">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Cari obat...">
            </div>

            <div class="category-tabs">
                <div class="tab-button" data-tab="obat-biasa">Obat Biasa</div>
                <div class="tab-button" data-tab="resep-dokter">Resep Dokter</div>
                <div class="tab-button" data-tab="obat-racikan">Obat Racikan</div>
            </div>

            <div class="tab-content" id="obat-biasa">


                <div class="product-grid">
                    @foreach ($obats as $obat)
                        <div class="product-card" data-id="{{ $obat->id }}" data-name="{{ $obat->nama_obat }}"
                            data-price="{{ $obat->harga_jual }}" data-category="{{ strtolower($obat->kategori) }}">
                            <div class="product-image">
                                @if ($obat->gambar)
                                    <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nama_obat }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="product-image-placeholder">💊</div>
                                @endif
                            </div>
                            <div class="product-name">{{ $obat->nama_obat }}</div>
                            <div class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                            <div class="product-stock {{ $obat->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                Stok: {{ $obat->stok }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-content hidden" id="resep-dokter">
                <div class="resep-form">
                    <h2 class="text-xl font-bold mb-4">Input Resep Dokter</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nama pasien">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nama dokter">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Resep</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nomor resep">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Resep</label>
                            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="mb-4">
                        <input type="text" id="searchResep" class="w-full rounded-md border-gray-300 shadow-sm"
                            placeholder="Cari obat di resep...">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="resepObatGrid">
                        @foreach ($obats as $obat)
                            <div class="resep-obat-card bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow"
                                data-id="{{ $obat->id }}" data-name="{{ $obat->nama_obat }}"
                                data-price="{{ $obat->harga_jual }}">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                        @if ($obat->gambar)
                                            <img src="{{ asset('storage/' . $obat->gambar) }}"
                                                alt="{{ $obat->nama_obat }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">💊
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $obat->nama_obat }}</h3>
                                        <p class="text-sm text-gray-500">{{ $obat->kategori }}</p>
                                        <p class="text-sm font-medium text-gray-900">Rp
                                            {{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                                        <p class="text-sm {{ $obat->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Stok: {{ $obat->stok }}</p>
                                    </div>
                                    <div>
                                        <input type="number"
                                            class="resep-qty w-20 rounded-md border-gray-300 shadow-sm" min="1"
                                            value="1">
                                    </div>
                                </div>
                                <button
                                    class="mt-3 w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                                    Tambah ke Resep
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tab-content hidden" id="obat-racikan">
                <div class="p-4">
                    <h2 class="text-xl font-bold mb-4">Obat Racikan</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Racikan</label>
                            <input type="text" id="nama-racikan"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Contoh: Obat Batuk Hitam">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aturan Pakai</label>
                            <input type="text" id="aturan-pakai"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Contoh: 3x1 sehari setelah makan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Komposisi Racikan</label>
                            <div id="komposisi-container" class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <select class="flex-1 rounded-md border-gray-300 shadow-sm">
                                        <option value="">Pilih obat</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}" data-price="{{ $obat->harga_jual }}"
                                                {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                                {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="w-20 rounded-md border-gray-300 shadow-sm"
                                        min="1" value="1">
                                    <button type="button"
                                        class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md"
                                        onclick="this.closest('div').remove()">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="tambah-obat"
                                class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                + Tambah Obat
                            </button>
                        </div>
                    </div>

                    <button type="button" id="tambah-ke-keranjang"
                        class="mt-6 w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors">
                        Tambahkan ke Keranjang
                    </button>
                </div>
            </div>
        </div>

        <div class="order-section">
            <h2>Current Order</h2>

            <div class="order-items" id="order-items">
                <div style="color: #777; text-align: center; padding: 20px;">No items added yet</div>
            </div>



            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">Rp 0</span>
                </div>

                <div class="payment-section mt-4">
                    <h3 class="text-lg font-semibold mb-3">Pembayaran</h3>

                    <div class="mb-3">
                        <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Nama
                            Pelanggan</label>
                        <input type="text" id="customerName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Nama pelanggan">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran:</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="paymentMethod" value="Tunai" class="form-radio"
                                    checked>
                                <span class="ml-2">Tunai</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="paymentMethod" value="QRIS" class="form-radio">
                                <span class="ml-2">QRIS</span>
                            </label>
                        </div>
                    </div>

                    <div id="cashPayment" class="mb-3">
                        <label for="amountReceived" class="block text-sm font-medium text-gray-700 mb-1">Uang
                            Diterima</label>
                        <input type="text" id="amountReceived"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Jumlah uang yang diterima">
                    </div>

                    <div id="qrisPayment" class="mb-3 hidden">
                        <div class="text-sm text-gray-700 mb-2">Silahkan scan QR code untuk melakukan pembayaran.</div>
                        <div class="bg-gray-100 p-4 rounded-md text-center">
                            <div class="text-lg font-semibold mb-2">QR Code Pembayaran</div>
                            <div class="text-sm text-gray-500">QR Code akan muncul setelah transaksi diproses</div>
                        </div>
                    </div>

                    <div id="changeAmount" class="mb-3 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kembalian:</label>
                        <div class="text-lg font-semibold text-green-600">Rp 0</div>
                    </div>
                </div>

                <button class="checkout-btn" id="checkout-btn">Proses Pembayaran</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle dropdown
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
    <script src="{{ asset('js/kasir.js') }}"></script>
</body>

</html>
