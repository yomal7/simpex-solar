<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/report.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
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
                <h1>Order Report</h1>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/orders" class="back-button">
                    <span class="material-icons-sharp">arrow_back</span>
                    Back to Orders
                </a>
            </div>

            <div class="report-actions">
                <div class="date-range">
                    <span class="material-icons-sharp">calendar_today</span>
                    <span>As of: <?php echo date('F j, Y'); ?></span>
                </div>
                <div class="action-buttons">
                    <button onclick="window.print()" class="btn-primary">
                        <span class="material-icons-sharp">print</span> Print Report
                    </button>
                    <a href="<?php echo URLROOT; ?>/supplierCoordinator/exportOrders" class="btn-secondary">
                        <span class="material-icons-sharp">file_download</span> Export to CSV
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="card-icon">
                        <span class="material-icons-sharp">receipt_long</span>
                    </div>
                    <div class="card-info">
                        <h3>Total Orders</h3>
                        <div class="card-value"><?php echo $data['totalOrders']; ?></div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="card-icon">
                        <span class="material-icons-sharp">payments</span>
                    </div>
                    <div class="card-info">
                        <h3>Total Revenue</h3>
                        <div class="card-value">Rs. <?php echo number_format($data['totalRevenue'], 2); ?></div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="card-icon">
                        <span class="material-icons-sharp">trending_up</span>
                    </div>
                    <div class="card-info">
                        <h3>Average Order Value</h3>
                        <div class="card-value">Rs. <?php echo number_format($data['totalRevenue'] / max(1, $data['totalOrders']), 2); ?></div>
                    </div>
                </div>
            </div>

            <!-- Orders by Status -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Orders by Status</h2>
                </div>
                <div class="card-body">
                    <div class="status-chart">
                        <?php foreach ($data['ordersByStatus'] as $status => $count): ?>
                            <div class="status-item">
                                <div class="status-label"><?php echo ucfirst($status); ?></div>
                                <div class="status-bar">
                                    <div class="status-fill <?php echo $status; ?>" style="width: <?php echo ($count / max(1, $data['totalOrders'])) * 100; ?>%">
                                        <?php echo $count; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Top Selling Products</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Units Sold</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['topProducts'] as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->name); ?></td>
                                        <td><?php echo $product->units_sold; ?></td>
                                        <td>Rs. <?php echo number_format($product->total_revenue, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Recent Orders</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recentOrders'] as $order): ?>
                                    <tr>
                                        <td><?php echo $order->order_number; ?></td>
                                        <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                        <td>Rs. <?php echo number_format($order->total_amount, 2); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $order->status; ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>