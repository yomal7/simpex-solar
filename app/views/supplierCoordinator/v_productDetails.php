<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/inventory.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar remains the same -->
        <div class="sidebar" id="sidebar">
            <img

                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory" class="active">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/chat">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="product-details-container">
                <div class="product-header">
                    <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="back-btn">
                        <span class="material-icons-sharp">arrow_back</span> Back to Inventory
                    </a>
                    <h2>Product Details</h2>
                </div>

                <div class="product-info-grid">
                    <!-- Product Image Section -->
                    <div class="product-image-section">
                        <?php if (!empty($data['inventory']->image)): ?>
                            <img src="<?php echo URLROOT; ?>/uploads/images/<?php echo $data['inventory']->image; ?>"
                                alt="<?php echo htmlspecialchars($data['inventory']->name); ?>"
                                class="product-image">
                        <?php else: ?>
                            <div class="no-image">
                                <span class="material-icons-sharp">image_not_available</span>
                                <p>No image available</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Details Section -->
                    <div class="product-info">
                        <div class="info-group">
                            <h3>Basic Information</h3>
                            <div class="detail-row">
                                <span class="label">Product Name:</span>
                                <span class="value"><?php echo htmlspecialchars($data['inventory']->name ?? 'N/A'); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Supplier:</span>
                                <span class="value"><?php echo htmlspecialchars($data['inventory']->supplier_name ?? 'N/A'); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Price:</span>
                                <span class="value">$<?php echo number_format($data['inventory']->price ?? 0, 2); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Quantity:</span>
                                <span class="value"><?php echo $data['inventory']->quantity ?? 0; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Status:</span>
                                <span class="status <?php echo $data['inventory']->quantity == 0 ? 'out-of-stock' : ($data['inventory']->quantity < 10 ? 'low-stock' : 'in-stock'); ?>">
                                    <?php echo $data['inventory']->quantity == 0 ? 'Out of Stock' : ($data['inventory']->quantity < 10 ? 'Low Stock' : 'In Stock'); ?>
                                </span>
                            </div>
                            <?php if (!empty($data['inventory']->blog_link)): ?>
                                <div class="detail-row">
                                    <span class="label">Product Blog:</span>
                                    <a href="<?php echo htmlspecialchars($data['inventory']->blog_link); ?>"
                                        target="_blank"
                                        class="blog-link">
                                        <span class="material-icons-sharp">link</span>
                                        Read More
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>
</body>

</html>