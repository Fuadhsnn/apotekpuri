<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Apotek</title>
    <link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="flex justify-between items-center w-full">
                <h1>Apotek Cashier System</h1>
                <div class="relative ml-auto"> <!-- Tambahkan ml-auto di sini -->
                    <button id="profileButton" class="flex items-center space-x-3 focus:outline-none hover:bg-gray-100 rounded-lg p-2">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-right pr-2"> <!-- Tambahkan padding kanan -->
                            <div class="font-bold">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-600">{{ auth()->user()->role }}</div>
                        </div>
                    </button>
                    
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border">
                        <div class="px-4 py-3 border-b">
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="py-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <div class="search-bar">
                <input type="text" placeholder="Search medicines...">
            </div>

            <div class="category-tabs">
                <div class="category-tab active">All</div>
                @foreach (['tablet', 'kapsul', 'sirup', 'strip', 'botol', 'box'] as $kategori)
                    <div class="category-tab">{{ ucfirst($kategori) }}</div>
                @endforeach
            </div>

            <div class="product-grid">
                @foreach ($obats as $obat)
                    <div class="product-card" 
                        data-id="{{ $obat->id }}" 
                        data-name="{{ $obat->nama_obat }}"
                        data-price="{{ $obat->harga_jual }}"
                        data-category="{{ strtolower($obat->kategori) }}">
                        <div class="product-image">
                            @if ($obat->gambar)
                                <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nama_obat }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="product-image-placeholder">💊</div>
                            @endif
                        </div>
                        <div class="product-name">{{ $obat->nama_obat }}</div>
                        <div class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="order-section">
            <h2>Current Order</h2>

            <div class="order-items" id="order-items">
                <div style="color: #777; text-align: center; padding: 20px;">No items added yet</div>
            </div>

            <div class="item-count">Items count: <span id="item-count">0</span></div>

            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">Rp 0</span>
                </div>

                <div class="summary-row discount">
                    <span>Discount (10%):</span>
                    <span id="discount">- Rp 0</span>
                </div>

                <div class="summary-row tax">
                    <span>Tax (PPN 10%):</span>
                    <span id="tax">Rp 0</span>
                </div>

                <div class="summary-row">
                    <span>Rounding:</span>
                    <span id="rounding">Rp 0</span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="total">Rp 0</span>
                </div>

                <button class="checkout-btn" id="checkout-btn">Process Payment</button>
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
