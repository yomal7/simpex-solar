<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/shop.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop" class="active">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
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
            <div class="page-header">
                <h1>Product Management</h1>
                <div class="header-actions">
                    <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addShopProduct'" class="add-button">
                        <span class="material-icons-sharp">add</span>
                        Add Product
                    </button>
                    <div class="search-container">
                        <div class="search-wrapper">
                            <span class="material-icons-sharp">search</span>
                            <input type="text" id="productSearch" placeholder="Search products...">
                            <span class="material-icons-sharp clear-search" id="clearSearchIcon">close</span>
                        </div>
                    </div>

                    <select id="categoryFilter" class="category-filter">
                        <option value="">All Categories</option>
                        <option value="Solar Panel">Solar Panel</option>
                        <option value="Inverters">Inverters</option>
                        <option value="Components">Components</option>
                    </select>
                </div>
            </div>

            <?php flash('product_message'); ?>

            <div class="table-responsive">
                <table id="productsTable" class="products-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['products'] as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product->name); ?></td>
                                <td><?php echo htmlspecialchars($product->supplier_name); ?></td>
                                <td>Rs.<?php echo number_format($product->price, 2); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($product->category); ?>
                                </td>
                                <td class="actions">
                                    <button onclick="viewProduct(<?php echo $product->id; ?>)" class="btn-icon view-btn">
                                        <span class="material-icons-sharp">visibility</span>
                                    </button>
                                    <button onclick="editProduct(<?php echo $product->id; ?>)" class="btn-icon edit-btn">
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



    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Delete Product</h2>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/shop.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>