<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/clientConfirmOrder.css">
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

            <div class="success-header">
                <h1>Confirm Order</h1>
                <p class="order-id">Order ID: <?php
                                                echo 'ORD-' . date('Y', strtotime($data['order']->created_at)) . '-' .
                                                    str_pad($data['order']->id, 3, '0', STR_PAD_LEFT);
                                                ?></p>
            </div>

            <div class="container">
                <div class="card">
                    <h2 class="card-title">Product Details</h2>
                    <div class="product-details">
                        <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['order']->image1; ?>"
                            alt="<?php echo $data['order']->product_name; ?>"
                            class="product-image">
                        <div class="product-info">
                            <h3 class="product-name"><?php echo $data['order']->product_name; ?></h3>
                            <div class="product-meta">
                                <p>Quantity: <?php echo $data['order']->quantity; ?></p>
                                <p>Price: Rs. <?php echo number_format($data['order']->price, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="card-title">Customer Information</h2>
                    <div class="customer-grid">
                        <div class="customer-section">
                            <h3>Contact Details</h3>
                            <div class="customer-info">
                                <p><?php echo $data['order']->full_name; ?></p>
                                <p><?php echo $data['order']->email; ?></p>
                                <p><?php echo $data['order']->phone_number; ?></p>
                            </div>
                        </div>
                        <div class="customer-section">
                            <h3><?php echo $data['order']->delivery_option === 'deliver' ? 'Delivery Address' : 'Pickup Location'; ?></h3>
                            <div class="customer-info">
                                <?php if ($data['order']->delivery_option === 'deliver'): ?>
                                    <p><?php echo $data['order']->street_address . ','; ?></p>
                                    <p><?php echo $data['order']->city . ', ' . $data['order']->province; ?></p>
                                    <p><?php echo $data['order']->postal_code; ?></p>
                                <?php else: ?>
                                    <p>Company Address</p>
                                    <p>123 Solar Street, Colombo</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="card-title">Order Summary</h2>
                    <div class="order-summary">
                        <?php
                        $subtotal = $data['order']->price * $data['order']->quantity;
                        $deliveryFee = $data['order']->delivery_option === 'deliver' ? $data['order']->delivery_fee : 0;
                        $discount = $data['order']->discount ?? 0;
                        $total = $subtotal + $deliveryFee - $discount;
                        ?>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>Rs. <?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <?php if ($data['order']->delivery_option === 'deliver'): ?>
                            <div class="summary-row">
                                <span>Delivery Fee</span>
                                <span>Rs. <?php echo number_format($deliveryFee, 2); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="summary-row discount">
                            <span>Discount</span>
                            <span>-Rs. <?php echo number_format($discount, 2); ?></span>
                        </div>
                        <div class="divider"></div>
                        <div class="summary-row total-row">
                            <span>Total</span>
                            <span>Rs. <?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="card-title">Payment Method</h2>
                    <div class="payment-methods">
                        <div class="payment-option">
                            <input type="radio" id="cash" name="payment_method" value="cash" required>
                            <label for="cash">Cash on Delivery/Pickup</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="online" name="payment_method" value="online">
                            <label for="online">Online Payment</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="bank" name="payment_method" value="bank">
                            <label for="bank">Bank Deposit</label>
                        </div>
                    </div>

                    <div class="terms-section">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">I agree to the terms and conditions</label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-confirm" onclick="confirmOrder(<?php echo $data['order']->id; ?>)">
                            <i class="fas fa-check"></i> Confirm Order
                        </button>
                        <button class="btn-download" onclick="downloadQuotation(<?php echo $data['order']->id; ?>)">
                            <i class="fas fa-download"></i> Download Quotation
                        </button>
                        <button class="btn-cancel" onclick="cancelOrder(<?php echo $data['order']->id; ?>)">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Cancellation</h3>
            <p>Are you sure you want to cancel this order?</p>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="closeModal('cancelModal')">No, Keep Order</button>
                <button class="btn-modal-confirm" onclick="confirmCancel(<?php echo $data['order']->id; ?>)">Yes, Cancel Order</button>
            </div>
        </div>
    </div>

    <!-- Add to v_clientConfirmOrder.php -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Payment Method</h3>
            <div class="modal-body"></div>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="closeModal('confirmModal')">Cancel</button>
                <button class="btn-modal-confirm" id="confirmPaymentBtn">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/client/clientConfirmOrder.js"></script>
    <?php require APPROOT . '/views/client/footer.php'; ?>