<?php require APPROOT . '/views/store/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/confirmation.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="confirmation-container">
        <div class="confirmation-icon">✓</div>
        <h1>Order Confirmed!</h1>

        <?php flash('order_message'); ?>

        <div class="order-details">
            <h2>Order #<?php echo $data['order']->order_number; ?></h2>
            <p>Date: <?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></p>
            <p>Total Amount: Rs. <?php echo number_format($data['order']->total_amount, 2); ?></p>
            <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $data['order']->payment_method)); ?></p>
            <p>Status: <?php echo ucfirst($data['order']->status); ?></p>

            <div class="shipping-info">
                <h3>Shipping Address</h3>
                <p><?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?></p>
                <p>Phone: <?php echo htmlspecialchars($data['order']->contact_phone); ?></p>
            </div>
        </div>

        <div class="next-steps">
            <h3>What's Next?</h3>
            <?php if ($data['order']->payment_method == 'bank_deposit'): ?>
                <p>We're awaiting verification of your bank deposit. Once confirmed, we'll process your order.</p>
            <?php elseif ($data['order']->payment_method == 'cash'): ?>
                <p>Your order will be processed and delivered soon. Please have the cash ready upon delivery.</p>
            <?php endif; ?>
        </div>

        <div class="confirmation-actions">
            <a href="<?php echo URLROOT; ?>/store/orders" class="track-order-btn">Track Order</a>
            <a href="<?php echo URLROOT; ?>/store" class="continue-shopping-btn">Continue Shopping</a>
        </div>
    </div>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>