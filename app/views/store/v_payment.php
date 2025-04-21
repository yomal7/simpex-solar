<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/payment.css">
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
                    <i class='bx bx-arrow-back'></i>Back to orders
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
            <div class="payment-container">
                <h1>Payment</h1>

                <div class="order-details">
                    <h2>Order #<?php echo $data['order']->order_number; ?></h2>
                    <p>Total Amount: Rs. <?php echo number_format($data['order']->total_amount, 2); ?></p>
                    <p>Shipping Address: <?php echo nl2br(htmlspecialchars($data['order']->shipping_address)); ?></p>
                    <p>Contact Phone: <?php echo htmlspecialchars($data['order']->contact_phone); ?></p>

                    <div class="order-actions">
                        <a href="<?php echo URLROOT; ?>/store/paymentCheckout/<?php echo $data['order']->id; ?>" class="edit-details-btn">
                            <i class='bx bx-edit'></i> Change Order Details
                        </a>
                    </div>
                </div>

                <div class="warning-message">
                    <i class='bx bx-info-circle'></i>
                    <p><strong>Important:</strong> Once you confirm your payment method and proceed, you will no longer be able to modify your order details or change the payment method.</p>
                </div>

                <?php if ($data['order']->payment_method == 'bank_deposit'): ?>
                    <div class="bank-deposit-form">
                        <h3>Bank Deposit</h3>
                        <p>Please transfer the amount to one of our bank accounts and upload the deposit slip.</p>

                        <div class="bank-accounts">
                            <div class="bank-account">
                                <h4>Bank of Ceylon</h4>
                                <p>Account Name: SimplEx Solar Solutions</p>
                                <p>Account Number: 12345678</p>
                                <p>Branch: Colombo</p>
                            </div>
                            <div class="bank-account">
                                <h4>Commercial Bank</h4>
                                <p>Account Name: SimplEx Solar Solutions</p>
                                <p>Account Number: 87654321</p>
                                <p>Branch: Colombo</p>
                            </div>
                        </div>

                        <form action="<?php echo URLROOT; ?>/store/processPayment" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                            <input type="hidden" name="payment_method" value="bank_deposit">

                            <div class="form-group">
                                <label for="bank_slip">Upload Bank Slip</label>
                                <input type="file" id="bank_slip" name="bank_slip" accept="image/*,.pdf" required>
                            </div>

                            <button type="submit" class="submit-btn">Submit Payment</button>
                        </form>
                    </div>
                <?php elseif ($data['order']->payment_method == 'cash'): ?>
                    <div class="cash-payment">
                        <h3>Cash on Delivery</h3>
                        <p>You have selected to pay with cash upon delivery. No action is required at this time.</p>
                        <p>Please have the exact amount ready when the delivery arrives.</p>

                        <form action="<?php echo URLROOT; ?>/store/processPayment" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                            <input type="hidden" name="payment_method" value="cash">

                            <button type="submit" class="confirm-btn">Confirm Order</button>
                        </form>
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