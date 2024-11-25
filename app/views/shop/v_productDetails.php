<?php require APPROOT.'/views/blog/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/productDetails.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
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
                <img src="<?php echo URLROOT; ?>/public/assets/solarPanel1.jpg" alt="Solar Panel 400W" class="main-image" id="mainImage">
                <div class="thumbnail-container">
                    <img src="<?php echo URLROOT; ?>/public/assets/solarPanel1.jpg" alt="Thumbnail 1" class="thumbnail active">
                    <img src="<?php echo URLROOT; ?>/public/assets/solarPanel2.jpeg" alt="Thumbnail 2" class="thumbnail">
                    <img src="<?php echo URLROOT; ?>/public/assets/solarPanel3.png" alt="Thumbnail 3" class="thumbnail">
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-header">
                    <h1 class="product-title">Premium Solar Panel 400W</h1>
                    <div class="product-meta">
                        <span><i class="fas fa-tags"></i> Solar Panels</span>
                        <span><i class="fas fa-building"></i> SolarTech Inc.</span>
                    </div>
                </div>

                <div class="product-price">$299.99</div>

                <p class="product-description">
                    High-efficiency monocrystalline solar panel with advanced technology for maximum power output. 
                    Perfect for residential and commercial installations.
                </p>

                <ul class="product-features">
                    <li><i class="fas fa-check-circle"></i> 25-year warranty</li>
                    <li><i class="fas fa-check-circle"></i> 400W peak power output</li>
                    <li><i class="fas fa-check-circle"></i> Anti-reflective coating</li>
                    <li><i class="fas fa-check-circle"></i> Weather-resistant</li>
                </ul>

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

    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>

    <script src="<?php echo URLROOT; ?>/js/shop/productDetails.js"></script>
<?php require APPROOT.'/views/shop/footer.php';?>