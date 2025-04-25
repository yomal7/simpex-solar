<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/finalPayment.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a>
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
                <h2>Final Payment Status</h2>
                <div class="status-indicator <?php echo (isset($data['payment']) && is_object($data['payment']) && $data['payment']->payment_status) ? 'paid' : 'pending'; ?>">
                    <?php echo (isset($data['payment']) && is_object($data['payment']) && $data['payment']->payment_status) ? 'Paid' : 'Pending'; ?>
                </div>
            </div>

            <div class="payment-details-card">
                <h2>Project Cost Details</h2>
                <div class="cost-breakdown">
                    <div class="cost-row">
                        <span class="cost-label">First Payment (Already Paid):</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['first_payment_amount'], 2); ?></span>
                    </div>
                    <div class="cost-row total">
                        <span class="cost-label">Remaining Balance:</span>
                        <span class="cost-value">Rs. <?php echo number_format($data['payment_amount'], 2); ?></span>
                    </div>
                    <?php if ($data['payment_amount'] < $data['minimum_required']): ?>
                        <div class="cost-row minimum-required">
                            <span class="cost-label">Minimum Required Payment (75%):</span>
                            <span class="cost-value">Rs. <?php echo number_format($data['minimum_required'], 2); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="payment-container">
                <div class="amount-display">
                    <h3>Final Payment (Remaining Balance)</h3>
                    <div class="amount">Rs. <?php echo number_format($data['payment_amount'], 2); ?></div>
                    <p class="payment-note">This final payment completes your solar installation project.</p>
                </div>

                <?php if (!isset($data['payment']) || !is_object($data['payment']) || !$data['payment']->payment_status): ?>
                    <!-- Payment Methods Accordion -->
                    <!-- Bank Deposit Method -->
                    <div class="payment-method">
                        <div class="method-header" onclick="toggleMethod('bank-deposit')">
                            <h4><i class="fas fa-university"></i> Bank Deposit</h4>
                            <span class="toggle-icon">+</span>
                        </div>
                        <div class="method-content" id="bank-deposit">
                            <p>Make a bank deposit and upload your payment slip.</p>

                            <?php if (isset($data['bank_slip']) && is_object($data['bank_slip'])): ?>
                                <div class="slip-status">
                                    <p>Slip Status: <strong><?php echo ucfirst($data['bank_slip']->status); ?></strong></p>

                                    <?php if ($data['bank_slip']->status == 'pending' && $data['bank_slip']->slip_file): ?>
                                        <div class="processing-message">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <p>Your payment slip is being processed. Please check back later.</p>
                                        </div>
                                    <?php elseif ($data['bank_slip']->status == 'reject'): ?>
                                        <p class="rejection-reason">Reason: <?php echo $data['bank_slip']->reject_reason; ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (
                                !isset($data['bank_slip']) || !is_object($data['bank_slip']) ||
                                ($data['bank_slip']->slip_downloaded && !$data['bank_slip']->slip_file) ||
                                $data['bank_slip']->status == 'reject'
                            ): ?>

                                <?php if (!isset($data['bank_slip']) || !is_object($data['bank_slip']) || !$data['bank_slip']->slip_downloaded): ?>
                                    <h4>Select Bank Account and Payment Amount</h4>
                                    <form action="<?php echo URLROOT; ?>/client/generateFinalBankSlip" method="post" id="bankForm">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                        <input type="hidden" name="payment_phase" value="final_payment">

                                        <div class="form-group">
                                            <label for="bank_account">Bank Account:</label>
                                            <select name="bank_account" id="bank_account" required>
                                                <option value="">-- Select Bank --</option>
                                                <?php foreach (BANK_ACCOUNTS as $index => $bank): ?>
                                                    <option value="<?php echo $index; ?>"><?php echo $bank['bank_name']; ?> - <?php echo $bank['branch']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount">Payment Amount (Rs.):</label>
                                            <input type="number" id="amount" name="amount"
                                                value="<?php echo max($data['payment_amount'], $data['minimum_required']); ?>"
                                                min="<?php echo $data['minimum_required']; ?>"
                                                max="<?php echo $data['payment_amount']; ?>"
                                                step="0.01" readonly required>
                                            <small>Final payment amount: Rs. <?php echo number_format(max($data['payment_amount'], $data['minimum_required']), 2); ?></small>
                                            <?php if ($data['payment_amount'] < $data['minimum_required']): ?>
                                                <small class="text-info"><?php echo number_format($data['payment_amount'], 2); ?></small>
                                            <?php endif; ?>
                                        </div>



                                        <div class="selected-bank-details" id="selectedBankDetails">
                                            <!-- Bank details will be shown here -->
                                        </div>

                                        <div class="action-buttons">
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="fas fa-download"></i> Download Payment Slip
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>


                                <?php if ((isset($data['bank_slip']) && is_object($data['bank_slip']) &&
                                    $data['bank_slip']->slip_downloaded && !$data['bank_slip']->slip_file) || (isset($data['bank_slip']) && is_object($data['bank_slip']) && $data['bank_slip']->status == 'reject')): ?>
                                    <form action="<?php echo URLROOT; ?>/client/uploadFinalBankSlip" method="post" enctype="multipart/form-data" class="upload-form">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                        <input type="hidden" name="payment_phase" value="final_payment">
                                        <input type="hidden" name="payment_id" value="<?php echo isset($data['payment']) && is_object($data['payment']) ? $data['payment']->id : ''; ?>">
                                        <input type="hidden" name="amount" value="<?php echo isset($data['payment']) && is_object($data['payment']) ? $data['payment']->amount : $data['payment_amount']; ?>">
                                        <input type="hidden" name="slip_id" value="<?php echo $data['bank_slip']->id; ?>">

                                        <div class="form-group">
                                            <label for="payment_slip">Upload Signed Payment Slip (PDF only):</label>
                                            <input type="file" id="payment_slip" name="payment_slip" accept=".pdf" required>
                                            <small>Only PDF files are accepted. Maximum size: 5MB.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Slip</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Online Payment Method -->
                    <div class="payment-method">
                        <div class="method-header" onclick="toggleMethod('online-payment')">
                            <h4><i class="fas fa-credit-card"></i> Online Payment</h4>
                            <span class="toggle-icon">+</span>
                        </div>
                        <div class="method-content" id="online-payment">
                            <p>Pay securely online with your credit/debit card or bank account.</p>
                            <div class="action-buttons">
                                <form action="<?php echo URLROOT; ?>/client/processFinalOnlinePayment" method="post">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                    <input type="hidden" name="payment_phase" value="final_payment">
                                    <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">
                                    <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cash Payment Method -->
                    <div class="payment-method">
                        <div class="method-header" onclick="toggleMethod('cash-payment')">
                            <h4><i class="fas fa-money-bill-wave"></i> Cash Payment</h4>
                            <span class="toggle-icon">+</span>
                        </div>
                        <div class="method-content" id="cash-payment">
                            <?php if (
                                isset($data['payment']) && is_object($data['payment']) &&
                                $data['payment']->payment_method == 'cash' &&
                                !$data['payment']->payment_status
                            ): ?>
                                <div class="cash-payment-info">
                                    <h4>Payment Details</h4>
                                    <p>You have chosen to pay by cash. Please visit our office with the amount below:</p>
                                    <div class="amount-to-pay">Rs. <?php echo number_format($data['payment']->amount, 2); ?></div>

                                    <div class="office-details">
                                        <h5>Office Details</h5>
                                        <p><i class="fas fa-map-marker-alt"></i> Address: <?php echo address; ?></p>
                                        <p><i class="fas fa-clock"></i> Business Hours: Monday to Friday, 9:00 AM - 5:00 PM</p>
                                        <p><i class="fas fa-phone"></i> Phone: 011-2345678</p>
                                    </div>

                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-secondary" onclick="changePaymentMethod()">
                                            <i class="fas fa-exchange-alt"></i> Change Payment Method
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Visit our office and pay in cash.</p>
                                <div class="office-details">
                                    <h5>Office Details</h5>
                                    <p><i class="fas fa-map-marker-alt"></i> Address: <?php echo address; ?></p>
                                    <p><i class="fas fa-clock"></i> Business Hours: Monday to Friday, 9:00 AM - 5:00 PM</p>
                                    <p><i class="fas fa-phone"></i> Phone: 011-2345678</p>
                                </div>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-primary" onclick="confirmCashPayment()">
                                        <i class="fas fa-money-bill-wave"></i> I'll Pay in Cash
                                    </button>
                                </div>

                                <!-- Hidden form for cash payment submission -->
                                <form id="cashPaymentForm" action="<?php echo URLROOT; ?>/client/recordFinalCashPayment" method="post" style="display: none;">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                    <input type="hidden" name="payment_phase" value="final_payment">
                                    <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>
        <?php else: ?>
            <div class="payment-success">
                <div class="success-icon">✓</div>
                <h3>Final Payment Complete</h3>
                <p>Your final payment has been received. Thank you!</p>
                <p>Amount Paid: Rs. <?php echo number_format($data['payment']->amount, 2); ?></p>
                <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $data['payment']->payment_method)); ?></p>
                <p>Payment Date: <?php echo date('F j, Y', strtotime($data['payment']->created_at)); ?></p>
                <div class="project-complete-message">
                    <h4><i class="fas fa-check-circle"></i> Payment Complete - Engineer Approval Phase</h4>
                    <p>Thank you for completing your final payment. Your project has now moved to the Engineer Approval phase.</p>
                    <p>An engineer will review and approve your installation to ensure everything meets quality and safety standards.</p>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Confirm Cash Payment</h3>
            <p>Are you sure you want to pay Rs. <?php echo number_format($data['payment_amount'], 2); ?> in cash at our office?</p>
            <div class="modal-buttons">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="submitCashPayment()">Confirm</button>
            </div>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="closeModal()"></div>
    <script src="<?php echo URLROOT; ?>/js/client/clientFinalPayment.js"></script>
    <script>
        function toggleMethod(methodId) {
            const methodContent = document.getElementById(methodId);
            const allContents = document.querySelectorAll('.method-content');
            const allIcons = document.querySelectorAll('.toggle-icon');

            // Close all other method contents
            allContents.forEach(content => {
                if (content.id !== methodId) {
                    content.style.display = 'none';
                }
            });

            // Reset all toggle icons
            allIcons.forEach(icon => {
                icon.textContent = '+';
            });

            // Toggle the clicked method
            if (methodContent.style.display === 'block') {
                methodContent.style.display = 'none';
                event.currentTarget.querySelector('.toggle-icon').textContent = '+';
            } else {
                methodContent.style.display = 'block';
                event.currentTarget.querySelector('.toggle-icon').textContent = '-';
            }
        }

        // Bank account details display
        const bankSelect = document.getElementById('bank_account');
        const bankDetails = document.getElementById('selectedBankDetails');
        const bankAccounts = <?php echo json_encode(BANK_ACCOUNTS); ?>;

        if (bankSelect) {
            bankSelect.addEventListener('change', function() {
                const selectedIndex = this.value;
                if (selectedIndex !== '') {
                    const bank = bankAccounts[selectedIndex];
                    const amount = document.getElementById('amount').value;
                    bankDetails.innerHTML = `
                <div class="bank-details">
                    <h5>${bank.bank_name} Details</h5>
                    <p><strong>Account Name:</strong> ${bank.account_name}</p>
                    <p><strong>Account Number:</strong> ${bank.account_number}</p>
                    <p><strong>Branch:</strong> ${bank.branch} (${bank.branch_code})</p>
                    <p><strong>Amount to Pay:</strong> Rs. ${parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                </div>
            `;
                } else {
                    bankDetails.innerHTML = '';
                }
            });

            // Modal functions
            function openModal() {
                document.getElementById('confirmationModal').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('confirmationModal').style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
            }

            // Cash payment functions
            function confirmCashPayment() {
                openModal();
            }

            function submitCashPayment() {
                document.getElementById('cashPaymentForm').submit();
                closeModal();
            }

            function changePaymentMethod() {
                if (confirm('Are you sure you want to change your payment method? This will cancel your current payment method selection.')) {
                    window.location.href = `${URLROOT}/client/cancelFinalPayment/<?php echo $data['pre_project_id']; ?>`;
                }
            }
        }
    </script>
    <?php require APPROOT . '/views/client/footer.php'; ?>