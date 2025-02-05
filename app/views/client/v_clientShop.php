<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/clientShop.css">
</head>

<body>

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
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
                    <h1>Orders</h1>
                    <!-- <ul class="breadcrumb">
                        <li><a href="#">
                                Analytics
                            </a></li>
                        /
                        <li><a href="#" class="active">Shop</a></li>
                    </ul> -->
                </div>
            </div>


            <div class="container">
                <div class="dashboard-container">

                    <!-- Filters -->
                    <div class="order-filters">
                        <input type="text" class="filter-input" placeholder="Search orders..." id="orderSearch">
                        <select class="filter-input" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="pending_approval">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Ready for Pickup</option>
                            <option value="cancelled">Out for Delivery</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <select class="filter-input" id="dateFilter">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>

                    <!-- Orders Table -->
                    <div class="orders-table-container">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Products</th>
                                    <th>Preferred Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Display pre-orders
                                foreach ($data['orders']['pre_orders'] as $order):
                                    $total = $order->product_price * $order->quantity;
                                    if ($order->delivery_option === 'deliver') {
                                        $total += 450.00;
                                    }

                                    // Calculate preferred date based on status
                                    $date = new DateTime($order->updated_at);
                                    switch ($order->status) {
                                        case 'pending':
                                            $date->modify('+10 days');
                                            break;
                                        case 'rejected':
                                        case 'cancelled':
                                            // Keep updated_at date
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <div class="order-products">
                                                <?php echo $order->product_name . " (x" . $order->quantity . ")"; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $date->format('M d, Y'); ?></td>
                                        <td class="total-price">Rs. <?php echo number_format($total, 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($order->status); ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="order-actions">
                                                <button class="action-button action-view" onclick="viewOrder(<?php echo $order->id; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <?php if ($order->status === 'pending'): ?>
                                                    <button class="action-button action-edit" onclick="editOrder(<?php echo $order->id; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="action-button action-cancel" onclick="cancelOrder(<?php echo $order->id; ?>)">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                <?php elseif (in_array($order->status, ['rejected', 'cancelled'])): ?>
                                                    <button class="action-button action-delete" onclick="deleteOrder(<?php echo $order->id; ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php
                                // Display orders
                                foreach ($data['orders']['orders'] as $order):
                                    $total = $order->price * $order->quantity;
                                    if ($order->delivery_fee > 0) {
                                        $total += $order->delivery_fee;
                                    }
                                    if ($order->discount > 0) {
                                        $total -= $order->discount;
                                    }

                                    // Calculate preferred date based on status
                                    $date = new DateTime($order->updated_at);
                                    switch ($order->status) {
                                        case 'approved':
                                            $date->modify('+7 days');
                                            break;
                                        case 'processing':
                                            $date->modify('+5 days');
                                            break;
                                        case 'ready for pickup':
                                        case 'out for delivery':
                                            $date = new DateTime(); // Today
                                            break;
                                        case 'delivered':
                                            // Keep updated_at date
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <div class="order-products">
                                                <?php echo $order->product_name . " (x" . $order->quantity . ")"; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $date->format('M d, Y'); ?></td>
                                        <td>Rs. <?php echo number_format($total, 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($order->status)); ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="order-actions">
                                                <button class="action-button action-view" onclick="viewOrder(<?php echo $order->id; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <?php if ($order->status === 'approved'): ?>
                                                    <button class="btn-confirm" onclick="confirmOrder(<?php echo $order->id; ?>)">
                                                        <i class="fas fa-check"></i> Confirm
                                                    </button>
                                                <?php elseif ($order->status === 'cancelled'): ?>
                                                    <button class="action-button action-delete" onclick="deleteOrder(<?php echo $order->id; ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination">
                            <button class="page-button">Previous</button>
                            <button class="page-button active">1</button>
                            <button class="page-button">2</button>
                            <button class="page-button">3</button>
                            <button class="page-button">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Cancel Order Modal -->
                <div class="modal" id="cancelModal">
                    <div class="modal-content">
                        <button class="modal-close" onclick="closeModal('cancelModal')">&times;</button>
                        <h2 class="modal-title">Cancel Order</h2>
                        <div class="modal-body">
                            <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                        </div>
                        <div class="modal-actions">
                            <button class="action-button" onclick="closeModal('cancelModal')">No, Keep Order</button>
                            <button class="action-button action-cancel" onclick="confirmCancel()">Yes, Cancel Order</button>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/client/clientShop.js"></script>
    <?php require APPROOT . '/views/client/footer.php'; ?>