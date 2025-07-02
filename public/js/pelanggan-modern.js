// Modern Pelanggan JavaScript
class ApotekApp {
    constructor() {
        this.products = [];
        this.filteredProducts = [];
        this.currentCategory = 'all';
        this.searchQuery = '';
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadProducts();
        this.setupNavigation();
        this.setupScrollEffects();
    }
    
    setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        
        if (searchInput && searchButton) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.searchQuery = e.target.value;
                this.filterProducts();
            }, 300));
            
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.searchQuery = e.target.value;
                    this.filterProducts();
                }
            });
            
            searchButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.searchQuery = searchInput.value;
                this.filterProducts();
            });
        }
        
        // Category filters
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all buttons
                filterButtons.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                btn.classList.add('active');
                
                // Update current category
                this.currentCategory = btn.dataset.category;
                this.filterProducts();
            });
        });
        
        // Modal functionality
        this.setupModal();
        
        // Mobile navigation
        this.setupMobileNav();
    }
    
    setupNavigation() {
        // Smooth scrolling for navigation links
        const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 80; // Account for fixed navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Update active nav link
                    this.updateActiveNavLink(targetId);
                }
            });
        });
        
        // Update active nav link on scroll
        window.addEventListener('scroll', this.throttle(() => {
            this.updateActiveNavLinkOnScroll();
        }, 100));
    }
    
    setupScrollEffects() {
        // Navbar background on scroll
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
        
        // Intersection Observer for animations
        this.setupIntersectionObserver();
    }
    
    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        const animateElements = document.querySelectorAll('.service-card, .product-card, .feature-item, .contact-card');
        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    }
    
    setupMobileNav() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                navToggle.classList.toggle('active');
            });
            
            // Close mobile menu when clicking on a link
            const navLinks = navMenu.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    navToggle.classList.remove('active');
                });
            });
        }
    }
    
    async loadProducts() {
        try {
            this.showLoading();
            
            const response = await fetch('/pelanggan/obat');
            if (!response.ok) {
                throw new Error('Failed to fetch products');
            }
            
            const products = await response.json();
            this.products = products;
            this.filteredProducts = products;
            
            this.hideLoading();
            this.displayProducts(this.filteredProducts);
            
        } catch (error) {
            console.error('Error loading products:', error);
            this.showError('Terjadi kesalahan saat memuat produk. Silakan coba lagi.');
        }
    }
    
    filterProducts() {
        let filtered = [...this.products];
        
        // Filter by category
        if (this.currentCategory !== 'all') {
            filtered = filtered.filter(product => {
                const category = (product.kategori || '').toLowerCase();
                return category.includes(this.currentCategory.toLowerCase());
            });
        }
        
        // Filter by search query
        if (this.searchQuery.trim()) {
            const query = this.searchQuery.toLowerCase();
            filtered = filtered.filter(product => {
                return (
                    product.nama_obat.toLowerCase().includes(query) ||
                    product.kode_obat.toLowerCase().includes(query) ||
                    (product.kategori && product.kategori.toLowerCase().includes(query)) ||
                    (product.deskripsi && product.deskripsi.toLowerCase().includes(query))
                );
            });
        }
        
        this.filteredProducts = filtered;
        this.displayProducts(this.filteredProducts);
    }
    
    displayProducts(products) {
        const productsGrid = document.getElementById('productsGrid');
        
        if (!productsGrid) return;
        
        if (products.length === 0) {
            productsGrid.innerHTML = this.getEmptyState();
            return;
        }
        
        const productsHTML = products.map(product => this.createProductCard(product)).join('');
        productsGrid.innerHTML = productsHTML;
        
        // Add click event listeners to product cards
        this.setupProductCardListeners();
    }
    
    createProductCard(product) {
        const isInStock = parseInt(product.stok) > 0;
        const stockClass = isInStock ? 'in-stock' : 'out-of-stock';
        const stockText = isInStock ? 'Tersedia' : 'Stok Habis';
        const imageUrl = product.gambar ? `/storage/${product.gambar}` : '/images/default-product.jpg';
        
        return `
            <div class="product-card" data-product-id="${product.id}">
                <div class="product-image">
                    <img src="${imageUrl}" alt="${product.nama_obat}" loading="lazy">
                </div>
                <div class="product-info">
                    <h3>${this.escapeHtml(product.nama_obat)}</h3>
                    <p class="code">Kode: ${this.escapeHtml(product.kode_obat)}</p>
                    <p class="category">${this.escapeHtml(product.kategori || 'Umum')}</p>
                    <p class="price">Rp ${this.formatCurrency(product.harga)}</p>
                    <p class="stock ${stockClass}">
                        <span class="stock-icon"></span>
                        ${stockText}
                    </p>
                </div>
            </div>
        `;
    }
    
    setupProductCardListeners() {
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = card.dataset.productId;
                const product = this.products.find(p => p.id == productId);
                if (product) {
                    this.showProductModal(product);
                }
            });
        });
    }
    
    setupModal() {
        const modal = document.getElementById('productModal');
        const modalOverlay = document.querySelector('.modal-overlay');
        const modalClose = document.querySelector('.modal-close');
        
        if (modalOverlay) {
            modalOverlay.addEventListener('click', () => this.closeModal());
        }
        
        if (modalClose) {
            modalClose.addEventListener('click', () => this.closeModal());
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && modal.style.display === 'block') {
                this.closeModal();
            }
        });
    }
    
    showProductModal(product) {
        const modal = document.getElementById('productModal');
        if (!modal) return;
        
        // Update modal content
        this.updateModalContent(product);
        
        // Show modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Focus trap
        this.trapFocus(modal);
    }
    
    updateModalContent(product) {
        const imageContainer = document.getElementById('modalProductImage');
        const nameElement = document.getElementById('modalProductName');
        const priceElement = document.getElementById('modalProductPrice');
        const stockElement = document.getElementById('modalStockStatus');
        const descriptionElement = document.getElementById('modalProductDescription');
        
        if (imageContainer) {
            const imageUrl = product.gambar ? `/storage/${product.gambar}` : '/images/default-product.jpg';
            imageContainer.innerHTML = `<img src="${imageUrl}" alt="${product.nama_obat}">`;
        }
        
        if (nameElement) {
            nameElement.textContent = product.nama_obat;
        }
        
        if (priceElement) {
            priceElement.textContent = `Rp ${this.formatCurrency(product.harga)}`;
        }
        
        if (stockElement) {
            const isInStock = parseInt(product.stok) > 0;
            const stockClass = isInStock ? 'in-stock' : 'out-of-stock';
            const stockText = isInStock ? 'Tersedia' : 'Stok Habis';
            
            stockElement.innerHTML = `
                <div class="stock-status ${stockClass}">
                    <span class="stock-icon"></span>
                    ${stockText}
                </div>
                <div class="product-details">
                    <p><strong>Kode:</strong> ${this.escapeHtml(product.kode_obat)}</p>
                    <p><strong>Kategori:</strong> ${this.escapeHtml(product.kategori || 'Umum')}</p>
                    <p><strong>Stok:</strong> ${product.stok} unit</p>
                </div>
            `;
        }
        
        if (descriptionElement) {
            descriptionElement.textContent = product.deskripsi || 'Tidak ada deskripsi tersedia.';
        }
    }
    
    closeModal() {
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
    
    trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        element.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }
        });
        
        firstElement?.focus();
    }
    
    showLoading() {
        const productsGrid = document.getElementById('productsGrid');
        if (productsGrid) {
            productsGrid.innerHTML = `
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Memuat produk...</p>
                </div>
            `;
        }
    }
    
    hideLoading() {
        // Loading will be hidden when products are displayed
    }
    
    showError(message) {
        const productsGrid = document.getElementById('productsGrid');
        if (productsGrid) {
            productsGrid.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                    <button class="btn btn-primary" onclick="location.reload()">
                        <i class="fas fa-refresh"></i>
                        Coba Lagi
                    </button>
                </div>
            `;
        }
    }
    
    getEmptyState() {
        return `
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Tidak ada produk ditemukan</h3>
                <p>Coba ubah kata kunci pencarian atau filter kategori</p>
                <button class="btn btn-outline" onclick="apotekApp.clearFilters()">
                    <i class="fas fa-times"></i>
                    Hapus Filter
                </button>
            </div>
        `;
    }
    
    clearFilters() {
        // Reset search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
        }
        this.searchQuery = '';
        
        // Reset category
        this.currentCategory = 'all';
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.category === 'all') {
                btn.classList.add('active');
            }
        });
        
        // Refresh products
        this.filterProducts();
    }
    
    updateActiveNavLink(targetId) {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === targetId) {
                link.classList.add('active');
            }
        });
    }
    
    updateActiveNavLinkOnScroll() {
        const sections = document.querySelectorAll('section[id]');
        const scrollPos = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                this.updateActiveNavLink(`#${sectionId}`);
            }
        });
    }
    
    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }
    
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }
}

