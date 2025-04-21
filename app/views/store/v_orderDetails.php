<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/orderDetails.css">
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
            <div class="order-details-container">
                <div class="order-header">
                    <h1>Order #<?php echo $data['order']->order_number; ?></h1>
                    <span class="status <?php echo $data['order']->status; ?>"><?php echo ucfirst($data['order']->status); ?></span>
                </div>

                <div class="order-info">
                    <div class="order-summary">
                        <h2>Order Summary</h2>
                        <p>Date: <?php echo date('F j, Y H:i', strtotime($data['order']->created_at)); ?></p>
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

                <?php if (is_null($data['payment'])): ?>
                    <div class="payment-pending">
                        <h3>Payment Pending</h3>
                        <p>Please Confirm your Payment.</p>
                        <a href="<?php echo URLROOT; ?>/store/payment/<?php echo $data['order']->id; ?>" class="payment-btn">Proceed to payment</a>
                    </div>
                <?php endif; ?>

                <?php if (!is_null($data['payment']) && $data['payment']->status == 'rejected' && $data['payment']->payment_method == 'bank_deposit'): ?>
                    <div class="reject-payment">
                        <h3>Your Payment is Rejected</h3>
                        <p>Rejected Reason : <?php echo $data['payment']->rejection_reason; ?>.</p>
                        <a href="<?php echo URLROOT; ?>/store/payment/<?php echo $data['order']->id; ?>" class="upload-slip-btn">Upload Slip</a>
                    </div>
                <?php endif; ?>
                <?php if (!is_null($data['payment']) && $data['payment']->status == 'pending_verification' && $data['payment']->payment_method == 'bank_deposit'): ?>
                    <div class="payment-verifying">
                        <h3>Your Payment is being verifing.</h3>
                        <p>Please awite.</p>
                    </div>
                <?php endif; ?>
                <?php if (!is_null($data['payment']) && $data['payment']->status == 'approved' && $data['payment']->payment_method == 'bank_deposit'): ?>
                    <div class="payment-confirm">
                        <h3>Your Payment has confirmed.</h3>
                        <p></p>
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

    <?php require APPROOT . '/views/client/footer.php'; ?>