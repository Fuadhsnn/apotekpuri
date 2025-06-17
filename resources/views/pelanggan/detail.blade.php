<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $obat->nama_obat }} - Apotek Puri</title>
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo">Medik</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="/#about">About</a></li>
                    <li><a href="/#products">Products</a></li>
                    <li><a href="/#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Product Detail Section -->
    <div class="product-detail-container">
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
                    <p class="product-detail-code">Kode: {{ $obat->kode_obat }}</p>
                    <p class="product-detail-category">Kategori: {{ $obat->kategori ?: '-' }}</p>
                </div>
                <div class="product-detail-price">
                    Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                </div>
                <div class="product-detail-stock {{ $obat->stok > 0 ? 'in-stock' : 'out-of-stock' }}">
                    <span class="stock-icon"></span>
                    {{ $obat->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                </div>
                <div class="product-detail-description">
                    <h2>Deskripsi:</h2>
                    <p>{{ $obat->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>