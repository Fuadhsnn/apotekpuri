document.addEventListener('DOMContentLoaded', function() {
    const orderItems = [];
    const productCards = document.querySelectorAll('.product-card');
    const orderItemsContainer = document.getElementById('order-items');
    const itemCountElement = document.getElementById('item-count');
    const subtotalElement = document.getElementById('subtotal');
    const discountElement = document.getElementById('discount');
    const taxElement = document.getElementById('tax');
    const roundingElement = document.getElementById('rounding');
    const totalElement = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const customerBtn = document.querySelector('.customer-btn');
    
    // Format number to currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    // Calculate and update order summary
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
    
    // Render order items
    function renderOrderItems() {
        if (orderItems.length === 0) {
            orderItemsContainer.innerHTML = '<div style="color: #777; text-align: center; padding: 20px;">No items added yet</div>';
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
    
    // Add product to order
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = parseInt(this.getAttribute('data-price'));
            
            // Check if item already exists in order
            const existingItemIndex = orderItems.findIndex(item => item.name === name);
            
            if (existingItemIndex !== -1) {
                // Increment quantity if item exists
                orderItems[existingItemIndex].quantity++;
            } else {
                // Add new item to order
                orderItems.push({
                    name,
                    price,
                    quantity: 1
                });
            }
            
            renderOrderItems();
            updateOrderSummary();
        });
    });
    
    // Checkout button
    checkoutBtn.addEventListener('click', function() {
        if (orderItems.length === 0) {
            alert('Please add items to the order first.');
            return;
        }
        
        // In a real app, this would process payment
        const total = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = Math.floor(total * 0.1);
        const tax = Math.floor((total - discount) * 0.1);
        const rounding = Math.round((total - discount + tax) / 100) * 100 - (total - discount + tax);
        const finalTotal = total - discount + tax + rounding;
        
        alert(`Payment processed successfully!\nTotal: ${formatCurrency(finalTotal)}`);
        
        // Reset order
        orderItems.length = 0;
        renderOrderItems();
        updateOrderSummary();
    });
    
    // Customer button
    customerBtn.addEventListener('click', function() {
        const customerName = prompt("Enter customer name:");
        if (customerName) {
            this.textContent = `👤 ${customerName}`;
        }
    });
    
    // Initialize with empty order
    updateOrderSummary();
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-bar input');
    const productGrid = document.querySelector('.product-grid');
    let typingTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            const query = this.value;
            if (query.length > 0) {
                fetch(`/kasir/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        productGrid.innerHTML = '';
                        data.forEach(obat => {
                            productGrid.innerHTML += `
                                <div class="product-card" data-id="${obat.id}" data-name="${obat.nama_obat}" data-price="${obat.harga_jual}">
                                    <div class="product-image">
                                        ${obat.gambar 
                                            ? `<img src="/storage/${obat.gambar}" alt="${obat.nama_obat}" style="width: 100%; height: 100%; object-fit: cover;">` 
                                            : `<div class="product-image-placeholder">💊</div>`}
                                    </div>
                                    <div class="product-name">${obat.nama_obat}</div>
                                    <div class="product-price">Rp ${new Intl.NumberFormat('id-ID').format(obat.harga_jual)}</div>
                                </div>
                            `;
                        });
                    });
            } else {
                // Reload semua produk
                window.location.reload();
            }
        }, 500); // Delay 500ms setelah user selesai mengetik
    });

    // Tambahkan event listener untuk kategori
    const categoryTabs = document.querySelectorAll('.category-tab');
    const productCards = document.querySelectorAll('.product-card');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Hapus kelas active dari semua tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            // Tambahkan kelas active ke tab yang diklik
            this.classList.add('active');

            const selectedCategory = this.textContent.toLowerCase();

            // Tampilkan semua produk jika "All" dipilih
            if (selectedCategory === 'all') {
                productCards.forEach(card => {
                    card.style.display = 'block';
                });
                return;
            }

            // Filter produk berdasarkan kategori
            productCards.forEach(card => {
                const productCategory = card.getAttribute('data-category');
                if (productCategory === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});