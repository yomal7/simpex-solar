<?php require APPROOT . '/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/productDetails.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <div class="product-container">
        <!-- Breadcrumb -->
        <div class="product-breadcrumb">
            <a href="#">Home</a> /
            <a href="#">Solar Panels</a> /
            <span>Premium Solar Panel 400W</span>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo !empty($data['product']->image1) ? URLROOT . '/public/uploads/store/' . $data['product']->image1 : URLROOT . '/public/assets/default-product.png'; ?>"
                        alt="<?php echo htmlspecialchars($data['product']->name); ?>"
                        id="mainImage">
                </div>
                <div class="thumbnails">
                    <?php if (!empty($data['product']->image1)): ?>
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image1; ?>"
                            alt="Thumbnail 1"
                            class="thumbnail active"
                            onclick="changeImage(this.src)">
                    <?php endif; ?>
                    <?php if (!empty($data['product']->image2)): ?>
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image2; ?>"
                            alt="Thumbnail 2"
                            class="thumbnail"
                            onclick="changeImage(this.src)">
                    <?php endif; ?>
                    <?php if (!empty($data['product']->image3)): ?>
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image3; ?>"
                            alt="Thumbnail 3"
                            class="thumbnail"
                            onclick="changeImage(this.src)">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-header">
                    <h1 class="product-title"><?php echo htmlspecialchars($data['product']->name); ?></h1>
                    <div class="product-meta">
                        <span><i class="fas fa-tags"></i> <?php echo htmlspecialchars($data['product']->category); ?></span>
                        <span><i class="fas fa-building"></i> <?php echo htmlspecialchars($data['product']->supplier_name); ?></span>
                    </div>
                </div>

                <div class="product-price">Rs. <?php echo number_format($data['product']->price, 2); ?></div>

                <p class="product-description">
                    <?php echo htmlspecialchars($data['product']->description); ?>
                </p>

                <ul class="product-features">
                    <?php if (!empty($data['features'])): ?>
                        <?php foreach ($data['features'] as $feature): ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($feature->feature); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($data['product']->blog_link)): ?>
                    <div class="blog-link">
                        <a href="<?php echo htmlspecialchars($data['product']->blog_link); ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Read More About This Product
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="delivery-options">
                <h3>Select Delivery Option</h3>
                <div class="delivery-option" data-option="deliver">
                    <input type="radio" name="delivery" id="deliverOnly">
                    <label for="deliverOnly">
                        <strong>Delivery Included</strong>
                        <p>Standard delivery to your location</p>
                    </label>
                </div>
                <div class="delivery-option" data-option="install">
                    <input type="radio" name="delivery" id="deliverInstall">
                    <label for="deliverInstall">
                        <strong>Delivery not included</strong>
                        <p>Pickup from the store</p>
                    </label>
                </div>
            </div>

            <div class="quantity-selector">
                <span>Quantity:</span>
                <div class="quantity-control">
                    <i class="fas fa-minus" id="decreaseQuantity"></i>
                    <input type="number" value="1" min="1" id="quantity">
                    <i class="fas fa-plus" id="increaseQuantity"></i>
                </div>
            </div>

            <button class="purchase-request" id="purchaseRequest">
                <i class="fas fa-paper-plane"></i>
                Request to Purchase
            </button>
        </div>
    </div>

    <!-- Additional Details -->
    <div class="product-details">
        <div class="detail-card">
            <h3><i class="fas fa-truck"></i> Shipping Information</h3>
            <p>Free shipping for orders above $1000. Standard delivery time is 5-7 business days. Professional installation available.</p>
        </div>
        <div class="detail-card">
            <h3><i class="fas fa-book"></i> Related Resources</h3>
            <p>Learn more about solar panel installation and maintenance in our blog.</p>
            <a href="#" class="blog-link">
                Read our installation guide
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    </div>

    <!-- Success Message -->
    <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i>
        Purchase request submitted successfully!
    </div>
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;

            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
                if (thumb.src === src) {
                    thumb.classList.add('active');
                }
            });
        }
    </script>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>

    <script src="<?php echo URLROOT; ?>/js/shop/productDetails.js"></script>
    <?php require APPROOT . '/views/shop/footer.php'; ?>