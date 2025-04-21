<?php require APPROOT . '/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/home.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="slider">
            <div class="slides">
                <div class="slide active">
                    <img src="<?php echo URLROOT; ?>/public/assets/solar_panels.jpg" alt="Solar Panel Installation">
                    <div class="slide-content">
                        <h1>Power Your Future with Solar</h1>
                        <p>Premium Solar Panels for Your Home</p>
                        <button class="cta-button" onclick="location.href='#products'">Shop Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="category-section">
        <nav class="category-nav">
            <ul>
                <li><button class="category-btn active" data-category="all">All Products</button></li>
                <li><button class="category-btn" data-category="Solar Panel">Solar Panels</button></li>
                <li><button class="category-btn" data-category="Inverters">Inverters</button></li>
                <li><button class="category-btn" data-category="Components">Components</button></li>
            </ul>
        </nav>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="products-grid">
            <?php if (!empty($data['products'])): ?>
                <?php foreach ($data['products'] as $product): ?>
                    <div class="product-card" data-category="<?php echo htmlspecialchars($product->category); ?>">
                        <div class="product-image">
                            <img src="<?php echo !empty($product->image1) ? URLROOT . '/public/uploads/store/' . $product->image1 : URLROOT . '/public/assets/default-product.png'; ?>"
                                alt="<?php echo htmlspecialchars($product->name); ?>">
                            <span class="product-label"><?php echo htmlspecialchars($product->category); ?></span>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($product->name); ?></h3>
                            <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                            <div class="product-actions">
                                <a href="<?php echo URLROOT; ?>/store/product/<?php echo $product->id; ?>" class="view-details">View Details</a>
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
        // Category filtering
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const category = btn.dataset.category;
                document.querySelectorAll('.product-card').forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>