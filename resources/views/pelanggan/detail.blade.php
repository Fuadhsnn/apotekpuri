<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $obat->nama_obat }} - Apotek Puri</title>
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Detail Page Specific Styles */
        .product-detail-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .product-detail-header {
            background: linear-gradient(135deg, #4a90e2, #2c5aa0);
            color: white;
            padding: 20px 30px;
            position: relative;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        .back-link i {
            margin-right: 8px;
        }

        .product-detail-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }

        .product-detail-left {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .product-detail-image {
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-detail-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-detail-name {
            color: #2c5aa0;
            margin-bottom: 15px;
            font-size: 2rem;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
        }

        .product-detail-meta {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .product-detail-code,
        .product-detail-category {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .product-detail-code i,
        .product-detail-category i {
            margin-right: 10px;
            color: #4a90e2;
            width: 20px;
            text-align: center;
        }

        .product-detail-price {
            font-size: 1.8rem;
            color: #2c5aa0;
            font-weight: bold;
            margin: 20px 0;
            display: flex;
            align-items: center;
        }

        .product-detail-price i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        .product-detail-stock {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .product-detail-description {
            margin-top: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .product-detail-description h2 {
            font-size: 1.2rem;
            color: #2c5aa0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .product-detail-description h2 i {
            margin-right: 10px;
        }

        .product-detail-description p {
            color: #555;
            line-height: 1.6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-detail-content {
                grid-template-columns: 1fr;
            }
        }

        /* Footer Styles */
        .footer {
            background: #2c5aa0;
            color: white;
            padding: 40px 0 0;
            margin-top: 60px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .footer-logo .logo {
            margin-bottom: 15px;
        }

        .footer h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
        }

        .footer h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 50px;
            height: 2px;
            background: #4a90e2;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-contact p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .footer-contact i {
            margin-right: 10px;
            color: #4a90e2;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: background 0.3s, transform 0.3s;
        }

        .social-icon:hover {
            background: #4a90e2;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo"><i></i> Apotek Puri Pasir Putih</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('pelanggan.index') }}#products"><i class="fas fa-pills"></i> Produk</a></li>
                    <li><a href="{{ route('pelanggan.index') }}#about"><i class="fas fa-info-circle"></i> Tentang
                            Kami</a></li>
                    <li><a href="{{ route('pelanggan.index') }}#contact"><i class="fas fa-phone"></i> Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Product Detail Section -->
    <div class="product-detail-container">
        <div class="product-detail-header">
            <a href="{{ route('pelanggan.index') }}" class="back-link"><i class="fas fa-arrow-left"></i>Kembali</a>
            <h1><i class="fas fa-pills"></i> Detail Produk</h1>
        </div>
        <div class="product-detail-content">
            <div class="product-detail-left">
                <div class="product-detail-image">
                    <img src="{{ $obat->gambar ? asset('storage/' . $obat->gambar) : asset('default-product.jpg') }}"
                        alt="{{ $obat->nama_obat }}">
                </div>
            </div>
            <div class="product-detail-right">
                <h1 class="product-detail-name">{{ $obat->nama_obat }}</h1>
                <div class="product-detail-meta">
                    <p class="product-detail-code"><i class="fas fa-barcode"></i> Kode: {{ $obat->kode_obat }}</p>
                    <p class="product-detail-category"><i class="fas fa-tags"></i> Kategori:
                        {{ $obat->kategori ?: '-' }}</p>
                    @if ($obat->jenis_obat)
                        <p class="product-detail-category"><i class="fas fa-capsules"></i> Jenis:
                            {{ $obat->jenis_obat }}</p>
                    @endif
                    @if ($obat->tanggal_kadaluarsa)
                        <p class="product-detail-category"><i class="fas fa-calendar-alt"></i> Kadaluarsa:
                            {{ $obat->tanggal_kadaluarsa->format('d/m/Y') }}</p>
                    @endif
                </div>
                <div class="product-detail-price">
                    <i class="fas fa-tag"></i> Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                </div>
                <div class="product-detail-stock {{ $obat->stok > 0 ? 'in-stock' : 'out-of-stock' }}">
                    <span class="stock-icon"></span>
                    {{ $obat->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                </div>
                <div class="product-detail-description">
                    <h2><i class="fas fa-info-circle"></i> Deskripsi Produk</h2>
                    <p>{{ $obat->deskripsi ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <div class="logo"><i></i> Apotek Puri Pasir Putih</div>
                <p>Kesehatan Anda, Prioritas Kami.</p>
            </div>
            <div class="footer-links">
                <h3>Tautan Cepat</h3>
                <ul>
                    <li><a href="{{ route('pelanggan.index') }}#products">Produk</a></li>
                    <li><a href="{{ route('pelanggan.index') }}#about">Tentang Kami</a></li>
                    <li><a href="{{ route('pelanggan.index') }}#contact">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Kontak</h3>
                <p><i class="fas fa-map-marker-alt"></i> Jl. Pasir Putih No. 123, Jakarta Timur</p>
                <p><i class="fas fa-phone"></i> (021) 1234-5678</p>
                <p><i class="fas fa-envelope"></i> info@apotekpuri.com</p>
            </div>
            <div class="footer-social">
                <h3>Ikuti Kami</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Apotek Puri Pasir Putih. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>
