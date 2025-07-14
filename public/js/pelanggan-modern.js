// Modern Pelanggan JavaScript
class ApotekApp {
    constructor() {
        this.products = [];
        this.filteredProducts = [];
        this.currentCategory = 'all';
        this.searchQuery = '';
        this.isMobileMenuOpen = false;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadProducts();
        this.setupNavigation();
        this.setupScrollEffects();
        this.setupMobileNav();
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
            // Toggle menu when clicking the hamburger icon
            navToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleMobileMenu();
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!navMenu.contains(e.target) && !navToggle.contains(e.target) && this.isMobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });
            
            // Close mobile menu when clicking on a link
            const navLinks = navMenu.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    this.closeMobileMenu();
                    
                    // Smooth scroll to section
                    const targetId = link.getAttribute('href');
                    if (targetId && targetId.startsWith('#') && targetId !== '#') {
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            e.preventDefault();
                            const headerHeight = document.querySelector('.navbar')?.offsetHeight || 80;
                            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                            
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
            
            // Handle resize events to reset mobile menu state
            window.addEventListener('resize', this.debounce(() => {
                if (window.innerWidth > 768 && this.isMobileMenuOpen) {
                    this.closeMobileMenu();
                }
            }, 250));
            
            // Handle escape key to close mobile menu
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });
        }
    }
    

    
    toggleMobileMenu() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        const overlay = document.querySelector('.nav-overlay');
        
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }
    
    openMobileMenu() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        navMenu.classList.add('active');
        navToggle.classList.add('active');
        this.isMobileMenuOpen = true;
        
        // Focus management
        const firstLink = navMenu.querySelector('.nav-link');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 100);
        }
    }
    
    closeMobileMenu() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        navMenu.classList.remove('active');
        navToggle.classList.remove('active');
        this.isMobileMenuOpen = false;
        
        // Return focus to toggle button
        navToggle.focus();
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
            if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                this.closeModal();
            }
        });
        
        // Handle mobile modal interactions
        this.setupMobileModal();
    }
    
    setupMobileModal() {
        const modal = document.getElementById('productModal');
        const modalContent = modal.querySelector('.modal-content');
        
        if (!modalContent) return;
        
        // Prevent modal content clicks from closing modal
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        
        // Handle touch events for mobile swipe to close
        let startY = 0;
        let currentY = 0;
        
        modalContent.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
        }, { passive: true });
        
        modalContent.addEventListener('touchend', (e) => {
            const diff = startY - currentY;
            const threshold = 100;
            
            // Close modal if swiped down significantly on mobile
            if (diff > threshold && window.innerWidth <= 480) {
                this.closeModal();
            }
        }, { passive: true });
        
        modalContent.addEventListener('touchmove', (e) => {
            currentY = e.touches[0].clientY;
            const diff = startY - currentY;
            
            // Allow scrolling within modal content
            if (modalContent.scrollTop > 0 || diff < 0) {
                return;
            }
            
            // Prevent default only when trying to scroll up at the top
            if (diff > 0 && modalContent.scrollTop === 0) {
                e.preventDefault();
            }
        }, { passive: false });
    }
    
    showProductModal(product) {
        const modal = document.getElementById('productModal');
        if (!modal) return;
        
        // Update modal content
        this.updateModalContent(product);
        
        // Show modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus trap
        this.trapFocus(modal);
        
        // Add animation class
        setTimeout(() => {
            modal.classList.add('modal-active');
        }, 10);
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
            // Remove animation class
            modal.classList.remove('modal-active');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
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
    const phoneNumber = '6285179674249';
    const message = 'Halo, saya ingin berkonsultasi tentang obat yang tersedia di Apotek Puri Pasir Putih.';
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.apotekApp = new ApotekApp();
    
    // Prevent horizontal scrolling on mobile
    preventHorizontalScroll();
    
    var modal = document.getElementById('productModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('modal-active');
        document.body.style.overflow = '';
    }
});

// Function to prevent horizontal scrolling
function preventHorizontalScroll() {
    // Set viewport meta tag if not present
    if (!document.querySelector('meta[name="viewport"]')) {
        const viewport = document.createElement('meta');
        viewport.name = 'viewport';
        viewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
        document.head.appendChild(viewport);
    }
    
    // Prevent horizontal scroll on touch devices
    document.addEventListener('touchmove', (e) => {
        if (e.touches.length > 1) {
            e.preventDefault();
        }
    }, { passive: false });
    
    // Prevent horizontal scroll on wheel events
    document.addEventListener('wheel', (e) => {
        if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
            e.preventDefault();
        }
    }, { passive: false });
    
    // Ensure body doesn't overflow
    document.body.style.overflowX = 'hidden';
    document.documentElement.style.overflowX = 'hidden';
    
    // Fix any elements that might cause horizontal scroll
    const fixOverflow = () => {
        const elements = document.querySelectorAll('*');
        elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.right > window.innerWidth) {
                el.style.maxWidth = '100%';
                el.style.overflowX = 'hidden';
            }
        });
    };
    
    // Run on load and resize
    fixOverflow();
    window.addEventListener('resize', fixOverflow);
}

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
    
    /* Additional horizontal scroll prevention */
    html, body {
        overflow-x: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    * {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .container, .nav-container, .hero-container, .about-content, .contact-content, .footer-content {
        overflow-x: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    @media (max-width: 768px) {
        html, body {
            overflow-x: hidden !important;
            width: 100vw !important;
            max-width: 100vw !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 var(--spacing-3) !important;
            overflow-x: hidden !important;
        }
    }
    
    @media (max-width: 480px) {
        html, body {
            overflow-x: hidden !important;
            width: 100vw !important;
            max-width: 100vw !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 var(--spacing-2) !important;
            overflow-x: hidden !important;
        }
    }
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);