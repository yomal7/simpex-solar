<?php require APPROOT . '/views/deliveryPerson/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/deliveryPerson/orders.css">

</head>

<body>
    <div class="orders-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Delivery Person</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/deliveryPerson/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/orders" class="active">
                <span class="material-icons-sharp">local_shipping</span>
                <h3>Orders</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <h1>My Delivery Orders</h1>
                </div>

                <?php flash('order_message'); ?>

                <!-- Pending Deliveries Section -->
                <div class="orders-section">
                    <h2>Pending Deliveries</h2>

                    <?php if (!empty($data['pendingOrders'])): ?>
                        <div class="orders-grid">
                            <?php foreach ($data['pendingOrders'] as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <h3>Order #<?php echo $order->order_number; ?></h3>
                                        <span class="status-badge shipped">Shipped</span>
                                    </div>
                                    <div class="order-details">
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">event</span>
                                            <p>Date: <?php echo date('F j, Y', strtotime($order->created_at)); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">person</span>
                                            <p>Customer: <?php echo htmlspecialchars($order->customer_name); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">location_on</span>
                                            <p>Address: <?php echo htmlspecialchars($order->shipping_address); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">phone</span>
                                            <p>Contact: <?php echo htmlspecialchars($order->contact_phone); ?></p>
                                        </div>
                                    </div>
                                    <div class="order-footer">
                                        <!-- <button class="view-btn" onclick="viewOrderDetails(<?php echo $order->id; ?>)">
                                            <span class="material-icons-sharp">visibility</span>
                                            View Details
                                        </button> -->
                                        <button class="deliver-btn" onclick="confirmDelivery(<?php echo $order->id; ?>)">
                                            <span class="material-icons-sharp">check_circle</span>
                                            Mark as Delivered
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-orders">
                            <div class="empty-state">
                                <span class="material-icons-sharp">local_shipping</span>
                                <p>No pending deliveries at the moment</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Completed Deliveries Section -->
                <div class="orders-section">
                    <h2>Completed Deliveries</h2>

                    <?php if (!empty($data['completedOrders'])): ?>
                        <div class="orders-grid">
                            <?php foreach ($data['completedOrders'] as $order): ?>
                                <div class="order-card completed">
                                    <div class="order-header">
                                        <h3>Order #<?php echo $order->order_number; ?></h3>
                                        <span class="status-badge delivered">Delivered</span>
                                    </div>
                                    <div class="order-details">
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">event</span>
                                            <p>Date: <?php echo date('F j, Y', strtotime($order->created_at)); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">person</span>
                                            <p>Customer: <?php echo htmlspecialchars($order->customer_name); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="material-icons-sharp">check_circle</span>
                                            <p>Delivered on: <?php echo date('F j, Y g:i A', strtotime($order->delivered_at)); ?></p>
                                        </div>
                                    </div>
                                    <div class="order-footer">
                                        <button class="view-btn" onclick="viewOrderDetails(<?php echo $order->id; ?>)">
                                            <span class="material-icons-sharp">visibility</span>
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-orders">
                            <div class="empty-state">
                                <span class="material-icons-sharp">inventory</span>
                                <p>No deliveries completed yet</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Define URLROOT for JavaScript
        const URLROOT = '<?php echo URLROOT; ?>';

        function viewOrderDetails(orderId) {
            window.location.href = `${URLROOT}/deliveryPerson/viewOrder/${orderId}`;
        }

        function confirmDelivery(orderId) {
            window.location.href = `${URLROOT}/deliveryPerson/viewOrder/${orderId}`;
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('show');
        }
    </script>

    <?php require APPROOT . '/views/deliveryPerson/footer.php'; ?>