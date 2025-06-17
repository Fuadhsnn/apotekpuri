// Definisikan orderItems sebagai variabel global
let orderItems = [];

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi elemen-elemen DOM
    const productCards = document.querySelectorAll('.product-card');
    const orderItemsContainer = document.getElementById('order-items');
    const itemCountElement = document.getElementById('item-count');
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const customerBtn = document.querySelector('.customer-btn');
    const amountReceived = document.getElementById('amountReceived');
    const changeAmount = document.getElementById('changeAmount');
    const changeDisplay = changeAmount.querySelector('div');
    const paymentMethods = document.getElementsByName('paymentMethod');
    const cashPayment = document.getElementById('cashPayment');
    const qrisPayment = document.getElementById('qrisPayment');
    const searchInput = document.querySelector('.search-input');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const productGrid = document.querySelector('.product-grid');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Fungsi pencarian obat
    function searchMedicines(query) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const name = product.getAttribute('data-name').toLowerCase();
            if (name.includes(query.toLowerCase())) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Event listener untuk pencarian
    searchInput.addEventListener('input', function() {
        searchMedicines(this.value);
    });

    // Fungsi filter berdasarkan kategori
    function filterByCategory(category) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            if (category === 'All' || product.getAttribute('data-category') === category) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Event listener untuk filter kategori
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            categoryFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            filterByCategory(this.getAttribute('data-category'));
        });
    });

    // Event listener untuk tab kategori
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Hapus kelas active dari semua tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            // Tambah kelas active ke tab yang diklik
            this.classList.add('active');

            // Sembunyikan semua konten tab
            tabContents.forEach(content => content.classList.add('hidden'));
            // Tampilkan konten tab yang sesuai
            const targetId = this.getAttribute('data-category');
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Fungsi untuk menampilkan form obat racikan
    function showRacikanForm() {
        const racikanForm = document.getElementById('racikanForm');
        const regularProducts = document.getElementById('regularProducts');
        racikanForm.classList.remove('hidden');
        regularProducts.classList.add('hidden');
    }

    // Fungsi untuk menampilkan form resep dokter
    function showResepForm() {
        const resepForm = document.getElementById('resepForm');
        const regularProducts = document.getElementById('regularProducts');
        resepForm.classList.remove('hidden');
        regularProducts.classList.add('hidden');
    }

    // Fungsi untuk menyembunyikan form khusus
    function hideSpecialForms() {
        const racikanForm = document.getElementById('racikanForm');
        const resepForm = document.getElementById('resepForm');
        const regularProducts = document.getElementById('regularProducts');
        racikanForm.classList.add('hidden');
        resepForm.classList.add('hidden');
        regularProducts.classList.remove('hidden');
    }

    // Fungsi untuk menambahkan obat racikan ke keranjang
    window.addRacikanToCart = function() {
        const racikanName = document.getElementById('racikanName').value;
        const racikanPrice = parseInt(document.getElementById('racikanPrice').value) || 0;
        const racikanQuantity = parseInt(document.getElementById('racikanQuantity').value) || 1;

        if (!racikanName || racikanPrice <= 0) {
            alert('Mohon lengkapi nama dan harga obat racikan!');
            return;
        }

        addToCart(null, racikanName, racikanPrice, racikanQuantity);
        document.getElementById('racikanForm').reset();
    };

    // Fungsi untuk menambahkan resep dokter ke keranjang
    window.addResepToCart = function() {
        const resepNumber = document.getElementById('resepNumber').value;
        const resepItems = document.getElementById('resepItems').value;
        const resepPrice = parseInt(document.getElementById('resepPrice').value) || 0;

        if (!resepNumber || !resepItems || resepPrice <= 0) {
            alert('Mohon lengkapi semua detail resep dokter!');
            return;
        }

        const resepName = `Resep #${resepNumber}: ${resepItems}`;
        addToCart(null, resepName, resepPrice, 1);
        document.getElementById('resepForm').reset();
    };

    // Format number to currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Update ringkasan pesanan
    function updateOrderSummary() {
        const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        subtotalElement.textContent = formatCurrency(subtotal);
        totalElement.textContent = formatCurrency(subtotal);

        const totalItems = orderItems.reduce((sum, item) => sum + item.quantity, 0);
        itemCountElement.textContent = totalItems;
    }

    // Render item di keranjang
    function renderOrderItems() {
        if (orderItems.length === 0) {
            orderItemsContainer.innerHTML = '<div class="text-center text-gray-600 py-4">Keranjang kosong.</div>';
            return;
        }

        orderItemsContainer.innerHTML = '';
        orderItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'order-item';
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
            orderItemsContainer.appendChild(itemElement);
        });

        // Tambahkan event listener untuk tombol plus dan minus
        document.querySelectorAll('.qty-btn.minus').forEach(btn => {
            btn.addEventListener('click', function () {
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
            btn.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                orderItems[index].quantity++;
                renderOrderItems();
                updateOrderSummary();
            });
        });
    }

    // Fungsi menambahkan item ke keranjang
    window.addToCart = function (id, name, price, quantity = 1) {
        const existingItemIndex = orderItems.findIndex(item => item.name === name);
        if (existingItemIndex !== -1) {
            orderItems[existingItemIndex].quantity += quantity;
        } else {
            orderItems.push({ id, name, price, quantity });
        }
        renderOrderItems();
        updateOrderSummary();
    };

    // Event untuk product cards
    productCards.forEach(card => {
        card.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseInt(this.getAttribute('data-price'));
            addToCart(id, name, price);
        });
    });

    // Event untuk checkout
    checkoutBtn.addEventListener('click', function () {
        if (orderItems.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        const customerName = document.getElementById('customerName').value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        const receivedAmount = parseInt(amountReceived.value.replace(/\D/g, '') || 0);

        if (!customerName || !receivedAmount) {
            alert('Mohon lengkapi nama pelanggan dan pembayaran!');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const total = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        fetch('/kasir/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                customerName,
                paymentMethod,
                amountReceived: receivedAmount,
                orderItems,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Transaksi berhasil!');
                    window.location.reload();
                } else {
                    alert('Transaksi gagal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses transaksi.');
            });
    });

    // Event untuk input uang diterima
    amountReceived.addEventListener('input', function () {
        const value = parseInt(this.value.replace(/\D/g, '') || 0);
        const total = parseInt(subtotalElement.textContent.replace(/\D/g, '') || 0);

        if (value >= total) {
            changeDisplay.textContent = formatCurrency(value - total);
            changeAmount.classList.remove('hidden');
        } else {
            changeAmount.classList.add('hidden');
        }

        this.value = new Intl.NumberFormat('id-ID').format(value);
    });

    // Event untuk metode pembayaran
    paymentMethods.forEach(method => {
        method.addEventListener('change', function () {
            if (this.value === 'Tunai') {
                cashPayment.classList.remove('hidden');
                qrisPayment.classList.add('hidden');
                changeAmount.classList.add('hidden');
                amountReceived.value = '';
            } else {
                cashPayment.classList.add('hidden');
                qrisPayment.classList.remove('hidden');
                changeAmount.classList.add('hidden');
                amountReceived.value = '';
            }
        });
    });

    // Event untuk nama pelanggan
    customerBtn.addEventListener('click', function () {
        const customerName = prompt('Masukkan nama pelanggan:');
        if (customerName) {
            this.textContent = `👤 ${customerName}`;
        }
    });

    // Inisialisasi tampilan awal
    updateOrderSummary();
    
});

