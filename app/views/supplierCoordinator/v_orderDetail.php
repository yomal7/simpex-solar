<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/orderDetails.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/adminnavbar.php'; ?>

    <div class="order-details-container">
        <div class="order-header">
            <h1>Order #<?php echo $data['order']->order_number; ?></h1>
            <a href="<?php echo URLROOT; ?>/supplierCoordinator/orders" class="back-link">← Back to Orders</a>
        </div>

        <?php flash('order_message'); ?>
        <?php flash('payment_message'); ?>

        <div class="order-layout">
            <!-- Order Info -->
            <div class="order-section order-info">
                <h2>Order Information</h2>
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
                        <span><?php echo date('Y-m-d H:i', strtotime($data['order']->created_at)); ?></span>
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

                <!-- Status Update Form -->
                <form action="<?php echo URLROOT; ?>/supplierCoordinator/updateOrderStatus" method="POST" class="status-update-form">
                    <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                    <div class="form-group">
                        <label for="status">Update Status:</label>
                        <select name="status" id="status">
                            <option value="pending" <?php echo $data['order']->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $data['order']->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo $data['order']->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $data['order']->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $data['order']->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>

            <!-- Shipping Info -->
            <div class="order-section shipping-info">
                <h2>Shipping Information</h2>
                <div class="address-block">
                    <?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-section order-items">
            <h2>Order Items</h2>
            <table>
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

        <!-- Payment Info -->
        <?php if ($data['payment']): ?>
            <div class="order-section payment-info">
                <h2>Payment Details</h2>
                <div class="payment-status">
                    Status: <span class="status-badge <?php echo $data['payment']->status; ?>">
                        <?php echo ucfirst($data['payment']->status); ?>
                    </span>
                </div>

                <?php if ($data['payment']->payment_method == 'bank_deposit' && $data['payment']->bank_slip): ?>
                    <div class="bank-slip">
                        <h3>Bank Slip</h3>
                        <a href="<?php echo URLROOT; ?>/uploads/bank_slips/<?php echo $data['payment']->bank_slip; ?>" target="_blank">
                            <img src="<?php echo URLROOT; ?>/uploads/bank_slips/<?php echo $data['payment']->bank_slip; ?>" alt="Bank Slip">
                        </a>

                        <?php if ($data['payment']->status == 'pending_verification'): ?>
                            <form action="<?php echo URLROOT; ?>/supplierCoordinator/verifyPayment" method="POST" class="payment-verification-form">
                                <input type="hidden" name="payment_id" value="<?php echo $data['payment']->id; ?>">
                                <div class="button-group">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve Payment</button>
                                    <button type="button" onclick="showRejectForm()" class="btn btn-danger">Reject Payment</button>
                                </div>

                                <div id="rejectForm" style="display: none;">
                                    <div class="form-group">
                                        <label for="rejection_reason">Rejection Reason:</label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="3"></textarea>
                                    </div>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Confirm Rejection</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($data['payment']->status == 'rejected' && $data['payment']->rejection_reason): ?>
                    <div class="rejection-reason">
                        <h3>Rejection Reason</h3>
                        <p><?php echo htmlspecialchars($data['payment']->rejection_reason); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function showRejectForm() {
            document.getElementById('rejectForm').style.display = 'block';
        }
    </script>

    <?php require APPROOT . '/views/inc/components/adminFooter.php'; ?>