// Global functions
window.closeModal = function() {
    if (window.apotekApp) {
        window.apotekApp.closeModal();
    }
};

window.contactPharmacist = function() {
    const phoneNumber = '082311323121';
    const message = 'Halo, saya ingin berkonsultasi tentang obat yang tersedia di Apotek Puri Pasir Putih.';
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.apotekApp = new ApotekApp();
});

// Add some CSS for empty and error states
const additionalStyles = `
    .empty-state,
    .error-state {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-16);
        text-align: center;
        color: var(--gray-500);
    }
    
    .empty-state i,
    .error-state i {
        font-size: 4rem;
        margin-bottom: var(--spacing-4);
        color: var(--gray-400);
    }
    
    .empty-state h3,
    .error-state h3 {
        font-size: var(--font-size-xl);
        color: var(--gray-700);
        margin-bottom: var(--spacing-2);
    }
    
    .empty-state p,
    .error-state p {
        margin-bottom: var(--spacing-6);
        max-width: 400px;
    }
    
    .product-details {
        margin-top: var(--spacing-4);
        padding: var(--spacing-4);
        background: var(--gray-50);
        border-radius: var(--radius-lg);
    }
    
    .product-details p {
        margin-bottom: var(--spacing-2);
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    .product-details p:last-child {
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .nav-menu.active {
            display: flex;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            flex-direction: column;
            padding: var(--spacing-4);
            box-shadow: var(--shadow-lg);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }
        
        .nav-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .nav-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
    }
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);