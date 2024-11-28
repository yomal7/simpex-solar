<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/edit_inventory.css">
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
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="#" class="active">
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

        <div class="page-header">
            <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="btn back-btn">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <div class="content-wrapper">
            <div class="main-content">

                <?php flash('product_message'); ?>

                <div class="product-details-section">
                    <div class="product-header">
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/uploads/images/<?php echo $data['image_path']; ?>"
                                alt="<?php echo htmlspecialchars($data['name']); ?>"
                                class="img-fluid">
                        </div>
                        <div class="product-title">
                            <h2><?php echo htmlspecialchars($data['name']); ?></h2>
                        </div>
                    </div>
                </div>

                <form action="<?php echo URLROOT; ?>/supplierCoordinator/updateInventory" method="POST" class="inventory-form">
                    <div class="form-grid">

                        <!-- Supplier Selection -->
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

                        <!-- Description -->
                        <div class="form-group full-width">
                            <label for="description">Description <span class="required">*</span></label>
                            <textarea id="description" name="description" rows="4" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" required><?php echo $data['description']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="form-actions">
                            <button type="reset" class="btn reset-btn">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/edit_inventory.js"></script>
    <script src="<?php echo URLROOT; ?>/js/inventory.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>