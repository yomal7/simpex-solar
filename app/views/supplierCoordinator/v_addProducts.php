<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/add_products.css">
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
                    <p>HR Administrator</p>
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

        <div class="content-wrapper">
            <div class="main-content">
                <!-- Update v_addProducts.php back button -->
                <div class="page-header">
                    <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="back-btn">
                        <span class="material-icons-sharp">arrow_back</span> Back to Inventory
                    </a>
                    <h2>Add New Product</h2>
                </div>

                <?php flash('product_message'); ?>

                <form action="<?php echo URLROOT; ?>/supplierCoordinator/addProduct" method="POST" class="inventory-form" enctype="multipart/form-data">
                    <div class="form-grid">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" required>
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>

                        <!-- Supplier -->
                        <div class="form-group">
                            <label for="supplier">Supplier <span class="required">*</span></label>
                            <select id="supplier" name="supplier" class="form-control <?php echo (!empty($data['supplier_err'])) ? 'is-invalid' : ''; ?>" required>
                                <option value="">Select a Supplier</option>
                                <?php foreach ($data['suppliers'] as $supplier) { ?>
                                    <option value="<?php echo $supplier->id; ?>" <?php echo ($data['supplier_id'] == $supplier->id) ? 'selected' : ''; ?>>
                                        <?php echo $supplier->name; ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <span class="invalid-feedback"><?php echo $data['supplier_err']; ?></span>
                        </div>

                        <!-- Price -->
                        <div class="form-group">
                            <label for="price">Price <span class="required">*</span></label>
                            <input type="text" id="price" name="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>" required>
                            <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity">Quantity <span class="required">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control <?php echo (!empty($data['quantity_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['quantity']; ?>" required>
                            <span class="invalid-feedback"><?php echo $data['quantity_err']; ?></span>
                        </div>

                        <!-- Blog Link -->
                        <div class="form-group">
                            <label for="blog_link">Blog Link <span class="required">*</span></label>
                            <input type="url" id="blog_link" name="blog_link" class="form-control <?php echo (!empty($data['blog_link_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['blog_link']; ?>" required>
                            <span class="invalid-feedback"><?php echo $data['blog_link_err']; ?></span>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image_path">Upload Image <span class="required">*</span></label>
                            <input type="file" id="image_path" name="image_path" class="form-control-file <?php echo (!empty($data['image_path_err'])) ? 'is-invalid' : ''; ?>" required>
                            <span class="invalid-feedback"><?php echo $data['image_path_err']; ?></span>
                        </div>

                        <!-- Description -->
                        <div class="form-group full-width">
                            <label for="description">Description <span class="required">*</span></label>
                            <textarea id="description" name="description" rows="4" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['description']; ?>" required></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn reset-btn">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-save"></i> Save Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/add_products.js"></script>
    <script src="<?php echo URLROOT; ?>/js/inventory.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>