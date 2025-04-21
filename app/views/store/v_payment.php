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

                <?php if ($data['order']->payment_method == 'bank_deposit' && is_null($data['payment'])): ?>
                    <div class="bank-deposit-form">
                        <h3>Bank Deposit</h3>
                        <p>Please select a bank account, download the deposit slip, and then upload your completed slip after making the payment.</p>

                        <!-- Step 1: Bank Selection -->
                        <div class="deposit-steps">
                            <div class="step active" id="step-bank-selection">
                                <div class="step-number">1</div>
                                <div class="step-title">Select Bank</div>
                            </div>
                            <div class="step" id="step-download-slip">
                                <div class="step-number">2</div>
                                <div class="step-title">Download Slip</div>
                            </div>
                            <div class="step" id="step-upload-slip">
                                <div class="step-number">3</div>
                                <div class="step-title">Upload Slip</div>
                            </div>
                        </div>

                        <!-- Bank Account Selection -->
                        <div class="bank-account-selection" id="bank-selection-section">
                            <div class="bank-accounts">
                                <?php foreach (BANK_ACCOUNTS as $index => $bank): ?>
                                    <div class="bank-account <?php echo $index === 0 ? 'selected' : ''; ?>" data-bank-id="<?php echo $index; ?>">
                                        <div class="bank-logo">
                                            <i class='bx bxs-bank'></i>
                                        </div>
                                        <div class="bank-details">
                                            <h4><?php echo $bank['bank_name']; ?></h4>
                                            <p>Account: <?php echo $bank['account_number']; ?></p>
                                            <p>Branch: <?php echo $bank['branch']; ?></p>
                                        </div>
                                        <div class="selection-indicator">
                                            <i class='bx bx-check'></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" id="download-slip-btn" class="action-btn">
                                <i class='bx bx-download'></i> Download Bank Deposit Slip
                            </button>
                        </div>

                        <!-- Slip Upload Section (Hidden initially) -->
                        <div class="slip-upload-section" id="slip-upload-section" style="display: none;">
                            <div class="upload-instructions">
                                <i class='bx bx-info-circle'></i>
                                <p>Your bank slip has been downloaded. After completing the deposit, please upload the scanned/photographed slip below.</p>
                            </div>

                            <form action="<?php echo URLROOT; ?>/store/processPayment" method="POST" enctype="multipart/form-data" id="upload-form">
                                <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                                <input type="hidden" name="payment_method" value="bank_deposit">
                                <input type="hidden" name="bank_id" id="selected-bank-id" value="0">

                                <div class="form-group">
                                    <label for="bank_slip">Upload Bank Slip</label>
                                    <div class="file-upload-container">
                                        <div class="upload-preview" id="upload-preview">
                                            <i class='bx bx-upload'></i>
                                            <p>Drag & drop or click to select</p>
                                        </div>
                                        <input type="file" id="bank_slip" name="bank_slip" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="file-details" id="file-details" style="display: none;">
                                        <p id="file-name"></p>
                                        <button type="button" id="remove-file" class="remove-btn">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="submit-btn" id="submit-payment-btn" disabled>Submit Payment</button>
                            </form>
                        </div>
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

                <?php if ($data['order']->payment_method == 'bank_deposit' && !is_null($data['payment']) && $data['payment']->status == 'rejected'): ?>
                    <div class="payment-rejection">
                        <div class="rejection-header">
                            <i class='bx bx-x-circle'></i>
                            <h3>Your payment has been rejected</h3>
                        </div>

                        <div class="rejection-reason">
                            <p><strong>Reason:</strong> <?php echo htmlspecialchars($data['payment']->rejection_reason); ?></p>
                        </div>

                        <div class="upload-section">
                            <h4>Upload New Bank Slip</h4>
                            <form action="<?php echo URLROOT; ?>/store/processPayment" method="POST" enctype="multipart/form-data" id="rejected-upload-form">
                                <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                                <input type="hidden" name="payment_method" value="bank_deposit">
                                <input type="hidden" name="resubmission" value="true">

                                <div class="form-group">
                                    <label for="bank_slip_resubmit">Upload Corrected Bank Slip</label>
                                    <div class="file-upload-container">
                                        <div class="upload-preview" id="resubmit-upload-preview">
                                            <i class='bx bx-upload'></i>
                                            <p>Drag & drop or click to select</p>
                                        </div>
                                        <input type="file" id="bank_slip_resubmit" name="bank_slip" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="file-details" id="resubmit-file-details" style="display: none;">
                                        <p id="resubmit-file-name"></p>
                                        <button type="button" id="resubmit-remove-file" class="remove-btn">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="submit-btn" id="resubmit-payment-btn" disabled>Submit Updated Slip</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        // Toggle sidebar function
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.bx-menu');
            const sidebar = document.querySelector('.sidebar');

            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('close');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const bankAccounts = document.querySelectorAll('.bank-account');
            const downloadBtn = document.getElementById('download-slip-btn');
            const uploadSection = document.getElementById('slip-upload-section');
            const bankSelectionStep = document.getElementById('step-bank-selection');
            const downloadSlipStep = document.getElementById('step-download-slip');
            const uploadSlipStep = document.getElementById('step-upload-slip');
            const bankSlipInput = document.getElementById('bank_slip');
            const fileDetails = document.getElementById('file-details');
            const fileName = document.getElementById('file-name');
            const removeFileBtn = document.getElementById('remove-file');
            const submitBtn = document.getElementById('submit-payment-btn');
            const uploadPreview = document.getElementById('upload-preview');
            const selectedBankIdInput = document.getElementById('selected-bank-id');
            const orderId = document.querySelector('input[name="order_id"]').value;

            // Bank selection
            bankAccounts.forEach(bank => {
                bank.addEventListener('click', function() {
                    // Remove selected class from all banks
                    bankAccounts.forEach(item => {
                        item.classList.remove('selected');
                    });

                    // Add selected class to clicked bank
                    this.classList.add('selected');

                    // Update hidden input with selected bank ID
                    selectedBankIdInput.value = this.dataset.bankId;

                    // Enable download button
                    downloadBtn.disabled = false;
                });
            });

            // Download slip button click
            downloadBtn.addEventListener('click', function() {
                const bankId = selectedBankIdInput.value;

                // Call API to generate and download slip
                downloadBankSlip(orderId, bankId);
            });

            // File input change
            bankSlipInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    // Show file details
                    fileName.textContent = this.files[0].name;
                    fileDetails.style.display = 'flex';
                    uploadPreview.style.display = 'none';

                    // Enable submit button
                    submitBtn.disabled = false;
                }
            });

            // Remove file button click
            removeFileBtn.addEventListener('click', function() {
                // Clear file input
                bankSlipInput.value = '';

                // Hide file details
                fileDetails.style.display = 'none';
                uploadPreview.style.display = 'flex';

                // Disable submit button
                submitBtn.disabled = true;
            });

            // Functions
            function downloadBankSlip(orderId, bankId) {
                // Show loading state
                downloadBtn.disabled = true;
                downloadBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Generating...';

                // API call to generate slip
                fetch(`${URLROOT}/store/generateBankSlip`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            bank_id: bankId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to generate bank slip');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Create download link
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = `bank_slip_order_${orderId}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                        // Record the download
                        recordSlipDownload(orderId, bankId);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to generate bank slip. Please try again.');

                        // Reset button
                        downloadBtn.disabled = false;
                        downloadBtn.innerHTML = '<i class="bx bx-download"></i> Download Bank Deposit Slip';
                    });
            }

            function recordSlipDownload(orderId, bankId) {
                // API call to record slip download
                fetch(`${URLROOT}/store/recordSlipDownload`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            bank_id: bankId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI to show upload section
                            bankSelectionStep.classList.remove('active');
                            bankSelectionStep.classList.add('completed');
                            downloadSlipStep.classList.remove('active');
                            downloadSlipStep.classList.add('completed');
                            uploadSlipStep.classList.add('active');

                            // Show upload section
                            uploadSection.style.display = 'block';

                            // Reset download button
                            downloadBtn.innerHTML = '<i class="bx bx-download"></i> Download Bank Deposit Slip Again';
                            downloadBtn.disabled = false;
                        } else {
                            throw new Error('Failed to record slip download');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('There was an issue recording your download. Please try again.');

                        // Reset button
                        downloadBtn.disabled = false;
                        downloadBtn.innerHTML = '<i class="bx bx-download"></i> Download Bank Deposit Slip';
                    });
            }

            // Check if slip was already downloaded
            function checkSlipDownload() {
                fetch(`${URLROOT}/store/checkSlipDownload/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.downloaded) {
                            // Update UI to show upload section
                            bankSelectionStep.classList.remove('active');
                            bankSelectionStep.classList.add('completed');
                            downloadSlipStep.classList.remove('active');
                            downloadSlipStep.classList.add('completed');
                            uploadSlipStep.classList.add('active');

                            // Show upload section
                            uploadSection.style.display = 'block';

                            // Set the selected bank
                            const bankId = data.bank_id;
                            selectedBankIdInput.value = bankId;

                            // Highlight the previously selected bank
                            bankAccounts.forEach(bank => {
                                if (bank.dataset.bankId == bankId) {
                                    bank.classList.add('selected');
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error checking slip download:', error);
                    });
            }

            // Initialize
            checkSlipDownload();
        });

        // Resubmission form handling
        const resubmitSlipInput = document.getElementById('bank_slip_resubmit');
        const resubmitFileDetails = document.getElementById('resubmit-file-details');
        const resubmitFileName = document.getElementById('resubmit-file-name');
        const resubmitRemoveFileBtn = document.getElementById('resubmit-remove-file');
        const resubmitSubmitBtn = document.getElementById('resubmit-payment-btn');
        const resubmitUploadPreview = document.getElementById('resubmit-upload-preview');

        if (resubmitSlipInput) {
            resubmitSlipInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    // Show file details
                    resubmitFileName.textContent = this.files[0].name;
                    resubmitFileDetails.style.display = 'flex';
                    resubmitUploadPreview.style.display = 'none';

                    // Enable submit button
                    resubmitSubmitBtn.disabled = false;
                }
            });

            // Remove file button click
            resubmitRemoveFileBtn.addEventListener('click', function() {
                // Clear file input
                resubmitSlipInput.value = '';

                // Hide file details
                resubmitFileDetails.style.display = 'none';
                resubmitUploadPreview.style.display = 'flex';

                // Disable submit button
                resubmitSubmitBtn.disabled = true;
            });
        }
    </script>

    <?php require APPROOT . '/views/store/footer.php'; ?>