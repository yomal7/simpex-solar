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

        <div class="main-content">
            <div class="page-header">
                <h1>Product Management</h1>
                <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addProduct'" class="add-button">
                    <span class="material-icons-sharp">add</span>
                    Add New Product
                </button>
            </div>

            <?php flash('product_message'); ?>

            <div class="table-responsive">
                <table class="suppliers-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['products'] as $product) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product->product_name); ?></td>
                                <td><?php echo htmlspecialchars($product->supplier_name); ?></td>
                                <td>$<?php echo number_format($product->price, 2); ?></td>
                                <td><?php echo htmlspecialchars($product->category); ?></td>
                                <td class="actions">
                                    <button onclick="viewProduct(<?php echo $product->id; ?>)" class="btn-icon view-btn">
                                        <span class="material-icons-sharp">visibility</span>
                                    </button>
                                    <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/editProduct/<?php echo $product->id; ?>'" class="btn-icon edit-btn">
                                        <span class="material-icons-sharp">edit</span>
                                    </button>
                                    <button onclick="deleteProduct(<?php echo $product->id; ?>)" class="btn-icon delete-btn">
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

    <!-- View Modal -->
    <!-- <div id="viewModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Product Details</h2>
                <div class="product-info">
                    <div class="info-row">
                        <label>Name:</label>
                        <span id="view-name"></span>
                    </div>
                    <div class="info-row">
                        <label>Supplier:</label>
                        <span id="view-email"></span>
                    </div>
                    <div class="info-row">
                        <label>Quantity:</label>
                        <span id="view-contact"></span>
                    </div>
                    <div class="info-row">
                        <label>Address:</label>
                        <span id="view-address"></span>
                    </div>
                    <div class="info-row">
                        <label>Other Details:</label>
                        <span id="view-other-details"></span>
                    </div>
                </div>
            </div>
        </div> -->

    <!-- Edit Modal -->
    <!-- <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Supplier</h2>
                <form id="editSupplierForm">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Contact:</label>
                        <input type="text" id="edit-contact" name="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label>Address:</label>
                        <textarea id="edit-address" name="address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Other Details:</label>
                        <textarea id="edit-other-details" name="other_details"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div> -->

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete Prodcut</h2>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="form-actions">
                <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/suppliers.js"></script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/status.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>