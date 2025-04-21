<?php require APPROOT . '/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/orders.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="orders-container">
        <h1>My Orders</h1>

        <?php if (!empty($data['orders'])): ?>
            <div class="orders-list">
                <?php foreach ($data['orders'] as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?php echo $order->order_number; ?></h3>
                                <p>Date: <?php echo date('F j, Y', strtotime($order->created_at)); ?></p>
                            </div>
                            <div class="order-status <?php echo $order->status; ?>">
                                <?php echo ucfirst($order->status); ?>
                            </div>
                        </div>

                        <div class="order-details">
                            <p>Total Items: <?php echo $order->total_items; ?></p>
                            <p>Total Amount: Rs. <?php echo number_format($order->total_amount, 2); ?></p>
                            <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $order->payment_method)); ?></p>
                        </div>

                        <div class="order-actions">
                            <a href="<?php echo URLROOT; ?>/store/orderDetails/<?php echo $order->id; ?>" class="view-details-btn">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="<?php echo URLROOT; ?>/store" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>