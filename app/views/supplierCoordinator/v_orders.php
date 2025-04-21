<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/orders.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/adminnavbar.php'; ?>

    <div class="orders-container">
        <div class="orders-header">
            <h1>Order Management</h1>
            <div class="header-actions">
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/generateOrderReport" class="btn btn-secondary">Generate Report</a>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/exportOrders" class="btn btn-secondary">Export to CSV</a>
            </div>
        </div>

        <?php flash('order_message'); ?>
        <?php flash('payment_message'); ?>

        <!-- Order Status Tabs -->
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

        <!-- Orders Table -->
        <div class="orders-table">
            <table>
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
                            <td><?php echo date('Y-m-d H:i', strtotime($order->created_at)); ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/supplierCoordinator/viewOrder/<?php echo $order->id; ?>" class="btn btn-small">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require APPROOT . '/views/inc/components/adminFooter.php'; ?>