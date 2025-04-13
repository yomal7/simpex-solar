<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/projectBank.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/firstPayment/<?php echo $data['pre_project_id']; ?>" class="back-buttons">
                    <i class='bx bx-arrow-back'></i>Back
                </a>
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
            <?php flash('bank_payment_message'); ?>

            <div class="header-section">
                <h1>Bank Deposit Payment</h1>
                <p>Please complete the payment form below to proceed with your project</p>
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
                    <div class="cost-row minimum">
                        <span class="cost-label">Minimum Payment Required (25%):</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['minimum_payment'], 2); ?></span>
                    </div>
                </div>
            </div>

            <?php if (!isset($data['slip_downloaded']) || !$data['slip_downloaded']): ?>
                <div class="payment-form-section">
                    <h2>Payment Information</h2>
                    <form id="paymentForm">
                        <div class="form-group">
                            <label for="payment_amount">Payment Amount (Rs.)</label>
                            <input type="number" id="payment_amount" name="payment_amount"
                                value="<?php echo number_format($data['minimum_payment'], 2, '.', ''); ?>"
                                min="<?php echo $data['minimum_payment']; ?>"
                                max="<?php echo $data['total_price']; ?>" step="0.01" required>
                            <small class="form-text">Amount must be at least 25% of the total project cost</small>
                        </div>

                        <div class="form-group">
                            <label>Select Bank</label>
                            <div class="bank-cards">
                                <?php foreach (BANK_ACCOUNTS as $index => $bank): ?>
                                    <div class="bank-card" data-bank-index="<?php echo $index; ?>">
                                        <div class="bank-header">
                                            <h3><?php echo $bank['bank_name']; ?></h3>
                                            <div class="checkbox">
                                                <input type="radio" name="selected_bank" id="bank_<?php echo $index; ?>" value="<?php echo $index; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                                <label for="bank_<?php echo $index; ?>"></label>
                                            </div>
                                        </div>
                                        <div class="bank-details">
                                            <div class="bank-detail">
                                                <span>Account Name:</span> <?php echo $bank['account_name']; ?>
                                            </div>
                                            <div class="bank-detail">
                                                <span>Account Number:</span> <?php echo $bank['account_number']; ?>
                                            </div>
                                            <div class="bank-detail">
                                                <span>Branch:</span> <?php echo $bank['branch']; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="button" id="downloadSlipBtn" class="btn-download">
                            <i class="material-icons">download</i> Download Bank Slip
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="slip-upload-section">
                    <h2>Upload Bank Deposit Slip</h2>
                    <p>Please upload a photo or scan of your deposit slip after making the payment</p>

                    <div class="file-drop-area" id="dropArea">
                        <!-- Upload UI elements -->
                        <div class="upload-ui" id="uploadUI">
                            <i class="material-icons upload-icon">cloud_upload</i>
                            <p>Drag and drop your deposit slip here or</p>
                            <label class="choose-file-btn">
                                Choose File
                                <input type="file" id="fileInput" hidden accept="image/*">
                            </label>
                            <p>Supported formats: PNG, JPG (Max 5MB)</p>
                        </div>
                        <!-- Preview container -->
                        <div class="preview-container" id="previewContainer">
                            <img id="dropAreaPreview" alt="Preview">
                            <div class="file-info" id="fileInfo">
                                <span id="fileName"></span>
                            </div>
                            <button class="remove-preview" id="removeFile">
                                <i class="material-icons">close</i>
                            </button>
                        </div>
                    </div>

                    <div class="upload-actions">
                        <button id="submitButton" class="btn-submit" disabled>
                            Submit Deposit Slip
                        </button>
                        <button id="regenerateSlipBtn" class="btn-secondary">
                            <i class="material-icons">refresh</i> Generate New Slip
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Confirm Payment Modal -->
        <div class="modal" id="confirmModal">
            <div class="modal-content">
                <h2>Confirm Payment Details</h2>
                <p>Please confirm your payment details before downloading the bank slip:</p>

                <div class="confirm-details">
                    <div class="detail-row">
                        <span class="label">Payment Amount:</span>
                        <span id="confirmAmount">Rs. 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Bank:</span>
                        <span id="confirmBank">Bank Name</span>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button class="btn-secondary" onclick="closeModal()">Edit Details</button>
                    <button class="btn-primary" id="confirmPaymentBtn">Confirm & Download</button>
                </div>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        const projectId = '<?php echo $data['project_id']; ?>';
        const preProjectId = '<?php echo $data['pre_project_id']; ?>';
        const minimumPayment = <?php echo $data['minimum_payment']; ?>;
        const totalCost = <?php echo $data['total_price']; ?>;
        const bankAccounts = <?php echo json_encode(BANK_ACCOUNTS); ?>;

        // DOM elements
        const paymentForm = document.getElementById('paymentForm');
        const paymentAmountInput = document.getElementById('payment_amount');
        const downloadSlipBtn = document.getElementById('downloadSlipBtn');
        const confirmModal = document.getElementById('confirmModal');
        const overlay = document.getElementById('overlay');
        const confirmAmount = document.getElementById('confirmAmount');
        const confirmBank = document.getElementById('confirmBank');
        const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

        <?php if (isset($data['slip_downloaded']) && $data['slip_downloaded']): ?>
            // File upload handlers
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('fileInput');
            const uploadUI = document.getElementById('uploadUI');
            const previewContainer = document.getElementById('previewContainer');
            const dropAreaPreview = document.getElementById('dropAreaPreview');
            const fileName = document.getElementById('fileName');
            const removeFileBtn = document.getElementById('removeFile');
            const submitButton = document.getElementById('submitButton');
            const regenerateSlipBtn = document.getElementById('regenerateSlipBtn');

            // Drag and drop handlers
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.remove('dragover');
                });
            });

            dropArea.addEventListener('drop', handleDrop);
            fileInput.addEventListener('change', handleFileSelect);
            removeFileBtn.addEventListener('click', removeFile);

            function handleDrop(e) {
                const files = e.dataTransfer.files;
                handleFiles(files);
            }

            function handleFileSelect(e) {
                const files = e.target.files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (files.length) {
                    const file = files[0];
                    if (validateFile(file)) {
                        showPreview(file);
                        submitButton.disabled = false;
                    }
                }
            }

            function validateFile(file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (PNG or JPG)');
                    return false;
                }

                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    return false;
                }

                return true;
            }

            function showPreview(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    dropAreaPreview.src = e.target.result;
                    fileName.textContent = file.name;
                    uploadUI.style.display = 'none';
                    previewContainer.classList.add('show');
                };

                reader.readAsDataURL(file);
            }

            function removeFile() {
                fileInput.value = '';
                uploadUI.style.display = 'flex';
                previewContainer.classList.remove('show');
                dropAreaPreview.src = '';
                submitButton.disabled = true;
            }

            // Submit button handler
            submitButton.addEventListener('click', async function() {
                if (!fileInput.files[0]) {
                    alert('Please select a file first');
                    return;
                }

                try {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Uploading...';

                    const formData = new FormData();
                    formData.append('slip', fileInput.files[0]);
                    formData.append('payment_id', '<?php echo isset($data['payment_id']) ? $data['payment_id'] : ''; ?>');

                    const response = await fetch(`${URLROOT}/client/uploadBankSlip`, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Deposit slip uploaded successfully!');
                        window.location.href = `${URLROOT}/client/project`;
                    } else {
                        throw new Error(data.message || 'Error uploading deposit slip');
                    }
                } catch (error) {
                    alert(error.message || 'An error occurred while uploading your deposit slip');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Submit Deposit Slip';
                }
            });

            // Regenerate slip button handler
            regenerateSlipBtn.addEventListener('click', function() {
                window.location.href = `${URLROOT}/client/resetBankSlip/${preProjectId}`;
            });
        <?php else: ?>
            // Payment form handlers
            downloadSlipBtn.addEventListener('click', function() {
                const amount = parseFloat(paymentAmountInput.value);
                const selectedBankIndex = parseInt(document.querySelector('input[name="selected_bank"]:checked').value);

                // Validate amount
                if (isNaN(amount) || amount < minimumPayment || amount > totalCost) {
                    alert(`Please enter a valid amount between Rs. ${minimumPayment.toFixed(2)} and Rs. ${totalCost.toFixed(2)}`);
                    return;
                }

                // Show confirmation modal
                confirmAmount.textContent = `Rs. ${amount.toFixed(2)}`;
                confirmBank.textContent = bankAccounts[selectedBankIndex].bank_name;

                confirmModal.style.display = 'block';
                overlay.style.display = 'block';
            });

            confirmPaymentBtn.addEventListener('click', async function() {
                const amount = parseFloat(paymentAmountInput.value);
                const selectedBankIndex = parseInt(document.querySelector('input[name="selected_bank"]:checked').value);

                try {
                    confirmPaymentBtn.disabled = true;
                    confirmPaymentBtn.textContent = 'Processing...';

                    const response = await fetch(`${URLROOT}/client/generateBankSlip`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            project_id: projectId,
                            pre_project_id: preProjectId,
                            amount: amount,
                            bank_index: selectedBankIndex,
                            payment_phase: 'first_payment'
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Download the slip
                        window.location.href = `${URLROOT}/client/downloadBankSlip/${data.payment_id}`;

                        // Reload the page after a short delay to show upload section
                        setTimeout(() => {
                            window.location.href = `${URLROOT}/client/projectBankDeposit/${preProjectId}`;
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Error generating bank slip');
                    }
                } catch (error) {
                    alert(error.message || 'An error occurred while processing your request');
                } finally {
                    confirmPaymentBtn.disabled = false;
                    confirmPaymentBtn.textContent = 'Confirm & Download';
                    closeModal();
                }
            });

            function closeModal() {
                confirmModal.style.display = 'none';
                overlay.style.display = 'none';
            }

            // Bank card selection
            const bankCards = document.querySelectorAll('.bank-card');
            bankCards.forEach(card => {
                card.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    radioInput.checked = true;
                });
            });

            // Close modal when clicking overlay
            overlay.addEventListener('click', closeModal);
        <?php endif; ?>
    </script>

    <?php require APPROOT . '/views/client/footer.php'; ?>