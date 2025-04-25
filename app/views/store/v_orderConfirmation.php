<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/confirmation.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/store/orders" style="background-color: rgb(192, 236, 192);" class="back-buttons">
                    <i class='bx bx-arrow-back'></i>Back to Orders
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-log-out-circle'></i>Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>
        <!-- End of Navbar -->

        <main>
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
        </main>
    </div>

    <script>
        // Toggle sidebar function
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.bx-menu');
            const sidebar = document.querySelector('.sidebar');

            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('close');
            });
        });
    </script>

    <?php require APPROOT . '/views/store/footer.php'; ?>
</body>

</html>