// Fungsi untuk mengelola komponen racikan
function initializeCompoundingForm() {
    const addCompoundingItemBtn = document.getElementById('add-compounding-item');
    const compoundingItems = document.getElementById('compounding-items');

    if (addCompoundingItemBtn) {
        addCompoundingItemBtn.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'compounding-item';
            newItem.innerHTML = `
                <select class="compounding-drug">
                    <option value="">Pilih obat</option>
                    ${Array.from(document.querySelectorAll('.product-card')).map(card => `
                        <option value="${card.dataset.id}">
                            ${card.dataset.name} (Rp ${new Intl.NumberFormat('id-ID').format(card.dataset.price)})
                        </option>
                    `).join('')}
                </select>
                <input type="number" class="compounding-quantity" placeholder="Jumlah" min="1" value="1">
                <button class="remove-compounding-item">×</button>
            `;
            compoundingItems.appendChild(newItem);

            // Event untuk tombol hapus
            newItem.querySelector('.remove-compounding-item').addEventListener('click', function() {
                newItem.remove();
            });
        });
    }
}

// Fungsi untuk mengelola tab
function initializeTabs() {
    const tabs = document.querySelectorAll('.category-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Nonaktifkan semua tab
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.add('hidden'));

            // Aktifkan tab yang dipilih
            tab.classList.add('active');
            const target = document.getElementById(tab.dataset.category);
            if (target) {
                target.classList.remove('hidden');
            }
        });
    });
}

// Inisialisasi saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    initializeCompoundingForm();
    initializeTabs();

    // Set tab default aktif
    const defaultTab = document.querySelector('.category-tab[data-category="obat-biasa"]');
    if (defaultTab) {
        defaultTab.click();
    }
});
