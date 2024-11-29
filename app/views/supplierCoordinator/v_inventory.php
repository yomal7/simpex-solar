<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/inventory.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar remains the same -->
        <div class="sidebar" id="sidebar">
            <img

                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
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
                <h1><?php echo $data['title']; ?></h1>
                <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addProduct'" class="add-button">
                    <span class="material-icons-sharp">add</span>
                    Product
                </button>
            </div>

            <?php flash('supplier_message'); ?>

            <div class="inventory-container">
                <!-- New Search and Filter Section -->
                <div class="table-controls">
                    <div class="search-filter">
                        <input type="text" id="searchInput" placeholder="Search products...">

                        <select id="filterDropdown">
                            <option value="">All Status</option>
                            <option value="in-stock">In Stock</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="inventory-table" id="inventoryTable">
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
                                    <td class="status" id="status-<?php echo $product->item_id; ?>">
                                        <?php
                                        // Determine status based on quantity
                                        if ($product->quantity == 0) {
                                            echo '<span class="status out-of-stock">Out of Stock</span>';
                                        } elseif ($product->quantity < 10) {
                                            echo '<span class="status low-stock">Low Stock</span>';
                                        } else {
                                            echo '<span class="status in-stock">In Stock</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="actions">

                                        <button onclick="viewInventory(<?php echo $product->item_id; ?>)" class="btn-icon view view-btn">

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

    <!-- New JavaScript for Search and Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterDropdown = document.getElementById('filterDropdown');
            const table = document.getElementById('inventoryTable');
            const rows = table.getElementsByTagName('tr');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusFilter = filterDropdown.value;

                for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header row
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    const nameCell = cells[0];
                    const supplierCell = cells[1];
                    const statusCell = cells[4];

                    // Check search term
                    const nameMatch = nameCell.textContent.toLowerCase().includes(searchTerm);
                    const supplierMatch = supplierCell.textContent.toLowerCase().includes(searchTerm);

                    // Check status filter
                    const statusText = statusCell.textContent.toLowerCase().replace(' ', '-');
                    const statusMatch = statusFilter === '' || statusText === statusFilter;

                    // Show/hide row based on search and filter
                    row.style.display = (nameMatch || supplierMatch) && statusMatch ? '' : 'none';
                }
            }

            // Add event listeners for real-time filtering
            searchInput.addEventListener('keyup', filterTable);
            filterDropdown.addEventListener('change', filterTable);
        });
    </script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>
</body>

</html>