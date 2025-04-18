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
                <div class="status-indicator <?php echo (isset($data['payment']) && is_object($data['payment']) && $data['payment']->payment_status) ? 'paid' : 'pending'; ?>">
                    <?php echo (isset($data['payment']) && is_object($data['payment']) && $data['payment']->payment_status) ? 'Paid' : 'Pending'; ?>
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
                    <h3>First Payment (25% of Total Project Cost)</h3>
                    <div class="amount">Rs. <?php echo number_format($data['payment_amount'], 2); ?></div>
                    <p class="payment-note">This initial payment allows us to begin your solar installation project.</p>
                </div>

                <?php if (!isset($data['payment']) || !$data['payment']->payment_status): ?>
                    <!-- Payment Methods Accordion -->
                    <div class="payment-methods">
                        <h3>Select Payment Method</h3>

                        <!-- Bank Deposit Method -->
                        <div class="payment-method">
                            <div class="method-header" onclick="toggleMethod('bank-deposit')">
                                <h4>Bank Deposit</h4>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="method-content" id="bank-deposit">
                                <p>Make a bank deposit and upload your payment slip.</p>

                                <?php if (isset($data['bank_slip']) && $data['bank_slip']): ?>
                                    <div class="slip-status">
                                        <p>You have uploaded a slip. Status:
                                            <?php
                                            switch ($data['bank_slip']->status) {
                                                case 'pending':
                                                    echo 'Pending';
                                                    break;
                                                case 'approved':
                                                    echo 'Approved';
                                                    break;
                                                case 'rejected':
                                                    echo 'Rejected';
                                                    break;
                                                default:
                                                    echo ucfirst($data['bank_slip']->status);
                                            }
                                            ?>
                                        </p>
                                        <?php if ($data['bank_slip']->status == 'rejected' && !empty($data['bank_slip']->reject_reason)): ?>
                                            <p class="rejection-reason">Reason: <?php echo $data['bank_slip']->reject_reason; ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <h4>Select Bank Account</h4>
                                <form action="<?php echo URLROOT; ?>/client/generateBankSlip" method="post" id="bankForm">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                    <input type="hidden" name="payment_phase" value="first_payment">
                                    <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">

                                    <div class="form-group">
                                        <select name="bank_account" id="bank_account" required>
                                            <option value="">-- Select Bank --</option>
                                            <?php foreach (BANK_ACCOUNTS as $index => $bank): ?>
                                                <option value="<?php echo $index; ?>"><?php echo $bank['bank_name']; ?> - <?php echo $bank['branch']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="selected-bank-details" id="selectedBankDetails">
                                        <!-- Bank details will be shown here -->
                                    </div>

                                    <div class="action-buttons">
                                        <button type="submit" class="btn btn-secondary">Download Payment Slip</button>
                                    </div>
                                </form>

                                <?php if (!isset($data['bank_slip']) || $data['bank_slip']->status == 'rejected'): ?>
                                    <form action="<?php echo URLROOT; ?>/client/uploadBankSlip" method="post" enctype="multipart/form-data" class="upload-form">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                        <input type="hidden" name="payment_phase" value="first_payment">
                                        <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">
                                        <div class="form-group">
                                            <label for="payment_slip">Upload Signed Payment Slip:</label>
                                            <input type="file" id="payment_slip" name="payment_slip" accept="image/*,.pdf" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Slip</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Online Payment Method -->
                        <div class="payment-method">
                            <div class="method-header" onclick="toggleMethod('online-payment')">
                                <h4>Online Payment</h4>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="method-content" id="online-payment">
                                <p>Pay securely online with your credit/debit card or bank account.</p>
                                <div class="action-buttons">
                                    <form action="<?php echo URLROOT; ?>/client/processOnlinePayment" method="post">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                        <input type="hidden" name="payment_phase" value="first_payment">
                                        <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">
                                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payment Method -->
                        <div class="payment-method">
                            <div class="method-header" onclick="toggleMethod('cash-payment')">
                                <h4>Cash Payment</h4>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="method-content" id="cash-payment">
                                <p>Visit our office and pay in cash.</p>
                                <div class="office-details">
                                    <h5>Office Details</h5>
                                    <p>Address: <?php echo address; ?></p>
                                    <p>Business Hours: Monday to Friday, 9:00 AM - 5:00 PM</p>
                                    <p>Phone: 011-2345678</p>
                                </div>
                                <div class="action-buttons">
                                    <form action="<?php echo URLROOT; ?>/client/recordCashPayment" method="post">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                        <input type="hidden" name="payment_phase" value="first_payment">
                                        <input type="hidden" name="amount" value="<?php echo $data['payment_amount']; ?>">
                                        <button type="submit" class="btn btn-primary">I'll Pay in Cash</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="payment-success">
                        <div class="success-icon">✓</div>
                        <h3>Payment Complete</h3>
                        <p>Your payment has been received. Thank you!</p>
                        <p>Amount Paid: Rs. <?php echo number_format($data['payment']->amount, 2); ?></p>
                        <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $data['payment']->payment_method)); ?></p>
                        <p>Payment Date: <?php echo date('F j, Y', strtotime($data['payment']->created_at)); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
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
                    bankDetails.innerHTML = `
                        <div class="bank-details">
                            <h5>${bank.bank_name} Details</h5>
                            <p>Account Name: ${bank.account_name}</p>
                            <p>Account Number: ${bank.account_number}</p>
                            <p>Branch: ${bank.branch} (${bank.branch_code})</p>
                            <p>Amount: Rs. <?php echo number_format($data['payment_amount'], 2); ?></p>
                        </div>
                    `;
                } else {
                    bankDetails.innerHTML = '';
                }
            });
        }
    </script>
        <script src="<?php echo URLROOT; ?>/js/client/clientFirstPayment.js"></script>
    <?php require APPROOT . '/views/client/footer.php'; ?>