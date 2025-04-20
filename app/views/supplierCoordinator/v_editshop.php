<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/add_shop.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img

                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop" class="active">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
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

        <div class="content-wrapper">
            <div class="main-content">
                <!-- Update v_addProducts.php back button -->
                <div class="page-header">
                    <a href="<?php echo URLROOT; ?>/supplierCoordinator/shop" class="back-btn">
                        <span class="material-icons-sharp">arrow_back</span> Back to Shop
                    </a>
                    <h2>Edit Product Details</h2>
                </div>

                <?php flash('product_message'); ?>

                <form action="<?php echo URLROOT; ?>/supplierCoordinator/updateShopProduct" method="POST" class="product-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Product Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['name'] ?? ''; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Price (Rs.) <span class="required">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['price'] ?? ''; ?>">
                            <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Category <span class="required">*</span></label>
                            <select name="category" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Category</option>
                                <option value="Solar Panel" <?php echo ($data['category'] == 'Solar Panel') ? 'selected' : ''; ?>>Solar Panel</option>
                                <option value="Inverters" <?php echo ($data['category'] == 'Inverters') ? 'selected' : ''; ?>>Inverters</option>
                                <option value="Components" <?php echo ($data['category'] == 'Components') ? 'selected' : ''; ?>>Components</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['category_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Supplier <span class="required">*</span></label>
                            <select name="supplier_id" class="form-control <?php echo (!empty($data['supplier_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Supplier</option>
                                <?php foreach ($data['suppliers'] as $supplier): ?>
                                    <option value="<?php echo $supplier->id; ?>"
                                        <?php echo ($data['supplier_id'] == $supplier->id) ? 'selected' : ''; ?>>
                                        <?php echo $supplier->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['supplier_err']; ?></span>
                        </div>

                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="4" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['description'] ?? ''; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Blog Link</label>
                            <input type="url" name="blog_link" class="form-control <?php echo (!empty($data['blog_link_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['blog_link'] ?? ''; ?>">
                            <!-- <span class="invalid-feedback"><?php echo $data['blog_link_err']; ?></span> -->
                        </div>

                        <div class="form-group">
                            <label>Product Images</label>
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <div class="image-field">
                                    <?php if (!empty($data['image' . $i])): ?>
                                        <div class="current-image-preview">
                                            <img src="<?php echo URLROOT . '/public/uploads/images/' . $data['image' . $i]; ?>"
                                                alt="Current Image <?php echo $i; ?>"
                                                class="preview-img">
                                            <p>Current Image <?php echo $i; ?>: <?php echo $data['image' . $i]; ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="image<?php echo $i; ?>" accept="image/*">
                                    <!-- Hidden input to retain current image if no new one is uploaded -->
                                    <input type="hidden" name="current_image<?php echo $i; ?>"
                                        value="<?php echo $data['image' . $i] ?? ''; ?>">
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="form-group full-width features-section">
                            <label>Product Features</label>
                            <div class="features-container">
                                <div class="feature-input">
                                    <input type="text"
                                        name="features[]"
                                        class="form-control <?php echo (!empty($data['features_err'])) ? 'is-invalid' : ''; ?>"
                                        placeholder="Feature 1 (Required)"
                                        value="<?php echo isset($data['features'][0]) ? $data['features'][0] : ''; ?>"
                                        required>
                                    <span class="required">*</span>
                                </div>
                                <div class="feature-input">
                                    <input type="text"
                                        name="features[]"
                                        class="form-control <?php echo (!empty($data['features_err'])) ? 'is-invalid' : ''; ?>"
                                        placeholder="Feature 2 (Required)"
                                        value="<?php echo isset($data['features'][1]) ? $data['features'][1] : ''; ?>"
                                        required>
                                    <span class="required">*</span>
                                </div>
                                <div class="feature-input">
                                    <input type="text"
                                        name="features[]"
                                        placeholder="Feature 3 (Optional)"
                                        value="<?php echo isset($data['features'][2]) ? $data['features'][2] : ''; ?>"
                                        class="form-control">
                                </div>
                                <div class="feature-input">
                                    <input type="text"
                                        name="features[]"
                                        placeholder="Feature 4 (Optional)"
                                        value="<?php echo isset($data['features'][3]) ? $data['features'][3] : ''; ?>"
                                        class="form-control">
                                </div>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['features_err'] ?? ''; ?></span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Add Product</button>
                            <a href="<?php echo URLROOT; ?>/supplierCoordinator/shop" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/shop.js"></script>
    <!-- <script src="<?php echo URLROOT; ?>/js/inventory.js"></script> -->
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>