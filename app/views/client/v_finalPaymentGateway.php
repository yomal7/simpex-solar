<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .payment-container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .payment-header {
        background-color: #2e7d32;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .payment-header h1 {
        margin: 0;
        font-size: 24px;
    }

    .payment-content {
        padding: 30px;
    }

    .payment-details {
        margin-bottom: 30px;
    }

    .payment-details h2 {
        color: #2e7d32;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
    }

    .detail-value {
        color: #333;
    }

    .amount {
        font-size: 24px;
        font-weight: 700;
        color: #2e7d32;
    }

    .payment-form h2 {
        color: #2e7d32;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .card-number {
        grid-column: 1 / 3;
    }

    .btn-container {
        text-align: center;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 25px;
        background-color: #2e7d32;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background-color: #256a27;
    }

    .security-notice {
        margin-top: 30px;
        background-color: #f8f8f8;
        padding: 15px;
        border-radius: 5px;
        font-size: 14px;
        color: #666;
    }

    .security-notice p {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .back-link {
        display: block;
        margin-top: 30px;
        text-align: center;
        color: #2e7d32;
        text-decoration: none;
        font-weight: 600;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .payment-container {
            margin: 20px;
        }

        .card-details {
            grid-template-columns: 1fr;
        }

        .card-number {
            grid-column: 1;
        }
    }
</style>
</head>

<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>Final Payment</h1>
        </div>

        <div class="payment-content">
            <div class="payment-details">
                <h2>Payment Details</h2>
                <div class="detail-row">
                    <span class="detail-label">Project ID:</span>
                    <span class="detail-value">PR<?php echo str_pad($data['project_id'], 5, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount">Rs. <?php echo number_format($data['amount'], 2); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Type:</span>
                    <span class="detail-value">Final Payment</span>
                </div>

            </div>

            <div class="payment-form">
                <h2>Card Details</h2>
                <form id="paymentForm" action="<?php echo URLROOT; ?>/client/completeFinalOnlinePayment" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                    <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                    <input type="hidden" name="payment_id" value="<?php echo $data['payment_id']; ?>">
                    <input type="hidden" name="payment_phase" value="<?php echo $data['payment_phase']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $data['amount']; ?>">

                    <div class="form-group card-number">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" placeholder="1234 5678 9012 3456" required
                            pattern="[0-9\s]{13,19}" maxlength="19">
                    </div>

                    <div class="card-details">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="text" id="expiry_date" placeholder="MM/YY" required
                                pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5">
                        </div>

                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" placeholder="123" required
                                pattern="[0-9]{3,4}" maxlength="4">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cardholder_name">Cardholder Name</label>
                        <input type="text" id="cardholder_name" placeholder="John Smith" required>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn">Pay Rs. <?php echo number_format($data['amount'], 2); ?></button>
                    </div>
                </form>
            </div>

            <div class="security-notice">
                <p>
                    <i class="fas fa-lock"></i>
                    Your payment information is secure. We use encryption to protect your data.
                </p>
            </div>

            <a href="<?php echo URLROOT; ?>/client/finalPayment/<?php echo $data['pre_project_id']; ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Payment Options
            </a>
        </div>
    </div>
    
    <script>
        // Format card number input with spaces
        document.getElementById('card_number').addEventListener('input', function(e) {
            // Remove non-digit characters
            let input = e.target.value.replace(/\D/g, '');

            // Add space after every 4 digits
            let formatted = '';
            for (let i = 0; i < input.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += input[i];
            }

            e.target.value = formatted;
        });

        // Format expiry date input with slash
        document.getElementById('expiry_date').addEventListener('input', function(e) {
            // Remove non-digit characters
            let input = e.target.value.replace(/\D/g, '');

            // Format as MM/YY
            if (input.length > 2) {
                e.target.value = input.substring(0, 2) + '/' + input.substring(2, 4);
            } else {
                e.target.value = input;
            }
        });

        // Simple form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // In a real application, you would validate the card details
            // and process the payment through a payment gateway

            // For this demo, we'll simulate a successful payment after a short delay
            const submitBtn = document.querySelector('.btn');
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;

            setTimeout(() => {
                this.submit();
            }, 2000);
        });
    </script>
</body>

</html>