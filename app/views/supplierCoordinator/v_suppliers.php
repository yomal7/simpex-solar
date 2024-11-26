<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/suppliers.css">
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
            <a href="#" class="active">
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
                <h1><?php echo $data['title']; ?></h1>
                <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addSupplier'" class="add-button">
                    <span class="material-icons-sharp">add</span>
                    Add New Supplier
                </button>
            </div>

            <?php flash('supplier_message'); ?>

            <div class="suppliers-container">
                <div class="table-responsive">
                    <table class="suppliers-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['suppliers'] as $supplier) : ?>
                                <tr>
                                    <td><?php echo $supplier->name; ?></td>
                                    <td><?php echo $supplier->email; ?></td>
                                    <td><?php echo $supplier->contact_number; ?></td>
                                    <td><?php echo $supplier->address; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($supplier->created_at)); ?></td>
                                    <td class="actions">
                                        <button onclick="viewSupplier(<?php echo $supplier->id; ?>)" class="btn-icon view view-btn">
                                            <span class="material-icons-sharp">visibility</span>
                                        </button>
                                        <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/edit/<?php echo $supplier->id ?>'" class="btn-icon edit edit-btn">
                                            <span class="material-icons-sharp">edit</span>
                                        </button>
                                        <button onclick="deleteSupplier(<?php echo $supplier->id; ?>)" class="btn-icon delete delete-btn">
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
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Supplier Details</h2>
            <div class="supplier-info">
                <div class="info-row">
                    <label>Name:</label>
                    <span id="view-name"></span>
                </div>
                <div class="info-row">
                    <label>Email:</label>
                    <span id="view-email"></span>
                </div>
                <div class="info-row">
                    <label>Contact:</label>
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
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
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
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete Supplier</h2>
            <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
            <div class="form-actions">
                <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/suppliers.js"></script>
    <script src="<?php echo URLROOT; ?>/js/supplier.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>