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
            <div class="logo">Medik</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Your Health, Our Priority</h1>
        <p>Premium medical products and equipment for healthcare professionals</p>
        <button class="cta-btn">Explore Products</button>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <h2 class="section-title">Our Products</h2>
        <p class="section-subtitle">High-quality medical products for all your healthcare needs</p>

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
