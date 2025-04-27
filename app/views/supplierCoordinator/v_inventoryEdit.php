<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/edit_inventory.css">
</head>

<body data-user-role="supplierCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

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

        <!-- v_inventoryEdit.php -->
        <div class="content-wrapper">
            <div class="page-header">
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="back-btn">
                    <span class="material-icons-sharp">arrow_back</span> Back to Inventory
                </a>
                <h2>Edit Product</h2>
            </div>

            <div class="edit-container">
                <!-- Left Column - Product Info -->
                <div class="product-info-section">
                    <div class="product-info">
                        <h2><?php echo htmlspecialchars($data['name']); ?></h2>
                    </div>
                    <div class="product-image-container">
                        <?php if (!empty($data['image_path'])): ?>
                            <img src="<?php echo URLROOT . '/public/uploads/images/' . $data['image_path']; ?>"
                                alt="<?php echo htmlspecialchars($data['name']); ?>"
                                class="product-image">
                        <?php else: ?>
                            <div class="no-image">
                                <span class="material-icons-sharp">image_not_available</span>
                                <p>No image available</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Right Column - Edit Form -->
                <div class="edit-form-section">
                    <form action="<?php echo URLROOT; ?>/supplierCoordinator/updateInventory" method="POST" class="edit-form">
                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="form-group">
                            <label>Supplier <span class="required">*</span></label>
                            <select name="supplier" class="form-control <?php echo (!empty($data['supplier_err'])) ? 'is-invalid' : ''; ?>">
                                <?php foreach ($data['suppliers'] as $supplier): ?>
                                    <option value="<?php echo $supplier->id; ?>"
                                        <?php echo ($data['supplier_id'] == $supplier->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($supplier->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['supplier_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Price (Rs.) <span class="required">*</span></label>
                            <input type="number" step="0.01" name="price"
                                class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['price']; ?>">
                            <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" name="quantity"
                                class="form-control <?php echo (!empty($data['quantity_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['quantity']; ?>">
                            <span class="invalid-feedback"><?php echo $data['quantity_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Blog Link <span class="required">*</span></label>
                            <input type="url" name="blog_link"
                                class="form-control <?php echo (!empty($data['blog_link_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['blog_link']; ?>">
                            <span class="invalid-feedback"><?php echo $data['blog_link_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="2"
                                class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['description']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">

                                Save Changes
                            </button>
                            <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="btn btn-secondary">

                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/edit_inventory.js"></script>
    <script src="<?php echo URLROOT; ?>/js/inventory.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>