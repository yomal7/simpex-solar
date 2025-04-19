<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/dashboard.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
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
            <div class="container">
                <div class="cards">
                    <div class="card" id="request-orders" onclick="showRequestOrders()">
                        <div class="card-content">
                            <div class="card-info">
                                <h3>Request Orders</h3>
                                <p class="count"><?php echo count($data['pending_orders']); ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="material-icons-sharp">pending_actions</i>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="processing-orders" onclick="showProcessingOrders()">
                        <div class="card-content">
                            <div class="card-info">
                                <h3>Processing Orders</h3>
                                <p class="count"><?php echo count($data['processing_orders']); ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="material-icons-sharp">sync</i>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="active-orders" onclick="showActiveOrders()">
                        <div class="card-content">
                            <div class="card-info">
                                <h3>Active Orders</h3>
                                <p class="count"><?php echo count($data['active_orders']); ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="material-icons-sharp">assignment</i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Total Price</th>
                                <th>Date</th>
                                <?php if (isset($data['show_status'])): ?>
                                    <th>Status</th>
                                <?php endif; ?>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <?php foreach ($data['orders'] as $order): ?>
                                <tr>
                                    <td><?php echo 'ORD-' . date('Y', strtotime($order->created_at)) . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo $order->product_name; ?></td>
                                    <td class="price">Rs. <?php
                                                            $total = $order->price * $order->quantity;
                                                            if ($order->delivery_fee > 0) $total += $order->delivery_fee;
                                                            if ($order->discount > 0) $total -= $order->discount;
                                                            echo number_format($total, 2);
                                                            ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($order->created_at)); ?></td>
                                    <?php if (isset($data['show_status'])): ?>
                                        <td>
                                            <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($order->status)); ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <button class="btn-view" onclick="window.location.href='<?php echo URLROOT; ?>/supplierCoordinator/viewOrder/<?php echo $order->id; ?>'">
                                            <i class="material-icons-sharp">visibility</i>
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

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/dashboard.js"></script>
</body>

</html>