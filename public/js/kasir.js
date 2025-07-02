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
    const changeDisplay = document.getElementById('changeDisplay');
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
    function addRacikanToCart() {
        const namaRacikan = document.getElementById('nama-racikan').value;
        const aturanPakai = document.getElementById('aturan-pakai').value;
        const komposisiContainer = document.getElementById('komposisi-container');
        const komposisiItems = komposisiContainer.querySelectorAll('div.flex');
        
        if (!namaRacikan) {
            alert('Mohon isi nama racikan!');
            return;
        }
        
        if (komposisiItems.length === 0) {
            alert('Mohon tambahkan minimal satu obat ke racikan!');
            return;
        }
        
        let totalHarga = 0;
        let komposisiText = [];
        
        komposisiItems.forEach(item => {
            const select = item.querySelector('select');
            const quantity = item.querySelector('input[type="number"]').value;
            const selectedOption = select.options[select.selectedIndex];
            
            if (select.value) {
                const obatName = selectedOption.text.split(' (')[0];
                const obatPrice = parseFloat(selectedOption.getAttribute('data-price'));
                totalHarga += obatPrice * quantity;
                komposisiText.push(`${obatName} x${quantity}`);
            }
        });
        
        if (komposisiText.length === 0) {
            alert('Mohon pilih minimal satu obat untuk racikan!');
            return;
        }
        
        const racikanName = `${namaRacikan} (${aturanPakai ? aturanPakai : 'Sesuai anjuran'})\n${komposisiText.join(', ')}`;
        
        // Tambahkan ke keranjang
        addToCart(null, racikanName, totalHarga, 1);
        
        // Reset form
        document.getElementById('nama-racikan').value = '';
        document.getElementById('aturan-pakai').value = '';
        
        // Hapus semua komposisi kecuali yang pertama
        const firstItem = komposisiItems[0];
        firstItem.querySelector('select').value = '';
        firstItem.querySelector('input[type="number"]').value = 1;
        
        for (let i = 1; i < komposisiItems.length; i++) {
            komposisiItems[i].remove();
        }
    }

    // Variabel global untuk menyimpan data resep dokter
    let currentDoctorName = null;
    let currentPrescriptionNumber = null;

    // Fungsi untuk menambahkan resep dokter ke keranjang
    function addResepToCart() {
        const namaPasien = document.querySelector('#resep-dokter input[placeholder="Nama pasien"]').value;
        const namaDokter = document.querySelector('#resep-dokter input[placeholder="Nama dokter"]').value;
        const nomorResep = document.querySelector('#resep-dokter input[placeholder="Nomor resep"]').value;
        const tanggalResep = document.querySelector('#resep-dokter input[type="date"]').value;
        
        if (!namaPasien || !namaDokter || !nomorResep || !tanggalResep) {
            alert('Mohon lengkapi semua data resep!');
            return;
        }
        
        // Simpan data dokter dan nomor resep untuk digunakan saat checkout
        currentDoctorName = namaDokter;
        currentPrescriptionNumber = nomorResep;
        
        const resepObatCards = document.querySelectorAll('.resep-obat-card');
        let selectedObats = [];
        let totalHarga = 0;
        
        resepObatCards.forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                const obatName = card.getAttribute('data-name');
                const obatPrice = parseFloat(card.getAttribute('data-price'));
                const quantity = parseInt(card.querySelector('.resep-qty').value) || 1;
                
                selectedObats.push(`${obatName} x${quantity}`);
                totalHarga += obatPrice * quantity;
            }
        });
        
        if (selectedObats.length === 0) {
            alert('Mohon pilih minimal satu obat untuk resep!');
            return;
        }
        
        const resepName = `Resep #${nomorResep} (${namaPasien})\nDokter: ${namaDokter}, Tgl: ${tanggalResep}\n${selectedObats.join(', ')}`;
        
        // Tambahkan ke keranjang
        addToCart(null, resepName, totalHarga, 1);
        
        // Reset form
        document.querySelector('#resep-dokter input[placeholder="Nama pasien"]').value = '';
        document.querySelector('#resep-dokter input[placeholder="Nama dokter"]').value = '';
        document.querySelector('#resep-dokter input[placeholder="Nomor resep"]').value = '';
        document.querySelector('#resep-dokter input[type="date"]').value = '';
        
        // Uncheck semua obat
        resepObatCards.forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = false;
        });
    }

    // Event listener untuk tombol tambah obat di racikan
    document.getElementById('tambah-obat').addEventListener('click', function() {
        const komposisiContainer = document.getElementById('komposisi-container');
        const newItem = document.createElement('div');
        newItem.className = 'flex items-center space-x-2';
        
        // Clone select dari item pertama
        const firstSelect = komposisiContainer.querySelector('select');
        const newSelect = firstSelect.cloneNode(true);
        newSelect.value = '';
        
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
    });

    // Event listener untuk tombol tambah ke keranjang di racikan
    document.getElementById('tambah-ke-keranjang').addEventListener('click', addRacikanToCart);

    // Tambahkan event listener untuk kartu obat resep
    document.querySelectorAll('.resep-obat-card').forEach(card => {
        card.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const quantity = parseInt(this.querySelector('.resep-qty').value) || 1;
            
            addToCart(id, name, price, quantity);
            alert(`${name} telah ditambahkan ke keranjang`);
        });
    });
    
    // Mencegah event bubbling pada input quantity
    document.querySelectorAll('.resep-qty').forEach(input => {
        input.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        input.addEventListener('change', function(e) {
            e.stopPropagation();
        });
    });
    
    // Fungsi pencarian obat di resep dokter
    const searchResep = document.getElementById('searchResep');
    if (searchResep) {
        searchResep.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const resepObatCards = document.querySelectorAll('.resep-obat-card');
            
            resepObatCards.forEach(card => {
                const obatName = card.getAttribute('data-name').toLowerCase();
                if (obatName.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Fungsi pencarian obat di komposisi racikan
    const searchRacikan = document.getElementById('searchRacikan');
    if (searchRacikan) {
        searchRacikan.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const selects = document.querySelectorAll('.racikan-obat-select');
            
            selects.forEach(select => {
                // Simpan opsi yang dipilih saat ini
                const currentValue = select.value;
                
                // Sembunyikan/tampilkan opsi berdasarkan query
                Array.from(select.options).forEach(option => {
                    if (option.value === '') return; // Skip opsi default "Pilih obat"
                    
                    const optionText = option.text.toLowerCase();
                    if (optionText.includes(query)) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                // Kembalikan nilai yang dipilih
                select.value = currentValue;
            });
        });
    }

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
            showWarningAlert('Keranjang masih kosong!', 'Silahkan tambahkan obat ke keranjang terlebih dahulu.');
            return;
        }

        // Cek apakah tab resep dokter aktif
        const resepDokterTab = document.getElementById('resep-dokter');
        if (resepDokterTab && !resepDokterTab.classList.contains('hidden')) {
            // Ambil data resep dokter
            const namaPasien = document.querySelector('#resep-dokter input[placeholder="Nama pasien"]').value;
            const namaDokter = document.querySelector('#resep-dokter input[placeholder="Nama dokter"]').value;
            const nomorResep = document.querySelector('#resep-dokter input[placeholder="Nomor resep"]').value;
            const tanggalResep = document.querySelector('#resep-dokter input[type="date"]').value;
            
            if (!namaPasien || !namaDokter || !nomorResep || !tanggalResep) {
                alert('Mohon lengkapi semua data resep!');
                return;
            }
            
            // Simpan data dokter dan nomor resep untuk digunakan saat checkout
            currentDoctorName = namaDokter;
            currentPrescriptionNumber = nomorResep;
            
            // Cek apakah ada obat di keranjang
            if (orderItems.length === 0) {
                alert('Mohon tambahkan minimal satu obat ke keranjang!');
                return;
            }
        }

        const customerName = document.getElementById('customerName').value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        const receivedAmount = parseInt(amountReceived.value.replace(/\D/g, '') || 0);
        const total = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        // Jika metode pembayaran QRIS, tidak perlu validasi jumlah uang diterima
        if (paymentMethod === 'QRIS') {
            // Lanjutkan proses checkout tanpa validasi receivedAmount
        } else if (!receivedAmount) {
            showWarningAlert('Pembayaran Tidak Lengkap', 'Mohon masukkan jumlah uang yang diterima.');
            return;
        } else if (receivedAmount < total) {
            showWarningAlert('Uang Tidak Cukup', `Uang yang diterima kurang dari total pembayaran. Kurang: ${formatCurrency(total - receivedAmount)}`);
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


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
                customerName: customerName || 'Pelanggan', // Default jika nama kosong
                doctorName: currentDoctorName, // Kirim nama dokter jika ada
                prescriptionNumber: currentPrescriptionNumber, // Kirim nomor resep jika ada
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
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    const prescriptionTab = document.querySelector('.tab-button[data-tab="resep-dokter"]');
    const compoundingTab = document.querySelector('.tab-button[data-tab="obat-racikan"]');
    const prescriptionContent = document.getElementById('resep-dokter');
    const compoundingContent = document.getElementById('obat-racikan');

    // Event listener untuk tab kategori
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus kelas active dari semua tab button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Tambahkan kelas active ke tab button yang diklik
            this.classList.add('active');

            // Sembunyikan semua tab content
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Tampilkan tab content yang sesuai
            const tabId = this.getAttribute('data-tab');
            const tabContent = document.getElementById(tabId);
            if (tabContent) {
                tabContent.classList.remove('hidden');
            }
        });
    });

    // Set tab pertama sebagai active secara default
    if (tabButtons.length > 0 && tabContents.length > 0) {
        tabButtons[0].classList.add('active');
        tabContents[0].classList.remove('hidden');
    }

    initializeCompoundingForm();
    initializeTabs();
    
    // Aktifkan tab default jika ada
    const defaultTab = document.querySelector('.category-tab.default') || document.querySelector('.category-tab');
    if (defaultTab) {
        defaultTab.click();
    }
});

