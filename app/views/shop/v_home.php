<?php require APPROOT . '/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/home.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
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
        <ul>
            <li><button class="category-btn active" data-category="all">All Products</button></li>
            <li><button class="category-btn" data-category="Solar Panel">Solar Panels</button></li>
            <li><button class="category-btn" data-category="Inverters">Inverters</button></li>
            <li><button class="category-btn" data-category="Components">Components</button></li>
        </ul>
    </nav>

    <!-- Products Section -->
    <section class="products-section">
        <div class="products-grid">
            <?php if (!empty($data['products'])): ?>
                <?php foreach ($data['products'] as $product): ?>
                    <div class="product-card" data-category="<?php echo htmlspecialchars($product->category); ?>">
                        <div class="product-image" style="background-image: url('<?php echo !empty($product->image1) ? URLROOT . '/public/uploads/images/' . $product->image1 : URLROOT . '/public/assets/default-product.png'; ?>')">
                            <span class="product-label"><?php echo htmlspecialchars($product->category); ?></span>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($product->name); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($product->description); ?></p>
                            <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                            <div class="product-actions">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="view-details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <p>No products available in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryBtns = document.querySelectorAll('.category-btn');
            const productCards = document.querySelectorAll('.product-card');

            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.dataset.category;

                    // Update active button
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    // Filter products
                    productCards.forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>


    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <script src="<?php echo URLROOT; ?>/js/shop/home.js"></script>
    <?php require APPROOT . '/views/shop/footer.php'; ?>