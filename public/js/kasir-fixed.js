// Definisikan variabel global
let orderItems = [];
let currentDoctorName = null;
let currentPrescriptionNumber = null;

// Format number to currency - GLOBAL FUNCTION
function formatCurrency(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

// Fungsi untuk safe element selection
function safeGetElement(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`Element with id '${id}' not found`);
    }
    return element;
}

function safeQuerySelector(selector) {
    const element = document.querySelector(selector);
    if (!element) {
        console.warn(`Element with selector '${selector}' not found`);
    }
    return element;
}

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi elemen-elemen DOM dengan safe selection
    const productCards = document.querySelectorAll('.product-card');
    const orderItemsContainer = safeGetElement('order-items');
    const itemCountElement = safeGetElement('item-count');
    const subtotalElement = safeGetElement('subtotal');
    const totalElement = safeGetElement('total');
    const checkoutBtn = safeGetElement('checkout-btn');
    const customerBtn = safeQuerySelector('.customer-btn');
    const amountReceived = safeGetElement('amountReceived');
    const changeAmount = safeGetElement('changeAmount');
    const changeDisplay = safeGetElement('changeDisplay');
    const paymentMethods = document.getElementsByName('paymentMethod');
    const cashPayment = safeGetElement('cashPayment');
    const qrisPayment = safeGetElement('qrisPayment');
    const searchInput = safeQuerySelector('.search-input');
    const categoryTabs = document.querySelectorAll('.tab-button');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const productGrid = safeQuerySelector('.product-grid');
    const tabContents = document.querySelectorAll('.tab-content');
    

    // Fungsi pencarian obat
    function searchMedicines(query) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const name = product.getAttribute('data-name');
            if (name && name.toLowerCase().includes(query.toLowerCase())) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Event listener untuk pencarian
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchMedicines(this.value);
        });
    }

    // Fungsi filter berdasarkan kategori
    function filterByCategory(category) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const productCategory = product.getAttribute('data-category');
            if (category === 'All' || productCategory === category) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }
    
    // Fungsi filter produk berdasarkan kategori tab
    function filterProducts(category) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            if (category === 'obat-biasa' || category === 'All') {
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
            const targetId = this.getAttribute('data-tab');
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
            
            // Tambahan untuk form khusus
            const category = this.getAttribute('data-tab');
            if (category === 'obat-racikan') {
                // Tidak perlu memanggil showRacikanForm() karena konten tab sudah ditampilkan di atas
            } else if (category === 'resep-dokter') {
                // Tidak perlu memanggil showResepForm() karena konten tab sudah ditampilkan di atas
            } else {
                // Tidak perlu memanggil hideSpecialForms() karena konten tab sudah ditampilkan di atas
                filterProducts(category);
            }
        });
    });

    // Update ringkasan pesanan
    function updateOrderSummary() {
        const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        if (subtotalElement) {
            subtotalElement.textContent = formatCurrency(subtotal);
        }
        if (totalElement) {
            totalElement.textContent = formatCurrency(subtotal);
        }
        if (itemCountElement) {
            const totalItems = orderItems.reduce((sum, item) => sum + item.quantity, 0);
            itemCountElement.textContent = totalItems;
        }
    }

    // Render item di keranjang
    function renderOrderItems() {
        if (!orderItemsContainer) return;
        
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
    window.addToCart = function (item) {
        // Mendukung format lama dan baru
        let id, name, price, quantity, type;
        
        if (typeof item === 'object' && item !== null) {
            // Format baru: menerima objek
            id = item.id;
            name = item.name;
            price = item.price;
            quantity = item.quantity || 1;
            type = item.type || 'obat';
        } else {
            // Format lama: menerima parameter terpisah
            id = arguments[0];
            name = arguments[1];
            price = arguments[2];
            quantity = arguments[3] || 1;
            type = 'obat';
        }
        
        const existingItemIndex = orderItems.findIndex(item => item.name === name);
        if (existingItemIndex !== -1) {
            orderItems[existingItemIndex].quantity += quantity;
        } else {
            orderItems.push({ id, name, price, quantity, type });
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
            addToCart({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                type: 'obat'
            });
        });
    });

    // Event untuk checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            if (orderItems.length === 0) {
                showWarningAlert('Keranjang masih kosong!', 'Silahkan tambahkan obat ke keranjang terlebih dahulu.');
                return;
            }

            const customerName = safeGetElement('customerName')?.value || '';
            const paymentMethodElement = document.querySelector('input[name="paymentMethod"]:checked');
            
            if (!paymentMethodElement) {
                showWarningAlert('Metode Pembayaran', 'Mohon pilih metode pembayaran.');
                return;
            }
            
            const paymentMethod = paymentMethodElement.value;
            const receivedAmount = parseInt((amountReceived?.value || '').replace(/\D/g, '') || 0);
            const total = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Validasi pembayaran
            if (paymentMethod === 'Tunai') {
                if (!receivedAmount) {
                    showWarningAlert('Pembayaran Tidak Lengkap', 'Mohon masukkan jumlah uang yang diterima.');
                    return;
                } else if (receivedAmount < total) {
                    showWarningAlert('Uang Tidak Cukup', `Uang yang diterima kurang dari total pembayaran. Kurang: ${formatCurrency(total - receivedAmount)}`);
                    return;
                }
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                showErrorAlert('CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            // Jika metode pembayaran QRIS, set amountReceived sama dengan total
            const finalAmount = paymentMethod === 'QRIS' ? total : receivedAmount;

            // Tampilkan loading alert
            showLoadingAlert('Memproses pembayaran...');

            fetch('/kasir/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    customerName: customerName || 'Pelanggan',
                    doctorName: currentDoctorName,
                    prescriptionNumber: currentPrescriptionNumber,
                    paymentMethod,
                    amountReceived: finalAmount,
                    orderItems,
                }),
            })
                .then(response => {
                    // Tutup loading alert
                    closeLoadingAlert();
                    
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    // Cek status response
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response text:', text);
                            throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                        });
                    }
                    
                    // Cek content type
                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type:', contentType);
                    
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('Response bukan JSON yang valid: ' + text.substring(0, 100));
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showSuccessAlert(paymentMethod, data.penjualan_id, total);
                    } else {
                        showErrorAlert('Transaksi gagal: ' + (data.message || 'Kesalahan tidak diketahui'));
                    }
                })
                .catch(error => {
                    console.error('Error detail:', error);
                    
                    // Tampilkan pesan error yang lebih spesifik
                    let errorMessage = 'Terjadi kesalahan saat memproses transaksi.';
                    
                    if (error.message.includes('HTTP error')) {
                        errorMessage = 'Server error. Silakan coba lagi.';
                    } else if (error.message.includes('JSON')) {
                        errorMessage = 'Response server tidak valid. Transaksi mungkin berhasil, silakan cek riwayat transaksi.';
                    } else if (error.message.includes('Failed to fetch')) {
                        errorMessage = 'Koneksi terputus. Silakan cek koneksi internet Anda.';
                    }
                    
                    showErrorAlert(errorMessage);
                });
        });
    }

    // Event untuk input uang diterima
    if (amountReceived) {
        amountReceived.addEventListener('input', function () {
            const value = parseInt(this.value.replace(/\D/g, '') || 0);
            const total = parseInt((subtotalElement?.textContent || '').replace(/\D/g, '') || 0);

            if (value >= total && changeDisplay && changeAmount) {
                changeDisplay.textContent = formatCurrency(value - total);
                changeAmount.classList.remove('hidden');
            } else if (changeAmount) {
                changeAmount.classList.add('hidden');
            }

            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    }

    // Event untuk metode pembayaran
    Array.from(paymentMethods).forEach(method => {
        method.addEventListener('change', function () {
            if (this.value === 'Tunai') {
                if (cashPayment) cashPayment.classList.remove('hidden');
                if (qrisPayment) qrisPayment.classList.add('hidden');
                if (changeAmount) changeAmount.classList.add('hidden');
                if (amountReceived) amountReceived.value = '';
            } else {
                if (cashPayment) cashPayment.classList.add('hidden');
                if (qrisPayment) qrisPayment.classList.remove('hidden');
                if (changeAmount) changeAmount.classList.add('hidden');
                if (amountReceived) amountReceived.value = '';
            }
        });
    });

    // Event untuk nama pelanggan
    if (customerBtn) {
        customerBtn.addEventListener('click', function () {
            const customerName = prompt('Masukkan nama pelanggan:');
            if (customerName) {
                this.textContent = `👤 ${customerName}`;
            }
        });
    }

    // Fungsi untuk menambahkan obat racikan ke keranjang
    function addRacikanToCart() {
        const namaRacikan = safeGetElement('nama-racikan')?.value;
        const aturanPakai = safeGetElement('aturan-pakai')?.value;
        const komposisiContainer = safeGetElement('komposisi-container');
        
        if (!komposisiContainer) return;
        
        if (!namaRacikan) {
            showWarningAlert('Racikan Tidak Lengkap', 'Mohon isi nama racikan!');
            return;
        }
        
        const komposisiItems = komposisiContainer.querySelectorAll('div.flex');
        if (komposisiItems.length === 0) {
            showWarningAlert('Racikan Tidak Lengkap', 'Mohon tambahkan minimal satu obat ke racikan!');
            return;
        }
        
        let totalHarga = 0;
        let komposisiText = [];
        
        komposisiItems.forEach(item => {
            const select = item.querySelector('select');
            const quantity = item.querySelector('input[type="number"]')?.value || 1;
            
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const obatName = selectedOption.text.split(' (')[0];
                const obatPrice = parseFloat(selectedOption.getAttribute('data-price') || 0);
                totalHarga += obatPrice * quantity;
                komposisiText.push(`${obatName} x${quantity}`);
            }
        });
        
        if (komposisiText.length === 0) {
            showWarningAlert('Racikan Tidak Lengkap', 'Mohon pilih minimal satu obat untuk racikan!');
            return;
        }
        
        const racikanName = `${namaRacikan} (${aturanPakai ? aturanPakai : 'Sesuai anjuran'})
${komposisiText.join(', ')}`;
        
        // Tambahkan ke keranjang
        addToCart({
            id: 'racikan-' + Date.now(),
            name: racikanName,
            price: totalHarga,
            quantity: 1,
            type: 'racikan'
        });
        
        // Reset form
        if (safeGetElement('nama-racikan')) safeGetElement('nama-racikan').value = '';
        if (safeGetElement('aturan-pakai')) safeGetElement('aturan-pakai').value = '';
        
        // Hapus semua komposisi kecuali yang pertama
        if (komposisiItems.length > 0) {
            const firstItem = komposisiItems[0];
            const firstSelect = firstItem.querySelector('select');
            const firstInput = firstItem.querySelector('input[type="number"]');
            
            if (firstSelect) firstSelect.value = '';
            if (firstInput) firstInput.value = 1;
            
            for (let i = 1; i < komposisiItems.length; i++) {
                komposisiItems[i].remove();
            }
        }
        
        // Tampilkan pesan sukses
        showSuccessAlert('Racikan Ditambahkan', null, totalHarga);
    }

    // Fungsi untuk menambahkan resep dokter ke keranjang
    function addResepToCart() {
        const namaPasien = document.querySelector('#resep-dokter input[placeholder="Nama pasien"]')?.value;
        const namaDokter = document.querySelector('#resep-dokter input[placeholder="Nama dokter"]')?.value;
        const nomorResep = document.querySelector('#resep-dokter input[placeholder="Nomor resep"]')?.value;
        const tanggalResep = document.querySelector('#resep-dokter input[type="date"]')?.value;
        
        if (!namaPasien || !namaDokter || !nomorResep || !tanggalResep) {
            showWarningAlert('Resep Tidak Lengkap', 'Mohon lengkapi semua data resep!');
            return;
        }
        
        // Simpan data dokter dan nomor resep untuk digunakan saat checkout
        currentDoctorName = namaDokter;
        currentPrescriptionNumber = nomorResep;
        
        const resepObatCards = document.querySelectorAll('.resep-obat-card');
        let selectedObats = [];
        let totalHarga = 0;
        
        // Ambil semua obat yang ada di resep
        resepObatCards.forEach(card => {
            const obatName = card.getAttribute('data-name');
            const obatPrice = parseFloat(card.getAttribute('data-price') || 0);
            const quantity = parseInt(card.querySelector('.resep-qty')?.value || 1);
            
            selectedObats.push(`${obatName} x${quantity}`);
            totalHarga += obatPrice * quantity;
        });
        
        if (selectedObats.length === 0) {
            showWarningAlert('Resep Tidak Lengkap', 'Tidak ada obat yang tersedia untuk resep!');
            return;
        }
        
        const resepName = `Resep #${nomorResep} (${namaPasien})\nDokter: ${namaDokter}, Tgl: ${tanggalResep}\n${selectedObats.join(', ')}`;
        
        // Tambahkan ke keranjang
        addToCart({
            id: 'resep-' + Date.now(),
            name: resepName,
            price: totalHarga,
            quantity: 1,
            type: 'resep'
        });
        
        // Reset form
        if (document.querySelector('#resep-dokter input[placeholder="Nama pasien"]')) 
            document.querySelector('#resep-dokter input[placeholder="Nama pasien"]').value = '';
        if (document.querySelector('#resep-dokter input[placeholder="Nama dokter"]')) 
            document.querySelector('#resep-dokter input[placeholder="Nama dokter"]').value = '';
        if (document.querySelector('#resep-dokter input[placeholder="Nomor resep"]')) 
            document.querySelector('#resep-dokter input[placeholder="Nomor resep"]').value = '';
        if (document.querySelector('#resep-dokter input[type="date"]')) 
            document.querySelector('#resep-dokter input[type="date"]').value = '';
        
        // Tampilkan pesan sukses
        showSuccessAlert('Resep Ditambahkan', null, totalHarga);
    }

    // Event listener untuk elemen racikan dan resep (dengan null check)
    const tambahObatBtn = safeGetElement('tambah-obat');
    if (tambahObatBtn) {
        tambahObatBtn.addEventListener('click', function() {
            const komposisiContainer = safeGetElement('komposisi-container');
            if (!komposisiContainer) return;
            
            const newItem = document.createElement('div');
            newItem.className = 'flex items-center space-x-2';
            
            const firstSelect = komposisiContainer.querySelector('select');
            if (firstSelect) {
                newItem.innerHTML = `
                    <select class="flex-1 rounded-md border-gray-300 shadow-sm racikan-obat-select">
                        <option value="">Pilih obat</option>
                        ${Array.from(firstSelect.options).map(opt => 
                            `<option value="${opt.value}" data-price="${opt.getAttribute('data-price')}" ${opt.disabled ? 'disabled' : ''}>
                                ${opt.text}
                            </option>`
                        ).join('')}
                    </select>
                    <input type="number" class="w-20 rounded-md border-gray-300 shadow-sm" min="1" value="1">
                    <button type="button" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md" onclick="this.closest('div').remove()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                komposisiContainer.appendChild(newItem);
            }
        });
    }

    const tambahKeKeranjangBtn = safeGetElement('tambah-ke-keranjang');
    if (tambahKeKeranjangBtn) {
        tambahKeKeranjangBtn.addEventListener('click', addRacikanToCart);
    }
    
    // Event listener untuk tombol tambah resep ke keranjang
    const tambahResepBtn = safeGetElement('tambah-resep');
    if (tambahResepBtn) {
        tambahResepBtn.addEventListener('click', addResepToCart);
    }

    // Inisialisasi tampilan awal
    updateOrderSummary();
});

// Fungsi untuk menampilkan alert sukses yang modern
function showSuccessAlert(paymentMethod, penjualanId, total) {
    const overlay = document.createElement('div');
    overlay.className = 'success-alert-overlay';
    overlay.innerHTML = `
        <div class="success-alert-modal">
            <div class="success-alert-header">
                <div class="success-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#4CAF50"/>
                        <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2>Transaksi Berhasil!</h2>
                <p class="success-message">
                    ${paymentMethod === 'QRIS' 
                        ? 'Silahkan scan QR code untuk melakukan pembayaran' 
                        : 'Pembayaran telah berhasil diproses'}
                </p>
            </div>
            
            <div class="success-alert-body">
                <div class="transaction-details">
                    <div class="detail-row">
                        <span>Total Pembayaran:</span>
                        <span class="amount">${formatCurrency(total)}</span>
                    </div>
                    <div class="detail-row">
                        <span>Metode Pembayaran:</span>
                        <span class="payment-method">${paymentMethod}</span>
                    </div>
                    <div class="detail-row">
                        <span>Waktu Transaksi:</span>
                        <span>${new Date().toLocaleString('id-ID')}</span>
                    </div>
                </div>
                
                ${paymentMethod === 'QRIS' ? `
                    <div class="qris-section">
                        <div class="qris-placeholder">
                            <div class="qr-icon">📱</div>
                            <p>QR Code akan ditampilkan di struk</p>
                        </div>
                    </div>
                ` : ''}
            </div>
            
            <div class="success-alert-footer">
                <button class="btn-print" onclick="printStruk(${penjualanId})">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Cetak Struk
                </button>
                <button class="btn-continue" onclick="closeSuccessAlert()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Lanjut Transaksi
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        if (document.body.contains(overlay)) {
            closeSuccessAlert();
        }
    }, 10000);
}

// Fungsi untuk menampilkan alert error yang modern
function showErrorAlert(message) {
    const overlay = document.createElement('div');
    overlay.className = 'error-alert-overlay';
    overlay.innerHTML = `
        <div class="error-alert-modal">
            <div class="error-alert-header">
                <div class="error-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#dc3545"/>
                        <path d="M15 9l-6 6M9 9l6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2>Transaksi Gagal</h2>
                <p class="error-message">${message}</p>
            </div>
            
            <div class="error-alert-footer">
                <button class="btn-retry" onclick="closeErrorAlert()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 4v6h6M23 20v-6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 173.51 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Coba Lagi
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
}

// Fungsi untuk menampilkan warning alert
function showWarningAlert(title, message) {
    const overlay = document.createElement('div');
    overlay.className = 'warning-alert-overlay';
    overlay.innerHTML = `
        <div class="warning-alert-modal">
            <div class="warning-alert-header">
                <div class="warning-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#ff9800"/>
                        <path d="M12 8v4M12 16h.01" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2>${title}</h2>
                <p class="warning-message">${message}</p>
            </div>
            
            <div class="warning-alert-footer">
                <button class="btn-ok" onclick="closeWarningAlert()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Mengerti
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        if (document.body.contains(overlay)) {
            closeWarningAlert();
        }
    }, 5000);
}

