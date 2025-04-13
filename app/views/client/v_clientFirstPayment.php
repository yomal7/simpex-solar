<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/firstPayment.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a>
            </li>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>
        <!-- End of Navbar -->

        <div class="container">
            <?php flash('payment_message'); ?>

            <div class="status-bar">
                <h2>Payment Status</h2>
                <div class="status-indicator <?php echo isset($data['existing_payment']) && $data['existing_payment']->payment_status ? 'paid' : 'pending'; ?>">
                    <?php echo isset($data['existing_payment']) && $data['existing_payment']->payment_status ? 'Paid' : 'Pending'; ?>
                </div>
            </div>

            <div class="payment-details-card">
                <h2>Project Cost Details</h2>
                <div class="cost-breakdown">
                    <div class="cost-row">
                        <span class="cost-label">Base Price:</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['base_price'], 2); ?></span>
                    </div>
                    <div class="cost-row">
                        <span class="cost-label">Service Charge:</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['service_charge'], 2); ?></span>
                    </div>
                    <div class="cost-row total">
                        <span class="cost-label">Total Project Cost:</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['total_price'], 2); ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-container">
                <div class="amount-display">
                    <h3>Amount Due (25% of Total Project Cost)</h3>
                    <div class="amount">Rs. <?php echo number_format($data['first_payment_amount'], 2); ?></div>
                    <p class="payment-note">Please note: This is the minimum required payment to proceed with your solar installation project.</p>
                </div>

                <?php if (!isset($data['existing_payment']) || !$data['existing_payment']->payment_status): ?>
                    <div class="payment-options">
                        <div class="payment-option" onclick="confirmPaymentMethod('online')">
                            <i class="material-icons">credit_card</i>
                            <h3>Online Banking</h3>
                            <p>Pay securely using your bank account</p>
                        </div>

                        <div class="payment-option" onclick="confirmPaymentMethod('bank_deposit')">
                            <i class="material-icons">upload_file</i>
                            <h3>Bank Deposit</h3>
                            <p>Upload your payment confirmation</p>
                        </div>

                        <div class="payment-option" onclick="confirmPaymentMethod('cash')">
                            <i class="material-icons">payments</i>
                            <h3>Cash Payment</h3>
                            <p>Pay in cash at our office</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="payment-success">
                        <i class="material-icons success-icon">check_circle</i>
                        <h3>Payment Complete</h3>
                        <p>Your payment has been received. Thank you!</p>
                        <p>Amount Paid: Rs. <?php echo number_format($data['existing_payment']->amount, 2); ?></p>
                        <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $data['existing_payment']->payment_method)); ?></p>
                        <p>Payment Date: <?php echo date('F j, Y', strtotime($data['existing_payment']->created_at)); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payment Method Confirmation Popup -->
        <div class="popup" id="paymentMethodPopup">
            <div class="popup-content">
                <h2>Confirm Payment Method</h2>
                <p>You have selected <span id="selectedMethod"></span> as your payment method.</p>
                <p>Do you want to proceed with this payment method?</p>

                <div class="popup-buttons">
                    <button class="btn-secondary" onclick="closePopup()">Cancel</button>
                    <button class="btn-primary" onclick="processPaymentMethod()">Confirm</button>
                </div>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>
    </div>

    <script>
        let selectedPaymentMethod = '';
        const projectId = '<?php echo $data['project_id']; ?>';
        const preProjectId = '<?php echo $data['pre_project_id']; ?>';
        const paymentAmount = <?php echo $data['first_payment_amount']; ?>;
        const URLROOT = '<?php echo URLROOT; ?>';

        function confirmPaymentMethod(method) {
            selectedPaymentMethod = method;
            document.getElementById('selectedMethod').textContent = method.replace('_', ' ');
            document.getElementById('paymentMethodPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('paymentMethodPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function processPaymentMethod() {
            // Send AJAX request to record payment method choice
            fetch(`${URLROOT}/client/selectPaymentMethod`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        project_id: projectId,
                        payment_method: selectedPaymentMethod,
                        amount: paymentAmount,
                        payment_phase: 'first_payment'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect based on payment method
                        switch (selectedPaymentMethod) {
                            case 'online':
                                window.location.href = `${URLROOT}/client/onlinePayment/${preProjectId}`;
                                break;
                            case 'bank_deposit':
                                window.location.href = `${URLROOT}/client/projectBankDeposit/${preProjectId}`;
                                break;
                            case 'cash':
                                window.location.href = `${URLROOT}/client/cashPayment/${preProjectId}`;
                                break;
                        }
                    } else {
                        alert(data.message || 'Error processing payment method selection');
                        closePopup();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request');
                    closePopup();
                });
        }

        // Close popup when clicking overlay
        document.getElementById('overlay').addEventListener('click', closePopup);
    </script>

    <?php require APPROOT . '/views/client/footer.php'; ?>