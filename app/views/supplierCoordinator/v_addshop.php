<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/add_products.css">
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
            <a href="<?php echo APPROOT; ?>/views/supplierCoordinator/v_dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#" class="active">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="content-wrapper">
            <div class="page-header">
                <h1>Add New Product</h1>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/shop" class="btn back-btn">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <?php flash('product_message'); ?>

            <form action="<?php echo URLROOT; ?>/supplierCoordinator/addShop" method="POST" class="inventory-form" enctype="multipart/form-data">
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

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category">Category <span class="required">*</span></label>
                        <select id="category" name="category" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>" required>
                            <option value="">Select a Category</option>
                            <?php foreach ($data['categories'] as $category) { ?>
                                <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                    <?php echo $category->category; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['category_err']; ?></span>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price <span class="required">*</span></label>
                        <input type="number" step="0.01" id="price" name="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
                    </div>

                    <!-- Blog Link -->
                    <div class="form-group">
                        <label for="blog_link">Blog Link <span class="required">*</span></label>
                        <input type="url" id="blog_link" name="blog_link" class="form-control <?php echo (!empty($data['blog_link_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['blog_link']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['blog_link_err']; ?></span>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="image1">Image 1 <span class="required">*</span></label>
                        <input type="file" id="image1" name="image1" class="form-control-file <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="image2">Image 2 <span class="required">*</span></label>
                        <input type="file" id="image2" name="image2" class="form-control-file <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="image3">Image 3 (Optional)</label>
                        <input type="file" id="image3" name="image3" class="form-control-file">
                    </div>

                    <!-- Description -->
                    <div class="form-group full-width">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" rows="2" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" required><?php echo $data['description']; ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                    </div>

                    <!-- Feature 1 (Required) -->
                    <div class="form-group ">
                        <label for="feature1">Feature 1 <span class="required">*</span></label>
                        <textarea id="feature1" name="feature1" rows="1" class="form-control" required><?php echo $data['feature1']; ?></textarea>
                    </div>

                    <!-- Feature 2 (Required) -->
                    <div class="form-group ">
                        <label for="feature2">Feature 2 <span class="required">*</span></label>
                        <textarea id="feature2" name="feature2" rows="1" class="form-control" required><?php echo $data['feature2']; ?></textarea>
                    </div>

                    <!-- Feature 3 (Optional) -->
                    <div class="form-group ">
                        <label for="feature3">Feature 3 (Optional)</label>
                        <textarea id="feature3" name="feature3" rows="1" class="form-control"><?php echo $data['feature3']; ?></textarea>
                    </div>

                    <!-- Feature 4 (Optional) -->
                    <div class="form-group ">
                        <label for="feature4">Feature 4 (Optional)</label>
                        <textarea id="feature4" name="feature4" rows="1" class="form-control"><?php echo $data['feature4']; ?></textarea>
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

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/add_products.js"></script>
    <script src="<?php echo URLROOT; ?>/js/inventory.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>