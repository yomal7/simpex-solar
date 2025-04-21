<?php require APPROOT . '/views/store/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/payment.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <div class="payment-container">
        <h1>Payment</h1>

        <div class="order-details">
            <h2>Order #<?php echo $data['order']->order_number; ?></h2>
            <p>Total Amount: Rs. <?php echo number_format($data['order']->total_amount, 2); ?></p>
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

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/store/footer.php'; ?>