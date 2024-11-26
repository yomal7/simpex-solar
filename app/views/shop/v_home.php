<?php require APPROOT.'/views/blog/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="slider">
            <div class="slides">
                <!-- Replace placeholder images with your actual banner images -->
                <div class="slide">
                    <img src="<?php echo URLROOT; ?>/public/assets/solar_panels.jpg" alt="Solar Panel Installation">
                    <div class="slide-content">
                        <h1>Power Your Future with Solar</h1>
                        <p>Premium Solar Panels for Your Home</p>
                        <button class="cta-button">Shop Now</button>
                    </div>
                </div>
                <div class="slide">
                    <img src="<?php echo URLROOT; ?>/public/assets/inverters.png" alt="Solar Inverters">
                    <div class="slide-content">
                        <h1>Smart Solar Inverters</h1>
                        <p>Maximum Efficiency for Your Solar System</p>
                        <button class="cta-button">Explore</button>
                    </div>
                </div>
                <div class="slide">
                    <img src="<?php echo URLROOT; ?>/public/assets/Batteries.png" alt="Solar Components">
                    <div class="slide-content">
                        <h1>Quality Components</h1>
                        <p>Complete Your Solar Installation</p>
                        <button class="cta-button">View Products</button>
                    </div>
                </div>
            </div>
            
            <button class="slider-btn prev">&#10094;</button>
            <button class="slider-btn next">&#10095;</button>
            
            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>

    <!-- Category Navigation -->
    <nav class="category-nav">
        <ul class="category-list">
            <li><button class="category-btn active" data-category="all">All Products</button></li>
            <li><button class="category-btn" data-category="panels">Solar Panels</button></li>
            <li><button class="category-btn" data-category="inverters">Inverters</button></li>
            <li><button class="category-btn" data-category="components">Components</button></li>
        </ul>
    </nav>

    <!-- Products Section -->
    <section class="products-section">
        <div class="products-grid">
            <!-- Solar Panel Product -->
            <div class="product-card" data-category="panels">
                <div class="product-image" style="background-image: url('/api/placeholder/400/320')">
                    <span class="product-label">Best Seller</span>
                </div>
                <div class="product-details">
                    <h3 class="product-title">Premium Solar Panel 400W</h3>
                    <p class="product-description">High-efficiency monocrystalline solar panel with 25-year warranty</p>
                    <div class="product-price">$299.99</div>
                    <div class="product-actions">
                        <button class="add-to-cart">Add to Cart</button>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inverter Product -->
            <div class="product-card" data-category="inverters">
                <div class="product-image" style="background-image: url('/api/placeholder/400/320')">
                    <span class="product-label">New</span>
                </div>
                <div class="product-details">
                    <h3 class="product-title">Smart Inverter 5kW</h3>
                    <p class="product-description">Grid-tied inverter with smart monitoring capabilities</p>
                    <div class="product-price">$799.99</div>
                    <div class="product-actions">
                        <button class="add-to-cart">Add to Cart</button>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Component Product -->
            <div class="product-card" data-category="components">
                <div class="product-image" style="background-image: url('/api/placeholder/400/320')">
                    <span class="product-label">Popular</span>
                </div>
                <div class="product-details">
                    <h3 class="product-title">Mounting System Kit</h3>
                    <p class="product-description">Complete roof mounting system for residential installation</p>
                    <div class="product-price">$149.99</div>
                    <div class="product-actions">
                        <button class="add-to-cart">Add to Cart</button>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add more product cards here -->
        </div>
    </section>



    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>
    <script src="<?php echo URLROOT; ?>/js/shop/home.js"></script>
<?php require APPROOT.'/views/shop/footer.php';?>