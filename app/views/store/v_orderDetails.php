<?php require APPROOT . '/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/orderDetails.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="order-details-container">
        <div class="order-header">
            <h1>Order Details</h1>
            <a href="<?php echo URLROOT; ?>/store/orders" class="back-link">← Back to Orders</a>
        </div>

        <div class="order-info">
            <div class="order-summary">
                <h2>Order #<?php echo $data['order']->order_number; ?></h2>
                <p>Date: <?php echo date('F j, Y H:i', strtotime($data['order']->created_at)); ?></p>
                <p>Status: <span class="status <?php echo $data['order']->status; ?>"><?php echo ucfirst($data['order']->status); ?></span></p>
                <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $data['order']->payment_method)); ?></p>
            </div>

            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <p><?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?></p>
                <p>Phone: <?php echo htmlspecialchars($data['order']->contact_phone); ?></p>
            </div>
        </div>

        <div class="order-items">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['orderItems'] as $item): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="<?php echo !empty($item->image1) ? URLROOT . '/public/uploads/store/' . $item->image1 : URLROOT . '/public/assets/default-product.png'; ?>"
                                        alt="<?php echo htmlspecialchars($item->name); ?>">
                                    <span><?php echo htmlspecialchars($item->name); ?></span>
                                </div>
                            </td>
                            <td><?php echo $item->quantity; ?></td>
                            <td>Rs. <?php echo number_format($item->price_at_time, 2); ?></td>
                            <td>Rs. <?php echo number_format($item->price_at_time * $item->quantity, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Total:</td>
                        <td><strong>Rs. <?php echo number_format($data['order']->total_amount, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if ($data['order']->status == 'pending' && $data['order']->payment_method == 'bank_deposit'): ?>
            <div class="payment-pending">
                <h3>Payment Pending</h3>
                <p>Please upload your bank deposit slip to proceed with your order.</p>
                <a href="<?php echo URLROOT; ?>/store/payment/<?php echo $data['order']->id; ?>" class="upload-slip-btn">Upload Payment Slip</a>
            </div>
        <?php endif; ?>
    </div>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>