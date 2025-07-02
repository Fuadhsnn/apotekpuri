document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    loadProducts();
    
    // Setup search functionality
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    // Ensure search input is focused and working
    if (searchInput) {
        // Force focus to work
        searchInput.addEventListener('click', function() {
            this.focus();
        });
        
        // Make sure input is not disabled
        searchInput.disabled = false;
        searchInput.style.pointerEvents = 'auto';
    }
    
    if (searchInput && searchButton) {
        console.log('Search elements found');
        
        // Search when button is clicked
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Search button clicked');
            searchProducts(searchInput.value);
        });
        
        // Search when Enter key is pressed
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                console.log('Enter key pressed in search');
                searchProducts(searchInput.value);
            }
        });
    } else {
        console.error('Search elements not found');
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('productModal');
        if (modal && event.target === modal) {
            closeModal();
        }
    });

    // Close modal when clicking the close button
    const closeButton = document.querySelector('.close-modal');
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }
    
    // Navigation functionality removed as per user request
    
    // Smooth scrolling for navigation links
    const anchors = document.querySelectorAll('a[href^="#"]');
    console.log('Found', anchors.length, 'anchor links');
    
    anchors.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Anchor clicked:', this.getAttribute('href'));
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                console.log('Scrolling to', targetId);
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                console.error('Target element not found:', targetId);
            }
        });
    });
});

// Global variable to store all products
let allProducts = [];

async function loadProducts() {
    try {
        const response = await fetch('/pelanggan/obat');
        const products = await response.json();
        allProducts = products; // Store all products for search functionality
        const productsGrid = document.getElementById('productsGrid');
        
        if (products.length === 0) {
            productsGrid.innerHTML = '<p class="no-products">Tidak ada produk tersedia</p>';
            return;
        }

        displayProducts(products);
        console.log('Products loaded:', products);
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsGrid').innerHTML = 
            '<p class="error-message">Terjadi kesalahan saat memuat produk</p>';
    }
}

function displayProducts(products) {
    const productsGrid = document.getElementById('productsGrid');
    
    if (products.length === 0) {
        productsGrid.innerHTML = '<p class="no-products">Tidak ada produk yang sesuai dengan pencarian</p>';
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
                <p class="stock ${parseInt(product.stok) > 0 ? 'in-stock' : 'out-of-stock'}">
                    <span class="stock-icon"></span>
                    ${parseInt(product.stok) > 0 ? 'Tersedia' : 'Tidak Tersedia'}
                </p>
            </div>
        </a>
    `).join('');
}

function searchProducts(keyword) {
    if (!keyword.trim()) {
        displayProducts(allProducts);
        return;
    }
    
    keyword = keyword.toLowerCase();
    
    const filteredProducts = allProducts.filter(product => 
        product.nama_obat.toLowerCase().includes(keyword) || 
        product.kode_obat.toLowerCase().includes(keyword) || 
        (product.kategori && product.kategori.toLowerCase().includes(keyword)) ||
        (product.deskripsi && product.deskripsi.toLowerCase().includes(keyword))
    );
    
    displayProducts(filteredProducts);
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