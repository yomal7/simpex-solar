<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/inventory.css">
</head>

<body data-user-role="supplierCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar remains the same -->
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Supplier Coordinator</p>
                </div>
            </div>
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
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