<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/inventory.css">
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
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h1><?php echo $data['title']; ?></h1>
                <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addProduct'" class="add-button">
                    <span class="material-icons-sharp">add</span>
                    Add New Product
                </button>
            </div>

            <?php flash('supplier_message'); ?>

            <div class="inventory-container">
                <div class="table-responsive">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Supplier</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['products'] as $product) : ?>
                                <tr data-item-id="<?php echo $product->item_id; ?>">
                                    <td><?php echo $product->product_name; ?></td>
                                    <td><?php echo $product->supplier_name; ?></td>
                                    <td><?php echo $product->price; ?></td>
                                    <td class="quantity" data-quantity="<?php echo $product->quantity; ?>">
                                        <?php echo $product->quantity; ?>
                                    </td>
                                    <td class="status" id="status-<?php echo $product->id; ?>"></td>
                                    <td class="actions">
                                    <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/viewProductDetails/<?php echo $product->item_id; ?>'" class="btn-icon view view-btn">
                                            <span class="material-icons-sharp">visibility</span>
                                        </button>
                                        <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/editInventory/<?php echo $product->item_id ?>'" class="btn-icon edit edit-btn">
                                            <span class="material-icons-sharp">edit</span>
                                        </button>
                                        <button onclick="openDeleteModal(<?php echo $product->item_id; ?>)" class="btn-icon delete delete-btn">
                                            <span class="material-icons-sharp">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete Product</h2>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="form-actions">
                <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/inventory.js"></script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/status.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>