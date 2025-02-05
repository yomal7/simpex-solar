<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/requestOrders.css">
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
                <!-- Back button -->
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/dashboard" class="back-btn">
                    <i class="material-icons-sharp">arrow_back</i>
                    Back to Dashboard
                </a>

                <div class="order-details-container">
                    <h1>Order Details</h1>

                    <!-- Order Summary -->
                    <div class="summary-section">
                        <h2>Order Summary</h2>
                        <div class="product-info">
                            <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['order']->image1; ?>"
                                alt="<?php echo $data['order']->product_name; ?>">
                            <div class="details">
                                <h3><?php echo $data['order']->product_name; ?></h3>
                                <p>Quantity: <?php echo $data['order']->quantity; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Method -->
                    <div class="collection-section">
                        <h2>Collection Method</h2>
                        <?php if ($data['order']->delivery_option === 'deliver'): ?>
                            <div class="delivery-info">
                                <i class="material-icons-sharp">local_shipping</i>
                                <div>
                                    <h3>Delivery Address</h3>
                                    <p><?php echo $data['order']->street_address; ?></p>
                                    <p><?php echo $data['order']->city . ', ' . $data['order']->province; ?></p>
                                    <p><?php echo $data['order']->postal_code; ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="pickup-info">
                                <i class="material-icons-sharp">store</i>
                                <div>
                                    <h3>Store Pickup</h3>
                                    <p>Customer will pickup from store</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Personal Information -->
                    <div class="personal-section">
                        <h2>Personal Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Full Name</label>
                                <p><?php echo $data['order']->full_name; ?></p>
                            </div>
                            <div class="info-item">
                                <label>Email</label>
                                <p><?php echo $data['order']->email; ?></p>
                            </div>
                            <div class="info-item">
                                <label>Phone</label>
                                <p><?php echo $data['order']->phone_number; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Finalization Form -->
                    <form id="finalizeOrderForm" class="finalize-section">
                        <h2>Finalize Order</h2>
                        <input type="hidden" id="orderId" value="<?php echo $data['order']->id; ?>" data-quantity="<?php echo $data['order']->quantity; ?>">
                        <input type="hidden" id="delivery_option" value="<?php echo $data['order']->delivery_option; ?>">
                        <input type="hidden" id="quantity" value="<?php echo $data['order']->quantity; ?>">

                        <div class="form-group">
                            <label for="price">Price (Rs.)</label>
                            <input type="number" step="0.01" id="price" value="<?php echo $data['order']->product_price; ?>" class="form-control">
                        </div>

                        <?php if ($data['order']->delivery_option === 'deliver'): ?>
                            <div class="form-group">
                                <label for="deliveryFee">Delivery Fee (Rs.)</label>
                                <input type="number" id="deliveryFee" value="450.00" class="form-control">
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="discount">Discount (Rs.)</label>
                            <input type="number" step="0.01" id="discount" value="0.00" class="form-control">
                        </div>

                        <div class="total-section">
                            <h3>Total Amount</h3>
                            <p id="totalAmount">Rs. <?php
                                                    $total = $data['order']->product_price * $data['order']->quantity;
                                                    if ($data['order']->delivery_option === 'deliver') $total += 450;
                                                    echo number_format($total, 2);
                                                    ?></p>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-approve" onclick="confirmApprove()">
                                <i class="material-icons-sharp">check</i>
                                Approve Order
                            </button>
                            <button type="button" class="btn-reject" onclick="confirmReject()">
                                <i class="material-icons-sharp">close</i>
                                Reject Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Confirmation Modals -->
            <div id="approveModal" class="modal">
                <div class="modal-content">
                    <h3>Confirm Approval</h3>
                    <p>Are you sure you want to approve this order?</p>
                    <div class="modal-actions">
                        <button class="btn-cancel" onclick="closeModal('approveModal')">Cancel</button>
                        <button class="btn-confirm" onclick="approveOrder()">Confirm</button>
                    </div>
                </div>
            </div>

            <div id="rejectModal" class="modal">
                <div class="modal-content">
                    <h3>Confirm Rejection</h3>
                    <p>Are you sure you want to reject this order?</p>
                    <div class="modal-actions">
                        <button class="btn-cancel" onclick="closeModal('rejectModal')">Cancel</button>
                        <button class="btn-confirm" onclick="rejectOrder()">Confirm</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/requestOrders.js"></script>
</body>

</html>