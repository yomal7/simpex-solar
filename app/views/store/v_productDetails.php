<?php require APPROOT . '/views/store/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/productDetails.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="product-container">
        <!-- Breadcrumb -->
        <div class="product-breadcrumb">
            <a href="<?php echo URLROOT; ?>/store">Home</a> /
            <a href="<?php echo URLROOT; ?>/store"><?php echo htmlspecialchars($data['product']->category); ?></a> /
            <span><?php echo htmlspecialchars($data['product']->name); ?></span>
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
                            onclick="changeImage(this.src, this)">
                    <?php endif; ?>
                    <?php if (!empty($data['product']->image2)): ?>
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image2; ?>"
                            alt="Thumbnail 2"
                            class="thumbnail"
                            onclick="changeImage(this.src, this)">
                    <?php endif; ?>
                    <?php if (!empty($data['product']->image3)): ?>
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image3; ?>"
                            alt="Thumbnail 3"
                            class="thumbnail"
                            onclick="changeImage(this.src, this)">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-header">
                    <h1 class="product-title"><?php echo htmlspecialchars($data['product']->name); ?></h1>
                    <div class="product-meta">
                        <span><?php echo htmlspecialchars($data['product']->category); ?></span>
                        <span><?php echo htmlspecialchars($data['product']->supplier_name); ?></span>
                    </div>
                </div>

                <div class="product-price">Rs. <?php echo number_format($data['product']->price, 2); ?></div>

                <p class="product-description">
                    <?php echo nl2br(htmlspecialchars($data['product']->description)); ?>
                </p>

                <ul class="product-features">
                    <?php if (!empty($data['features'])): ?>
                        <?php foreach ($data['features'] as $feature): ?>
                            <li><?php echo htmlspecialchars($feature->feature); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($data['product']->blog_link)): ?>
                    <div class="blog-link">
                        <a href="<?php echo htmlspecialchars($data['product']->blog_link); ?>" target="_blank">
                            Read More About This Product
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Add to Cart Form -->
                <form action="<?php echo URLROOT; ?>/store/addToCart" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">

                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-control">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="99">
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            element.classList.add('active');
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function increaseQuantity() {
            const input = document.getElementById('quantity');
            if (input.value < 99) {
                input.value = parseInt(input.value) + 1;
            }
        }
    </script>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>