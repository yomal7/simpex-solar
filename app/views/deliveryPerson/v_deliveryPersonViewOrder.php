<?php require APPROOT . '/views/deliveryPerson/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/deliveryPerson/viewOrder.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT; ?>/deliveryPerson/orders" class="back-button">
                <span class="material-icons-sharp">arrow_back</span>
                Back to Orders
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h1>Order #<?php echo $data['order']->order_number; ?></h1>
                <span class="status-badge <?php echo $data['order']->status; ?>">
                    <?php echo ucfirst($data['order']->status); ?>
                </span>
            </div>

            <?php flash('delivery_message'); ?>

            <div class="order-details-container">
                <!-- Customer Information -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Customer Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Name:</label>
                                <span><?php echo htmlspecialchars($data['order']->customer_name); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Contact Phone:</label>
                                <span><?php echo htmlspecialchars($data['order']->contact_phone); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Shipping Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Address:</label>
                                <div class="address-block">
                                    <?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?>
                                </div>
                            </div>
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
                <div class="detail-card payment-card">
                    <div class="card-header">
                        <h2>Payment Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Payment Method:</label>
                                <span><?php echo ucfirst(str_replace('_', ' ', $data['order']->payment_method)); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Payment Status:</label>
                                <span class="status-badge payment-status-<?php echo $data['order']->payment_status; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $data['order']->payment_status)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Confirmation -->
                <?php if ($data['order']->status == 'shipped' && is_null($data['order']->delivered_at)): ?>
                    <div class="detail-card delivery-card">
                        <div class="card-header">
                            <h2>Delivery Confirmation</h2>
                        </div>
                        <div class="card-body">
                            <div class="delivery-actions">
                                <a href="<?php echo URLROOT; ?>/deliveryPerson/generateDeliveryReport/<?php echo $data['order']->id; ?>"
                                    class="btn generate-btn" target="_blank">
                                    <span class="material-icons-sharp">description</span> Generate Delivery Report
                                </a>

                                <form action="<?php echo URLROOT; ?>/deliveryPerson/confirmDelivery" method="POST"
                                    enctype="multipart/form-data" id="deliveryForm">
                                    <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">

                                    <?php if ($data['order']->payment_method == 'cash'): ?>
                                        <div class="cash-confirmation">
                                            <label>
                                                <input type="checkbox" name="payment_received" id="paymentReceived">
                                                I confirm that I have received the cash payment of Rs. <?php echo number_format($data['order']->total_amount, 2); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>

                                    <div class="report-upload" <?php if ($data['order']->payment_method == 'cash'): ?>style="opacity: 0.5;" <?php endif; ?>>
                                        <label for="delivery_report">Upload Signed Delivery Report:</label>
                                        <input type="file" name="delivery_report" id="delivery_report" accept=".pdf,.jpg,.jpeg,.png"
                                            <?php if ($data['order']->payment_method == 'cash'): ?>disabled<?php endif; ?>>
                                        <small>Upload the delivery report signed by the customer (PDF, JPG, or PNG)</small>
                                    </div>

                                    <button type="submit" class="btn confirm-btn" id="confirmDeliveryBtn"
                                        <?php if ($data['order']->payment_method == 'cash'): ?>disabled<?php endif; ?>>
                                        <span class="material-icons-sharp">check_circle</span> Confirm Delivery
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Delivery Completed -->
                <?php if ($data['order']->status == 'delivered' && !is_null($data['order']->delivered_at)): ?>
                    <div class="detail-card delivery-card completed">
                        <div class="card-header">
                            <h2>Delivery Completed</h2>
                        </div>
                        <div class="card-body">
                            <div class="completion-details">
                                <div class="success-icon">
                                    <span class="material-icons-sharp">check_circle</span>
                                </div>
                                <p>This order was successfully delivered on:</p>
                                <p class="delivery-date"><?php echo date('F j, Y g:i A', strtotime($data['order']->delivered_at)); ?></p>

                                <?php if (!empty($data['order']->delivery_report)): ?>
                                    <div class="report-link">
                                        <a href="<?php echo URLROOT; ?>/uploads/delivery_reports/<?php echo $data['order']->delivery_report; ?>"
                                            target="_blank" class="btn view-report-btn">
                                            <span class="material-icons-sharp">visibility</span> View Delivery Report
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // For cash payment orders, enable upload field only after payment confirmation
        const paymentReceivedCheckbox = document.getElementById('paymentReceived');
        if (paymentReceivedCheckbox) {
            paymentReceivedCheckbox.addEventListener('change', function() {
                const reportUpload = document.querySelector('.report-upload');
                const deliveryReportInput = document.getElementById('delivery_report');
                const confirmBtn = document.getElementById('confirmDeliveryBtn');

                if (this.checked) {
                    reportUpload.style.opacity = '1';
                    deliveryReportInput.disabled = false;
                    confirmBtn.disabled = false;
                } else {
                    reportUpload.style.opacity = '0.5';
                    deliveryReportInput.disabled = true;
                    confirmBtn.disabled = true;
                }
            });
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/deliveryPerson/footer.php'; ?>
</body>

</html>