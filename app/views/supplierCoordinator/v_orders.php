<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/orders.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">storefront</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/orders" class="active">
                <span class="material-icons-sharp">shopping_cart</span>
                <h3>Orders</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
                <span class="material-icons-sharp">assignment</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">business</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
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
                <h1>Order Management</h1>
                <div class="header-actions">
                    <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/generateOrderReport'" class="add-button">
                        <span class="material-icons-sharp">assessment</span>
                        Generate Report
                    </button>
                    <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/exportOrders'" class="add-button">
                        <span class="material-icons-sharp">file_download</span>
                        Export CSV
                    </button>
                </div>
            </div>

            <div class="message"><?php flash('order_message'); ?></div>
            <div class="message"><?php flash('payment_message'); ?></div>

            <!-- Search bar -->
            <div class="search-container">
                <div class="search-wrapper">
                    <span class="material-icons-sharp">search</span>
                    <input type="text" id="orderSearch" placeholder="Search by order number or customer...">
                    <span class="material-icons-sharp clear-search" id="clearSearchIcon">close</span>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="status-tabs">
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/orders" class="tab <?php echo !isset($data['activeStatus']) ? 'active' : ''; ?>">
                    All Orders
                </a>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/filterOrders?status=pending" class="tab <?php echo (isset($data['activeStatus']) && $data['activeStatus'] == 'pending') ? 'active' : ''; ?>">
                    Pending (<?php echo $data['pendingCount'] ?? 0; ?>)
                </a>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/filterOrders?status=processing" class="tab <?php echo (isset($data['activeStatus']) && $data['activeStatus'] == 'processing') ? 'active' : ''; ?>">
                    Processing (<?php echo $data['processingCount'] ?? 0; ?>)
                </a>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/filterOrders?status=shipped" class="tab <?php echo (isset($data['activeStatus']) && $data['activeStatus'] == 'shipped') ? 'active' : ''; ?>">
                    Shipped (<?php echo $data['shippedCount'] ?? 0; ?>)
                </a>
            </div>

            <div class="orders-container">
                <div class="table-responsive">
                    <table class="orders-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['orders'] as $order): ?>
                                <tr>
                                    <td><?php echo $order->order_number; ?></td>
                                    <td><?php echo $order->customer_name; ?></td>
                                    <td>Rs. <?php echo number_format($order->total_amount, 2); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $order->payment_method)); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $order->status; ?>">
                                            <?php echo ucfirst($order->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                    <td class="actions">
                                        <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/viewOrder/<?php echo $order->id; ?>'" class="btn-icon view view-btn">
                                            <span class="material-icons-sharp">visibility</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('orderSearch');
            const clearIcon = document.getElementById('clearSearchIcon');
            const table = document.getElementById('ordersTable');

            function filterOrders() {
                const filter = searchInput.value.toLowerCase();
                const rows = table.getElementsByTagName('tr');
                let visibleCount = 0;

                // Show/hide clear button
                clearIcon.style.display = filter ? 'block' : 'none';

                // Skip header row (i=1)
                for (let i = 1; i < rows.length; i++) {
                    const orderNumberCell = rows[i].getElementsByTagName('td')[0]; // Order number column
                    const customerCell = rows[i].getElementsByTagName('td')[1]; // Customer name column

                    if (orderNumberCell && customerCell) {
                        const orderNumber = orderNumberCell.textContent || orderNumberCell.innerText;
                        const customerName = customerCell.textContent || customerCell.innerText;
                        const shouldShow = orderNumber.toLowerCase().includes(filter) ||
                            customerName.toLowerCase().includes(filter);

                        rows[i].style.display = shouldShow ? '' : 'none';
                        if (shouldShow) visibleCount++;
                    }
                }

                // Show/hide no results message
                updateNoResults(visibleCount === 0 && filter !== '');
            }

            function updateNoResults(show) {
                let message = document.getElementById('noResultsMessage');
                if (show) {
                    if (!message) {
                        message = document.createElement('div');
                        message.id = 'noResultsMessage';
                        message.className = 'no-results';
                        message.textContent = 'No orders found matching your search';
                        table.parentNode.insertBefore(message, table.nextSibling);
                    }
                    message.style.display = 'block';
                } else if (message) {
                    message.style.display = 'none';
                }
            }

            function clearSearch() {
                searchInput.value = '';
                filterOrders();
                searchInput.focus();
            }

            // Event listeners
            searchInput.addEventListener('input', filterOrders);
            clearIcon.addEventListener('click', clearSearch);
            clearIcon.style.display = 'none';
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>