// Kode untuk tab prescription dan compounding sudah digabungkan ke event listener di atas

// Fungsi untuk menampilkan alert sukses yang modern
function showSuccessAlert(paymentMethod, penjualanId, total) {
    // Buat overlay
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
    
    // Animasi masuk
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
    
    // Auto close setelah 10 detik jika tidak ada aksi
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
                        <path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Coba Lagi
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Animasi masuk
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
    
    // Animasi masuk
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
    
    // Auto close setelah 5 detik
    setTimeout(() => {
        if (document.body.contains(overlay)) {
            closeWarningAlert();
        }
    }, 5000);
}

// Fungsi untuk menampilkan info alert
function showInfoAlert(title, message) {
    const overlay = document.createElement('div');
    overlay.className = 'info-alert-overlay';
    overlay.innerHTML = `
        <div class="info-alert-modal">
            <div class="info-alert-header">
                <div class="info-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#2196F3"/>
                        <path d="M12 16v-4M12 8h.01" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2>${title}</h2>
                <p class="info-message">${message}</p>
            </div>
            
            <div class="info-alert-footer">
                <button class="btn-ok-info" onclick="closeInfoAlert()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    OK
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Animasi masuk
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
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

// Fungsi untuk menutup info alert
function closeInfoAlert() {
    const overlay = document.querySelector('.info-alert-overlay');
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
        
        // Tutup alert setelah membuka struk
        setTimeout(() => {
            closeSuccessAlert();
        }, 1000);
    }
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
    
    // Animasi masuk
    setTimeout(() => {
        overlay.classList.add('show');
    }, 10);
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
