<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/report.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/adminnavbar.php'; ?>

    <div class="report-container">
        <div class="report-header">
            <h1>Order Report</h1>
            <div class="date-range">
                <span>As of: <?php echo date('F j, Y'); ?></span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Orders</h3>
                <div class="card-value"><?php echo $data['totalOrders']; ?></div>
            </div>
            <div class="summary-card">
                <h3>Total Revenue</h3>
                <div class="card-value">Rs. <?php echo number_format($data['totalRevenue'], 2); ?></div>
            </div>
            <div class="summary-card">
                <h3>Average Order Value</h3>
                <div class="card-value">Rs. <?php echo number_format($data['totalRevenue'] / max(1, $data['totalOrders']), 2); ?></div>
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="report-section">
            <h2>Orders by Status</h2>
            <div class="status-chart">
                <?php foreach ($data['ordersByStatus'] as $status => $count): ?>
                    <div class="status-item">
                        <div class="status-label"><?php echo ucfirst($status); ?></div>
                        <div class="status-bar">
                            <div class="status-fill" style="width: <?php echo ($count / max(1, $data['totalOrders'])) * 100; ?>%">
                                <?php echo $count; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="report-section">
            <h2>Top Selling Products</h2>
            <table>
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

        <!-- Recent Orders -->
        <div class="report-section">
            <h2>Recent Orders</h2>
            <table>
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
                            <td><?php echo date('Y-m-d', strtotime($order->created_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="report-actions">
            <button onclick="window.print()" class="btn btn-primary">Print Report</button>
            <a href="<?php echo URLROOT; ?>/supplierCoordinator/exportOrders" class="btn btn-secondary">Export to CSV</a>
        </div>
    </div>

    <?php require APPROOT . '/views/inc/components/adminFooter.php'; ?>