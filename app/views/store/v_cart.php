<?php require APPROOT . '/views/store/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/cart.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php flash('cart_message'); ?>

        <?php if (!empty($data['cartItems'])): ?>
            <div class="cart-items">
                <?php foreach ($data['cartItems'] as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?php echo !empty($item->image1) ? URLROOT . '/public/uploads/store/' . $item->image1 : URLROOT . '/public/assets/default-product.png'; ?>"
                                alt="<?php echo htmlspecialchars($item->name); ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item->name); ?></h3>
                            <p class="price">Rs. <?php echo number_format($item->price_at_time, 2); ?></p>
                        </div>
                        <div class="item-quantity">
                            <form action="<?php echo URLROOT; ?>/store/updateCart" method="POST" class="update-form">
                                <input type="hidden" name="cart_id" value="<?php echo $item->id; ?>">
                                <input type="number" name="quantity" value="<?php echo $item->quantity; ?>" min="1" max="99">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </div>
                        <div class="item-total">
                            Rs. <?php echo number_format($item->price_at_time * $item->quantity, 2); ?>
                        </div>
                        <div class="item-remove">
                            <a href="<?php echo URLROOT; ?>/store/removeFromCart/<?php echo $item->id; ?>"
                                class="remove-btn"
                                onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>

                <form action="<?php echo URLROOT; ?>/store/checkout" method="POST">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="<?php echo URLROOT; ?>/store" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>