// Fungsi untuk menampilkan loading alert
function showLoadingAlert(message = 'Memproses transaksi...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-alert-overlay';
    overlay.innerHTML = `
        <div class="loading-alert-modal">
            <div class="loading-spinner"></div>
            <p class="loading-message">${message}</p>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
}

// Fungsi untuk menutup alert sukses
function closeSuccessAlert() {
    const overlay = document.querySelector('.success-alert-overlay');
    if (overlay) {
        overlay.classList.add('hide');
        setTimeout(() => {
            overlay.remove();
            window.location.reload();
        }, 300);
    }
}

// Fungsi untuk menutup alert error
function closeErrorAlert() {
    const overlay = document.querySelector('.error-alert-overlay');
    if (overlay) {
        overlay.classList.add('hide');
        setTimeout(() => {
            overlay.remove();
        }, 300);
    }
}

// Fungsi untuk menutup warning alert
function closeWarningAlert() {
    const overlay = document.querySelector('.warning-alert-overlay');
    if (overlay) {
        overlay.classList.add('hide');
        setTimeout(() => {
            overlay.remove();
        }, 300);
    }
}

// Fungsi untuk menutup loading alert
function closeLoadingAlert() {
    const overlay = document.querySelector('.loading-alert-overlay');
    if (overlay) {
        overlay.classList.add('hide');
        setTimeout(() => {
            overlay.remove();
        }, 300);
    }
}

// Fungsi untuk mencetak struk
function printStruk(penjualanId) {
    if (penjualanId) {
        const printWindow = window.open(`/kasir/print/${penjualanId}`, '_blank');
        if (!printWindow) {
            showErrorAlert('Popup diblokir oleh browser. Mohon izinkan popup untuk mencetak struk.');
            return;
        }
        
        setTimeout(() => {
            closeSuccessAlert();
        }, 1000);
    }
}