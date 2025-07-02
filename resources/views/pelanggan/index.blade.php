<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apotek Puri Pasir Putih - Solusi Kesehatan Terpercaya</title>
    <link rel="stylesheet" href="{{ asset('css/pelanggan-modern.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <div class="brand-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="brand-text">
                    <h1>Apotek Puri</h1>
                    <span>Pasir Putih</span>
                </div>
            </div>
            
            <div class="nav-menu" id="navMenu">
                <a href="#hero" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="#products" class="nav-link">
                    <i class="fas fa-pills"></i>
                    <span>Produk</span>
                </a>
                <a href="#services" class="nav-link">
                    <i class="fas fa-stethoscope"></i>
                    <span>Layanan</span>
                </a>
                <a href="#about" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang</span>
                </a>
                <a href="#contact" class="nav-link">
                    <i class="fas fa-phone"></i>
                    <span>Kontak</span>
                </a>
            </div>
            
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-background">
            <div class="hero-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Terpercaya Sejak 2020</span>
                </div>
                
                <h1 class="hero-title">
                    Kesehatan Anda,
                    <span class="gradient-text">Prioritas Kami</span>
                </h1>
                
                <p class="hero-description">
                    Dapatkan obat-obatan berkualitas tinggi dengan pelayanan profesional 
                    dan konsultasi gratis dari apoteker berpengalaman.
                </p>
                
                <div class="hero-actions">
                    <a href="#products" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Cari Obat
                    </a>
                    <a href="#contact" class="btn btn-outline">
                        <i class="fas fa-phone"></i>
                        Konsultasi
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Produk Tersedia</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Layanan Darurat</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5000+</div>
                        <div class="stat-label">Pelanggan Puas</div>
                    </div>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-card">
                    <div class="card-header">
                        <i class="fas fa-user-md"></i>
                        <span>Konsultasi Online</span>
                    </div>
                    <div class="card-body">
                        <p>Dapatkan saran medis dari apoteker profesional</p>
                        <div class="card-status">
                            <div class="status-dot"></div>
                            <span>Online Sekarang</span>
                        </div>
                    </div>
                </div>
                
                <div class="floating-elements">
                    <div class="floating-pill pill-1">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="floating-pill pill-2">
                        <i class="fas fa-capsules"></i>
                    </div>
                    <div class="floating-pill pill-3">
                        <i class="fas fa-tablets"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-star"></i>
                    <span>Layanan Unggulan</span>
                </div>
                <h2 class="section-title">Mengapa Memilih Kami?</h2>
                <p class="section-description">
                    Kami berkomitmen memberikan pelayanan kesehatan terbaik dengan standar internasional
                </p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                    <h3>Obat Original</h3>
                    <p>Semua produk dijamin keaslian dan kualitasnya langsung dari distributor resmi</p>
                    <div class="service-features">
                        <span><i class="fas fa-check"></i> BPOM Certified</span>
                        <span><i class="fas fa-check"></i> ISO 9001</span>
                    </div>
                </div>
                
                <div class="service-card featured">
                    <div class="service-badge">Populer</div>
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Konsultasi Gratis</h3>
                    <p>Konsultasi langsung dengan apoteker berpengalaman tanpa biaya tambahan</p>
                    <div class="service-features">
                        <span><i class="fas fa-check"></i> 24/7 Available</span>
                        <span><i class="fas fa-check"></i> Expert Advice</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Pengiriman Cepat</h3>
                    <p>Layanan antar obat ke rumah dengan waktu pengiriman yang fleksibel</p>
                    <div class="service-features">
                        <span><i class="fas fa-check"></i> Same Day</span>
                        <span><i class="fas fa-check"></i> Free Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products" id="products">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-pills"></i>
                    <span>Produk Kami</span>
                </div>
                <h2 class="section-title">Temukan Obat yang Anda Butuhkan</h2>
                <p class="section-description">
                    Koleksi lengkap obat-obatan untuk berbagai kebutuhan kesehatan Anda
                </p>
            </div>
            
            <!-- Search Section -->
            <div class="search-section">
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama obat, kategori, atau kode..." class="search-input">
                        <button id="searchButton" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <div class="search-filters">
                        <button class="filter-btn active" data-category="all">
                            <i class="fas fa-th"></i>
                            Semua
                        </button>
                        <button class="filter-btn" data-category="obat-keras">
                            <i class="fas fa-prescription-bottle-alt"></i>
                            Obat Keras
                        </button>
                        <button class="filter-btn" data-category="obat-bebas">
                            <i class="fas fa-pills"></i>
                            Obat Bebas
                        </button>
                        <button class="filter-btn" data-category="vitamin">
                            <i class="fas fa-leaf"></i>
                            Vitamin
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="products-container">
                <div class="products-grid" id="productsGrid">
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <p>Memuat produk...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <div class="image-container">
                        <div class="image-placeholder">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                        <div class="image-decorations">
                            <div class="decoration decoration-1"></div>
                            <div class="decoration decoration-2"></div>
                        </div>
                    </div>
                </div>
                
                <div class="about-text">
                    <div class="section-badge">
                        <i class="fas fa-heart"></i>
                        <span>Tentang Kami</span>
                    </div>
                    
                    <h2 class="section-title">Apotek Terpercaya di Depok</h2>
                    
                    <p class="about-description">
                        Apotek Puri Pasir Putih telah melayani masyarakat Depok dan sekitarnya sejak tahun 2020. 
                        Kami berkomitmen untuk menyediakan obat-obatan berkualitas tinggi dengan pelayanan yang 
                        profesional dan ramah.
                    </p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Bersertifikat Resmi</h4>
                                <p>Memiliki izin resmi dari Dinas Kesehatan dan BPOM</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Tim Profesional</h4>
                                <p>Didukung oleh apoteker dan tenaga kesehatan berpengalaman</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Buka Setiap Hari</h4>
                                <p>Melayani Anda dari pagi hingga malam, 7 hari seminggu</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="about-stats">
                        <div class="stat">
                            <div class="stat-number">4+</div>
                            <div class="stat-label">Tahun Pengalaman</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">5000+</div>
                            <div class="stat-label">Pelanggan Setia</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Jenis Obat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Hubungi Kami</span>
                </div>
                <h2 class="section-title">Kunjungi atau Hubungi Kami</h2>
                <p class="section-description">
                    Kami siap melayani Anda dengan sepenuh hati
                </p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Alamat Kami</h3>
                            <p>Jl. Pasir Putih Ruko Villagio Residence<br>
                               Kp Jl. Kekupu No V4, RT.01/RW.06<br>
                               Kota Depok, Jawa Barat 16519</p>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Telepon</h3>
                            <p>082311323121</p>
                            <span class="contact-note">Tersedia 24/7 untuk konsultasi darurat</span>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Jam Operasional</h3>
                            <div class="schedule">
                                <div class="schedule-item">
                                    <span>Senin - Minggu</span>
                                    <span>07:00 - 22:00</span>
                                </div>
                                <div class="schedule-item">
                                    <span>Layanan Darurat</span>
                                    <span>24 Jam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>info@apotekpuripasirputih.com</p>
                            <span class="contact-note">Respon dalam 24 jam</span>
                        </div>
                    </div>
                </div>
                
                <div class="contact-map">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.714335207804!2d106.78156827475242!3d-6.430731593560378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e98291d63b85%3A0x261fac8d4b3fadac!2sApotek%20Puri%20Pasir%20Putih!5e0!3m2!1sid!2sid!4v1751217846113!5m2!1sid!2sid"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="brand-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="brand-text">
                        <h3>Apotek Puri Pasir Putih</h3>
                        <p>Kesehatan Anda, Prioritas Kami</p>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#products">Obat-obatan</a></li>
                        <li><a href="#services">Konsultasi</a></li>
                        <li><a href="#contact">Pengiriman</a></li>
                        <li><a href="#about">Pemeriksaan Kesehatan</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Kontak</h4>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>082311323121</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@apotekpuri.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Depok, Jawa Barat</span>
                    </div>
                </div>
                
                <div class="footer-social">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-telegram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-divider"></div>
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} Apotek Puri Pasir Putih. Semua hak dilindungi.</p>
                    <div class="footer-legal">
                        <a href="#">Kebijakan Privasi</a>
                        <a href="#">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-info-circle"></i>
                    Detail Produk
                </h2>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="product-detail">
                    <div class="product-image">
                        <div id="modalProductImage" class="image-container"></div>
                    </div>
                    
                    <div class="product-info">
                        <h3 id="modalProductName" class="product-name"></h3>
                        <div id="modalProductPrice" class="product-price"></div>
                        <div id="modalStockStatus" class="stock-status"></div>
                        <div class="product-description">
                            <h4>Deskripsi</h4>
                            <p id="modalProductDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal()">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </button>
                <button class="btn btn-primary" onclick="contactPharmacist()">
                    <i class="fas fa-phone"></i>
                    Konsultasi
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/pelanggan-modern.js') }}"></script>
</body>
</html>