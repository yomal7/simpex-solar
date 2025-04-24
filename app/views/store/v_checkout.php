<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/checkout.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/store/cart" style="background-color: rgb(192, 236, 192);" class="back-buttons">
                    <i class='bx bx-arrow-back'></i>Back to Cart
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
            <div class="checkout-container">
                <h1>Checkout</h1>

                <div class="checkout-grid">
                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h2>Order Summary</h2>
                        <?php foreach ($data['cartItems'] as $item): ?>
                            <div class="summary-item">
                                <img src="<?php echo !empty($item->image1) ? URLROOT . '/public/uploads/store/' . $item->image1 : URLROOT . '/public/assets/default-product.png'; ?>"
                                    alt="<?php echo htmlspecialchars($item->name); ?>">
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item->name); ?></h4>
                                    <p>Quantity: <?php echo $item->quantity; ?></p>
                                    <p>Rs. <?php echo number_format($item->price_at_time * $item->quantity, 2); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <div class="checkout-form">
                        <h2>Shipping Information</h2>
                        <form action="<?php echo URLROOT; ?>/store/processOrder" method="POST">
                            <div class="form-group">
                                <label for="shipping_address">Shipping Address</label>
                                <textarea id="shipping_address" name="shipping_address" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="contact_phone">Contact Phone</label>
                                <input type="tel" id="contact_phone" name="contact_phone" required>
                            </div>

                            <h2>Payment Method</h2>
                            <div class="payment-methods">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="bank_deposit" checked>
                                    <span>Bank Deposit</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cash">
                                    <span>Cash on Delivery</span>
                                </label>
                            </div>

                            <button type="submit" class="place-order-btn">Place Order</button>
                        </form>
                    </div>
                </div>
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