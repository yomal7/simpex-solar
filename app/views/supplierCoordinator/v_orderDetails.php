<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/orderDetails.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">storefront</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/orders" class="active">
                <span class="material-icons-sharp">shopping_cart</span>
                <h3>Orders</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
                <span class="material-icons-sharp">assignment</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">business</span>
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
            <div class="page-header">
                <h1>Order #<?php echo $data['order']->order_number; ?></h1>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/orders" class="back-button">
                    <span class="material-icons-sharp">arrow_back</span>
                    Back to Orders
                </a>
            </div>

            <div class="message"><?php flash('order_message'); ?></div>
            <div class="message"><?php flash('payment_message'); ?></div>

            <div class="order-details-container">
                <!-- Order Information -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Order Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Customer:</label>
                                <span><?php echo $data['order']->customer_name; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Email:</label>
                                <span><?php echo $data['order']->email; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Phone:</label>
                                <span><?php echo $data['order']->contact_phone; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Order Date:</label>
                                <span><?php echo date('M d, Y h:i A', strtotime($data['order']->created_at)); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Payment Method:</label>
                                <span><?php echo ucfirst(str_replace('_', ' ', $data['order']->payment_method)); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Current Status:</label>
                                <span class="status-badge <?php echo $data['order']->status; ?>">
                                    <?php echo ucfirst($data['order']->status); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Update -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Update Status</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/supplierCoordinator/updateOrderStatus" method="POST" class="status-update-form">
                            <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                            <div class="form-group">
                                <label for="status">Select New Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" <?php echo $data['order']->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $data['order']->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $data['order']->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $data['order']->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $data['order']->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn update-btn">Update Status</button>
                        </form>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Shipping Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="address-block">
                            <?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="detail-card order-items-card">
                    <div class="card-header">
                        <h2>Order Items</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
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
                    </div>
                </div>

                <!-- Payment Information -->
                <?php if (isset($data['payment']) && $data['payment']): ?>
                    <div class="detail-card payment-card">
                        <div class="card-header">
                            <h2>Payment Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="payment-status">
                                <label>Payment Status:</label>
                                <span class="status-badge payment-status-<?php echo $data['payment']->status; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $data['payment']->status)); ?>
                                </span>
                            </div>

                            <?php if ($data['payment']->payment_method == 'bank_deposit' && $data['payment']->bank_slip): ?>
                                <div class="bank-slip-section">
                                    <h3>Bank Deposit Slip</h3>
                                    <div class="slip-image">
                                        <img src="<?php echo URLROOT; ?>/uploads/bank_slips/<?php echo $data['payment']->bank_slip; ?>"
                                            alt="Bank Deposit Slip">
                                    </div>

                                    <?php if ($data['payment']->status == 'pending_verification'): ?>
                                        <form action="<?php echo URLROOT; ?>/supplierCoordinator/verifyPayment" method="POST" class="payment-verification-form">
                                            <input type="hidden" name="payment_id" value="<?php echo $data['payment']->id; ?>">
                                            <div class="verification-actions">
                                                <button type="submit" name="action" value="approve" class="btn approve-btn">
                                                    <span class="material-icons-sharp">check_circle</span> Approve Payment
                                                </button>
                                                <button type="button" onclick="showRejectForm()" class="btn reject-btn">
                                                    <span class="material-icons-sharp">cancel</span> Reject Payment
                                                </button>
                                            </div>

                                            <div id="rejectForm" style="display: none;" class="reject-form">
                                                <div class="form-group">
                                                    <label for="rejection_reason">Rejection Reason:</label>
                                                    <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control" required></textarea>
                                                </div>
                                                <button type="submit" name="action" value="reject" class="btn reject-confirm-btn">Confirm Rejection</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($data['payment']->status == 'rejected' && $data['payment']->rejection_reason): ?>
                                <div class="rejection-details">
                                    <h3>Rejection Reason</h3>
                                    <div class="rejection-reason">
                                        <?php echo htmlspecialchars($data['payment']->rejection_reason); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showRejectForm() {
            document.getElementById('rejectForm').style.display = 'block';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>