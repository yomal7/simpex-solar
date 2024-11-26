<?php require APPROOT . '/views/shop/header.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<div class="container">
    <h1><?php echo isset($data['title']) ? htmlspecialchars($data['title']) : 'Online Store'; ?></h1>

    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($data['error']); ?>
        </div>
    <?php endif; ?>

    <div class="mini-navbar">
        <button class="nav-btn <?php echo (!isset($data['category']) || $data['category'] === 'all') ? 'active' : ''; ?>"
            data-category="all">All Products</button>
        <button class="nav-btn <?php echo (isset($data['category']) && $data['category'] === 'Solar Panels') ? 'active' : ''; ?>"
            data-category="Solar Panels">Solar Panels</button>
        <button class="nav-btn <?php echo (isset($data['category']) && $data['category'] === 'Inventers') ? 'active' : ''; ?>"
            data-category="Inventers">Inventers</button>
        <button class="nav-btn <?php echo (isset($data['category']) && $data['category'] === 'Additional Components') ? 'active' : ''; ?>"
            data-category="Additional Components">Additional Components</button>
    </div>

    <div class="packages-grid" id="packagesContainer">
        <?php if (!empty($data['products'])): ?>
            <?php foreach ($data['products'] as $product): ?>
                <div class="package-card">
                    <div class="package-image-container">
                        <!-- <img src="<?php echo URLROOT; ?>/assets/products/<?php echo htmlspecialchars($product->id); ?>.jpg"
                            alt="<?php echo htmlspecialchars($product->name); ?>"
                            class="package-image"> -->
                        <img src="<?php echo URLROOT; ?>/assets/image1.png ?>"
                            alt="<?php echo htmlspecialchars($product->name); ?>"
                            class="package-image">
                        alt="<?php echo htmlspecialchars($product->name); ?>"
                        class="package-image">
                    </div>
                    <h3 class="package-name"><?php echo htmlspecialchars($product->name); ?></h3>
                    <p class="package-category"><?php echo htmlspecialchars($product->category); ?></p>
                    <p class="package-price">$<?php echo number_format($product->price, 2); ?></p>
                    <a href="<?php echo URLROOT; ?>/shop/detail/<?php echo $product->id; ?>" class="learn-more-btn">
                        More Details
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.75 6.75L19.25 12L13.75 17.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M19 12H4.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">No products found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT . '/views/shop/footer.php'; ?>