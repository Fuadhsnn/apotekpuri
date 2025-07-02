<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apotek Puri</title>
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo"><i></i> Apotek Puri Pasir Putih</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#products"><i class="fas fa-pills"></i> Produk</a></li>
                    <li><a href="#about"><i class="fas fa-info-circle"></i> Tentang Kami</a></li>
                    <li><a href="#contact"><i class="fas fa-phone"></i> Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Make You Healthier</h1>
            <p>Obat berkualitas untuk memenuhi semua kebutuhan kesehatan Anda.</p>
            <a href="#products" class="cta-btn"> Jelajahi produk</a>
        </div>

        <!-- Products Section -->
        <section class="products-section" id="products">
            <h2 class="section-title">Produk <i class="fas fa-pills"></i></h2>

            <!-- Search Box -->
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Cari obat...">
                <button id="searchButton" class="search-button"><i class="fas fa-search"></i> Cari</button>
            </div>

            <div class="products-grid" id="productsGrid">
                <!-- Products will be dynamically loaded here -->
                <div class="loading-animation">
                    <i class="fas fa-spinner fa-pulse"></i>
                    <p>Memuat produk...</p>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section" id="about">
            <div class="about-container">
                <h2 class="section-title">Tentang Kami </h2>
                <div class="about-content">
                    <div class="about-image">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <div class="about-text">
                        <h3>Apotek Puri Pasir Putih</h3>
                        <p>Apotek Puri Pasir Putih adalah apotek terpercaya yang telah melayani masyarakat selama
                            bertahun-tahun. Kami berkomitmen untuk menyediakan obat-obatan berkualitas tinggi dan
                            layanan kesehatan yang terbaik untuk semua pelanggan kami.</p>
                        <div class="about-features">
                            <div class="feature">
                                <i class="fas fa-certificate"></i>
                                <h4>Produk Berkualitas</h4>
                                <p>Semua produk kami dijamin keaslian dan kualitasnya</p>
                            </div>
                            <div class="feature">
                                <i class="fas fa-user-md"></i>
                                <h4>Konsultasi Gratis</h4>
                                <p>Konsultasikan kebutuhan kesehatan Anda dengan apoteker kami</p>
                            </div>
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <h4>Pelayanan Ramah</h4>
                                <p>Layanan ramah dan profesional</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" id="contact">
            <h2 class="section-title">Hubungi Kami <i class="fas fa-phone"></i></h2>
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Alamat</h3>
                            <p>Jl. Pasir Putih Ruko Villagio Residence, Jl. Raya Pasir Putih, Kp Jl. Kekupu No V4,
                                RT.01/RW.06, Kota Depok, Jawa Barat 16519</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Telepon</h3>
                            <p>082311323121</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p>info@apotekpuri.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h3>Jam Operasional</h3>
                            <p>Setiap hari : 07.00 - 22.00</p>
                        </div>
                    </div>
                </div>
                <div class="contact-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.714335207804!2d106.78156827475242!3d-6.430731593560378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e98291d63b85%3A0x261fac8d4b3fadac!2sApotek%20Puri%20Pasir%20Putih!5e0!3m2!1sid!2sid!4v1751217846113!5m2!1sid!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <!-- Product Detail Modal -->
        <div id="productModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><i class="fas fa-info-circle"></i> Detail Produk</h2>
                    <button class="close-btn" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="modalProductImage" class="modal-product-image"></div>
                    <h3 id="modalProductName" class="modal-product-name"></h3>
                    <div id="modalProductPrice" class="modal-product-price"></div>
                    <div id="modalStockStatus" class="stock-status"></div>
                    <p id="modalProductDescription" class="modal-description"></p>
                    <button class="back-btn" onclick="closeModal()"><i class="fas fa-arrow-left"></i>
                        Kembali</button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-logo">
                    <p>Make You Healthier</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Apotek Puri Pasir Putih. All Rights Reserved.</p>
            </div>
        </footer>

        <script src="{{ asset('js/pelanggan.js') }}"></script>
</body>

</html>
