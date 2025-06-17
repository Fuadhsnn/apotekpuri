document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

async function loadProducts() {
    try {
        const response = await fetch('/pelanggan/obat');
        const products = await response.json();
        const productsGrid = document.getElementById('productsGrid');
        
        if (products.length === 0) {
            productsGrid.innerHTML = '<p class="no-products">Tidak ada produk tersedia</p>';
            return;
        }

        productsGrid.innerHTML = products.map(product => `
            <a href="/pelanggan/obat/${product.id}" class="product-card">
                <div class="product-image">
                    <img src="${product.gambar ? '/storage/' + product.gambar : '/default-product.jpg'}" alt="${product.nama_obat}">
                </div>
                <div class="product-info">
                    <h3>${product.nama_obat}</h3>
                    <p class="code">Kode: ${product.kode_obat}</p>
                    <p class="category">Kategori: ${product.kategori || '-'}</p>
                    <p class="price">Rp ${product.harga.toLocaleString('id-ID')}</p>
                    <p class="stock ${parseInt(product.stok) > 0 ? 'in-stock' : 'out-of-stock'}'>
                        <span class="stock-icon"></span>
                        ${parseInt(product.stok) > 0 ? 'Tersedia' : 'Tidak Tersedia'}
                    </p>
                </div>
            </a>
        `).join('');

        console.log('Products loaded:', products);
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsGrid').innerHTML = 
            '<p class="error-message">Terjadi kesalahan saat memuat produk</p>';
    }
}

function showProductDetails(product) {
    console.log('Showing product details:', product);
    const modal = document.getElementById('productModal');
    document.getElementById('modalProductImage').innerHTML = `
        <img src="${product.gambar ? '/storage/' + product.gambar : '/default-product.jpg'}" alt="${product.nama_obat}">
    `;
    document.getElementById('modalProductName').textContent = product.nama_obat;
    // Perbaiki juga di fungsi showProductDetails
    document.getElementById('modalProductPrice').textContent = 
        `Rp ${product.harga.toLocaleString('id-ID')}`;
    document.getElementById('modalStockStatus').innerHTML = `
        <div class="product-details-info">
            <div class="detail-row">
                <span class="detail-label">Kode</span>
                <span class="detail-value">${product.kode_obat}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Kategori</span>
                <span class="detail-value">${product.kategori || '-'}</span>
            </div>
            <div class="detail-row ${parseInt(product.stok) > 0 ? 'in-stock' : 'out-of-stock'}">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="stock-icon"></span>
                    ${parseInt(product.stok) > 0 ? 'Tersedia' : 'Tidak Tersedia'}
                </span>
            </div>
        </div>
    `;
    document.getElementById('modalProductDescription').textContent = product.deskripsi || 'Tidak ada deskripsi';
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}