<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/orders.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li><a href="<?php echo URLROOT; ?>/store"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/store/orders"><i class='bx bx-shopping-bag'></i>Orders</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
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
            <div class="header">
                <div class="left">
                    <h1>My Orders</h1>
                </div>
                <div class="right">
                    <a href="<?php echo URLROOT; ?>/store/cart" class="cart-button">
                        <i class='bx bx-cart'></i> Go to Cart
                    </a>
                </div>
            </div>

            <div class="orders-container">
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