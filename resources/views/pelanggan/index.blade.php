<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apotek Puri</title>
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo">Puri Pasir Putih</div>

        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Kesehatan Anda, Prioritas Kami.</h1>
        <p>Obat berkualitas tinggi untuk memenuhi semua kebutuhan kesehatan Anda.</p>
        <a href="#products" class="cta-btn">Jelajahi produk</a>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <h2 class="section-title">Produk</h2>
        <p class="section-subtitle">Produk medis berkualitas tinggi untuk semua kebutuhan kesehatan Anda.</p>

        <!-- Search Box -->
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari obat...">
            <button id="searchButton" class="search-button">Cari</button>
        </div>

        <div class="products-grid" id="productsGrid">
            <!-- Products will be dynamically loaded here -->
        </div>
    </section>

    <!-- Product Detail Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Product Details</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalProductImage" class="modal-product-image"></div>
                <h3 id="modalProductName" class="modal-product-name"></h3>
                <div id="modalProductPrice" class="modal-product-price"></div>
                <div id="modalStockStatus" class="stock-status"></div>
                <p id="modalProductDescription" class="modal-description"></p>
                <button class="back-btn" onclick="closeModal()">Back</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/pelanggan.js') }}"></script>
</body>

</html>
