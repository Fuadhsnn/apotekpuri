<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Apotek</title>
    <style>
        :root {
            --primary: #4CAF50;
            --secondary: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
            --dark: #343a40;
            --light: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }

        .header {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            color: var(--primary);
            font-size: 24px;
        }

        .order-info {
            display: flex;
            gap: 15px;
        }

        .order-type {
            background-color: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .customer-btn {
            background-color: #2196F3;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .products-section {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            padding-left: 40px;
        }

        .search-bar::before {
            content: "🔍";
            position: absolute;
            left: 15px;
            top: 12px;
            font-size: 16px;
        }

        .category-tabs {
            display: flex;
            overflow-x: auto;
            margin-bottom: 20px;
            gap: 10px;
            padding-bottom: 5px;
        }

        .category-tab {
            padding: 8px 15px;
            background-color: var(--secondary);
            border-radius: var(--border-radius);
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
            transition: all 0.2s;
        }

        .category-tab.active {
            background-color: var(--primary);
            color: white;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .product-card {
            background-color: white;
            border: 1px solid #eee;
            border-radius: var(--border-radius);
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-tab {
            background-color: var(--secondary);
            border-radius: var(--border-radius);
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .category-tab:hover {
            background-color: var(--primary);
            color: white;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            margin: auto;
        }

        .product-image-placeholder {
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .product-name {
            font-weight: 600;
            margin: 5px 0;
            font-size: 14px;
            text-align: center;
            width: 100%;
        }

        .product-price {
            color: var(--primary);
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            width: 100%;
        }

        .order-section {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 100px);
        }

        .order-items {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item-info {
            display: flex;
            flex-direction: column;
            width: 60%;
        }

        .order-item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .order-item-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .qty-btn {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }

        .qty-btn:hover {
            background-color: #f5f5f5;
        }

        .order-item-qty {
            width: 30px;
            text-align: center;
            font-weight: bold;
        }

        .order-item-price {
            width: 80px;
            text-align: right;
            font-weight: bold;
        }

        .order-summary {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }

        .discount {
            color: var(--success);
        }

        .tax {
            color: var(--dark);
        }

        .checkout-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.2s;
        }

        .checkout-btn:hover {
            background-color: #3e8e41;
        }

        .item-count {
            color: #777;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: right;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .order-section {
                height: auto;
            }
        }

        .tab-button {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            background-color: #f8f9fa;
        }

        .tab-button.active {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .tab-content {
            margin-top: 20px;
        }

        .tab-content.hidden {
            display: none;
        }

        .resep-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .resep-obat-card {
            transition: all 0.3s ease;
        }

        .resep-obat-card:hover {
            transform: translateY(-2px);
        }

        #resepObatGrid {
            max-height: 600px;
            overflow-y: auto;
            padding: 1rem;
        }

        /* Profile dropdown styles */
        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            width: 12rem;
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-button {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.375rem;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .profile-button:hover {
            background-color: #f3f4f6;
        }

        .profile-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .profile-info {
            text-align: right;
        }

        .profile-name {
            font-weight: 600;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #1f2937;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

        .dropdown-divider {
            border-top: 1px solid #e5e7eb;
            margin: 0.25rem 0;
        }

        .logout-btn {
            color: #ef4444;
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="flex justify-between items-center w-full">
                <h1>Apotek Puri Pasir Putih</h1>
                <div class="relative">
                    <button id="profileButton" class="profile-button">
                        <div class="profile-avatar">A</div>
                        <div class="profile-info">
                            <div class="profile-name">Admin</div>
                            <div class="profile-role">Kasir</div>
                        </div>
                    </button>
                    <div id="profileDropdown" class="profile-dropdown">
                        <div class="px-4 py-3">
                            <div class="profile-name">Admin</div>
                            <div class="profile-role">admin@apotek.com</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <button class="logout-btn">Logout</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search medicines...">
            </div>

            <div class="category-tabs">
                <div class="tab-button active" data-tab="obat-biasa">Obat Biasa</div>
                <div class="tab-button" data-tab="resep-dokter">Resep Dokter</div>
                <div class="tab-button" data-tab="obat-racikan">Obat Racikan</div>
            </div>

            <div class="tab-content" id="obat-biasa">
                <div class="category-tabs">
                    <div class="category-tab active">All</div>
                    <div class="category-tab">Tablet</div>
                    <div class="category-tab">Kapsul</div>
                    <div class="category-tab">Sirup</div>
                    <div class="category-tab">Strip</div>
                    <div class="category-tab">Botol</div>
                    <div class="category-tab">Box</div>
                </div>

                <div class="product-grid" id="productGrid">
                    <!-- Product cards will be inserted here by JavaScript -->
                </div>
            </div>

            <div class="tab-content hidden" id="resep-dokter">
                <div class="resep-form">
                    <h2 class="text-xl font-bold mb-4">Input Resep Dokter</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                            <input type="text" id="namaPasien"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nama pasien">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                            <input type="text" id="namaDokter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nama dokter">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Resep</label>
                            <input type="text" id="nomorResep"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Nomor resep">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Resep</label>
                            <input type="date" id="tanggalResep"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="mb-4">
                        <input type="text" id="searchResep" class="w-full rounded-md border-gray-300 shadow-sm"
                            placeholder="Cari obat di resep...">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="resepObatGrid">
                        <!-- Resep obat cards will be inserted here by JavaScript -->
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
                                    <select class="flex-1 rounded-md border-gray-300 shadow-sm" id="obat-select">
                                        <option value="">Pilih obat</option>
                                        <!-- Options will be added by JavaScript -->
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
        // Sample data for medicines
        const medicines = [{
                id: 1,
                name: "Paracetamol 500mg",
                price: 5000,
                category: "tablet"
            },
            {
                id: 2,
                name: "Amoxicillin 500mg",
                price: 7500,
                category: "kapsul"
            },
            {
                id: 3,
                name: "OBH Combi",
                price: 25000,
                category: "sirup"
            },
            {
                id: 4,
                name: "Antangin JRG",
                price: 15000,
                category: "sachet"
            },
            {
                id: 5,
                name: "Diapet",
                price: 10000,
                category: "tablet"
            },
            {
                id: 6,
                name: "Promag",
                price: 12000,
                category: "strip"
            },
            {
                id: 7,
                name: "Betadine",
                price: 35000,
                category: "botol"
            },
            {
                id: 8,
                name: "Cap Kaki Tiga",
                price: 18000,
                category: "botol"
            },
            {
                id: 9,
                name: "Vitamin C 500mg",
                price: 20000,
                category: "tablet"
            },
            {
                id: 10,
                name: "Laserin",
                price: 15000,
                category: "box"
            },
            {
                id: 11,
                name: "Decolsin",
                price: 12000,
                category: "strip"
            },
            {
                id: 12,
                name: "Tolak Angin",
                price: 10000,
                category: "sachet"
            }
        ];

        // Current order items
        const orderItems = [];

        // DOM Elements
        const productGrid = document.getElementById('productGrid');
        const orderItemsContainer = document.getElementById('order-items');
        const itemCountElement = document.getElementById('item-count');
        const subtotalElement = document.getElementById('subtotal');
        const discountElement = document.getElementById('discount');
        const taxElement = document.getElementById('tax');
        const roundingElement = document.getElementById('rounding');
        const totalElement = document.getElementById('total');
        const checkoutBtn = document.getElementById('checkout-btn');
        const searchInput = document.getElementById('searchInput');
        const categoryTabs = document.querySelectorAll('.category-tab');
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const resepObatGrid = document.getElementById('resepObatGrid');
        const obatSelect = document.getElementById('obat-select');
        const tambahObatBtn = document.getElementById('tambah-obat');
        const komposisiContainer = document.getElementById('komposisi-container');
        const tambahKeKeranjangBtn = document.getElementById('tambah-ke-keranjang');
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            renderProducts();
            renderResepObat();
            populateObatSelect();
            setupEventListeners();
            updateOrderSummary();
        });

        // Render products to the grid
        function renderProducts(filteredMedicines = medicines) {
            productGrid.innerHTML = '';
            filteredMedicines.forEach(medicine => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.setAttribute('data-id', medicine.id);
                productCard.setAttribute('data-name', medicine.name);
                productCard.setAttribute('data-price', medicine.price);
                productCard.setAttribute('data-category', medicine.category);
                productCard.innerHTML = `
                    <div class="product-image">
                        <div class="product-image-placeholder">💊</div>
                    </div>
                    <div class="product-name">${medicine.name}</div>
                    <div class="product-price">Rp ${formatCurrency(medicine.price)}</div>
                `;
                productGrid.appendChild(productCard);
            });
        }

        // Render resep obat
        function renderResepObat() {
            resepObatGrid.innerHTML = '';
            medicines.forEach(medicine => {
                const resepCard = document.createElement('div');
                resepCard.className =
                    'resep-obat-card bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow';
                resepCard.setAttribute('data-id', medicine.id);
                resepCard.setAttribute('data-name', medicine.name);
                resepCard.setAttribute('data-price', medicine.price);
                resepCard.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-2xl">💊</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">${medicine.name}</h3>
                            <p class="text-sm text-gray-500">${medicine.category}</p>
                            <p class="text-sm font-medium text-gray-900">Rp ${formatCurrency(medicine.price)}</p>
                        </div>
                        <div>
                            <input type="number" class="resep-qty w-20 rounded-md border-gray-300 shadow-sm" min="1" value="1">
                        </div>
                    </div>
                    <button class="mt-3 w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors add-resep-btn">
                        Tambah ke Resep
                    </button>
                `;
                resepObatGrid.appendChild(resepCard);
            });
        }

        // Populate obat select for racikan
        function populateObatSelect() {
            medicines.forEach(medicine => {
                const option = document.createElement('option');
                option.value = medicine.id;
                option.textContent = medicine.name;
                option.setAttribute('data-price', medicine.price);
                obatSelect.appendChild(option);
            });
        }

        // Setup event listeners
        function setupEventListeners() {
            // Product card click
            productGrid.addEventListener('click', function(e) {
                const productCard = e.target.closest('.product-card');
                if (productCard) {
                    const name = productCard.getAttribute('data-name');
                    const price = parseInt(productCard.getAttribute('data-price'));
                    addToOrder(name, price);
                }
            });

            // Search input
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const filtered = medicines.filter(medicine =>
                    medicine.name.toLowerCase().includes(query)
                );
                renderProducts(filtered);
            });

            // Category tabs
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    categoryTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.textContent.toLowerCase();
                    if (category === 'all') {
                        renderProducts(medicines);
                        return;
                    }

                    const filtered = medicines.filter(medicine =>
                        medicine.category === category
                    );
                    renderProducts(filtered);
                });
            });

            // Tab switching
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.add('hidden'));

                    this.classList.add('active');
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            // Add resep obat
            resepObatGrid.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-resep-btn')) {
                    const card = e.target.closest('.resep-obat-card');
                    const name = card.getAttribute('data-name');
                    const price = parseInt(card.getAttribute('data-price'));
                    const quantity = parseInt(card.querySelector('.resep-qty').value) || 1;

                    addToOrder(name, price, quantity);
                }
            });

            // Tambah obat racikan
            tambahObatBtn.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'flex items-center space-x-2';
                newRow.innerHTML = `
                    <select class="flex-1 rounded-md border-gray-300 shadow-sm">
                        ${obatSelect.innerHTML}
                    </select>
                    <input type="number" class="w-20 rounded-md border-gray-300 shadow-sm" min="1" value="1">
                    <button type="button" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md" onclick="this.closest('div').remove()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                komposisiContainer.appendChild(newRow);
            });

            // Tambah racikan ke keranjang
            tambahKeKeranjangBtn.addEventListener('click', function() {
                const namaRacikan = document.getElementById('nama-racikan').value;
                const aturanPakai = document.getElementById('aturan-pakai').value;

                if (!namaRacikan || !aturanPakai) {
                    alert('Mohon isi nama racikan dan aturan pakai!');
                    return;
                }

                const komposisi = [];
                let totalHarga = 0;

                document.querySelectorAll('#komposisi-container > div').forEach(row => {
                    const select = row.querySelector('select');
                    const quantity = parseInt(row.querySelector('input[type="number"]').value);
                    const option = select.options[select.selectedIndex];

                    if (option.value) {
                        const harga = parseInt(option.getAttribute('data-price'));
                        totalHarga += harga * quantity;
                        komposisi.push({
                            id: option.value,
                            nama: option.text,
                            jumlah: quantity,
                            harga: harga
                        });
                    }
                });

                if (komposisi.length === 0) {
                    alert('Mohon tambahkan minimal satu obat!');
                    return;
                }

                addToOrder(namaRacikan, totalHarga, 1, true, aturanPakai, komposisi);

                // Reset form
                document.getElementById('nama-racikan').value = '';
                document.getElementById('aturan-pakai').value = '';
                komposisiContainer.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <select class="flex-1 rounded-md border-gray-300 shadow-sm">
                            ${obatSelect.innerHTML}
                        </select>
                        <input type="number" class="w-20 rounded-md border-gray-300 shadow-sm" min="1" value="1">
                        <button type="button" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md" onclick="this.closest('div').remove()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
            });

            // Checkout button
            checkoutBtn.addEventListener('click', function() {
                if (orderItems.length === 0) {
                    alert('Please add items to the order first.');
                    return;
                }

                const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const discount = Math.floor(subtotal * 0.1);
                const tax = Math.floor((subtotal - discount) * 0.1);
                const rounding = Math.round((subtotal - discount + tax) / 100) * 100 - (subtotal - discount + tax);
                const finalTotal = subtotal - discount + tax + rounding;

                alert(`Payment processed successfully!\nTotal: ${formatCurrency(finalTotal)}`);

                // Reset order
                orderItems.length = 0;
                renderOrderItems();
                updateOrderSummary();
            });

            // Profile dropdown
            profileButton.addEventListener('click', function() {
                profileDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
            });

            // Logout button
            document.querySelector('.logout-btn').addEventListener('click', function() {
                alert('Logout successful!');
                profileDropdown.classList.remove('show');
            });
        }

        // Add item to order
        function addToOrder(name, price, quantity = 1, isRacikan = false, aturanPakai = '', komposisi = []) {
            // Check if item already exists in order (except for racikan)
            if (!isRacikan) {
                const existingItemIndex = orderItems.findIndex(item =>
                    item.name === name && !item.isRacikan
                );

                if (existingItemIndex !== -1) {
                    orderItems[existingItemIndex].quantity += quantity;
                    renderOrderItems();
                    updateOrderSummary();
                    return;
                }
            }

            // Add new item to order
            orderItems.push({
                name,
                price,
                quantity,
                isRacikan,
                aturanPakai,
                komposisi
            });

            renderOrderItems();
            updateOrderSummary();
        }

        // Render order items
        function renderOrderItems() {
            if (orderItems.length === 0) {
                orderItemsContainer.innerHTML =
                    '<div style="color: #777; text-align: center; padding: 20px;">No items added yet</div>';
                return;
            }

            orderItemsContainer.innerHTML = '';

            orderItems.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'order-item';

                if (item.isRacikan) {
                    itemElement.innerHTML = `
                        <div class="order-item-info">
                            <div class="order-item-name">${item.name} (Racikan)</div>
                            <div class="text-xs text-gray-500">${item.aturanPakai}</div>
                            <div class="product-price">${formatCurrency(item.price)}</div>
                        </div>
                        <div class="order-item-controls">
                            <button class="qty-btn minus" data-index="${index}">-</button>
                            <div class="order-item-qty">${item.quantity}</div>
                            <button class="qty-btn plus" data-index="${index}">+</button>
                            <div class="order-item-price">${formatCurrency(item.price * item.quantity)}</div>
                        </div>
                    `;
                } else {
                    itemElement.innerHTML = `
                        <div class="order-item-info">
                            <div class="order-item-name">${item.name}</div>
                            <div class="product-price">${formatCurrency(item.price)}</div>
                        </div>
                        <div class="order-item-controls">
                            <button class="qty-btn minus" data-index="${index}">-</button>
                            <div class="order-item-qty">${item.quantity}</div>
                            <button class="qty-btn plus" data-index="${index}">+</button>
                            <div class="order-item-price">${formatCurrency(item.price * item.quantity)}</div>
                        </div>
                    `;
                }

                orderItemsContainer.appendChild(itemElement);
            });

            // Add event listeners to quantity buttons
            document.querySelectorAll('.qty-btn.minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (orderItems[index].quantity > 1) {
                        orderItems[index].quantity--;
                    } else {
                        orderItems.splice(index, 1);
                    }
                    renderOrderItems();
                    updateOrderSummary();
                });
            });

            document.querySelectorAll('.qty-btn.plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    orderItems[index].quantity++;
                    renderOrderItems();
                    updateOrderSummary();
                });
            });
        }

        // Update order summary
        function updateOrderSummary() {
            const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = Math.floor(subtotal * 0.1);
            const tax = Math.floor((subtotal - discount) * 0.1);
            const totalBeforeRounding = subtotal - discount + tax;
            const rounding = Math.round(totalBeforeRounding / 100) * 100 - totalBeforeRounding;
            const total = subtotal - discount + tax + rounding;

            subtotalElement.textContent = formatCurrency(subtotal);
            discountElement.textContent = '- ' + formatCurrency(discount);
            taxElement.textContent = formatCurrency(tax);
            roundingElement.textContent = formatCurrency(rounding);
            totalElement.textContent = formatCurrency(total);

            // Update item count
            const totalItems = orderItems.reduce((sum, item) => sum + item.quantity, 0);
            itemCountElement.textContent = totalItems;
        }

        // Format number to currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
    </script>
</body>

</html>
