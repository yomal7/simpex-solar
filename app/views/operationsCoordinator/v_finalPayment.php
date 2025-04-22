<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/finalPayment.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/firstPayment.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="manager profile-picture" class="profile-picture" />

            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject/<?php echo $data['project']->project_id; ?>">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
                <!-- Payment Header -->
                <div class="page-header">
                    <h1>Final Payment Management</h1>
                    <span class="project-id">Project ID: <?php echo $data['project']->project_id; ?></span>
                </div>

                <?php flash('payment_message'); ?>

                <!-- Customer Information Card -->
                <div class="card customer-card">
                    <div class="card-header">
                        <h2>Customer Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">Customer Name</p>
                                <p class="info-value"><?php echo $data['project']->customer_name; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Phone</p>
                                <p class="info-value"><?php echo $data['project']->phone; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Email</p>
                                <p class="info-value"><?php echo $data['project']->email; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Address</p>
                                <p class="info-value"><?php echo $data['project']->address ?? $data['project']->location; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Financial Details -->
                <div class="card finance-card">
                    <div class="card-header">
                        <h2>Project Financial Details</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($data['agreement']): ?>
                            <div class="finance-row">
                                <span class="finance-label">Base Price:</span>
                                <span class="finance-value">Rs. <?php echo number_format($data['agreement']->base_price, 2); ?></span>
                            </div>
                            <div class="finance-row">
                                <span class="finance-label">Service Charge:</span>
                                <span class="finance-value">Rs. <?php echo number_format($data['agreement']->service_charge, 2); ?></span>
                            </div>
                            <div class="finance-row total">
                                <span class="finance-label">Total Project Cost:</span>
                                <span class="finance-value">Rs. <?php echo number_format($data['agreement']->total_price, 2); ?></span>
                            </div>
                            <div class="finance-row">
                                <span class="finance-label">First Payment:</span>
                                <span class="finance-value">Rs. <?php echo number_format($data['first_payment_amount'], 2); ?></span>
                            </div>
                            <div class="finance-row final-payment">
                                <span class="finance-label">Final Payment (Remaining Balance):</span>
                                <span class="finance-value">Rs. <?php echo number_format($data['final_payment_amount'], 2); ?></span>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Agreement data not available</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Status Card -->
                <div class="card payment-card">
                    <div class="card-header">
                        <h2>Final Payment Status</h2>
                        <?php if (isset($data['payment']) && $data['payment']): ?>
                            <span class="status-badge <?php echo $data['payment']->payment_status ? 'completed' : 'pending'; ?>">
                                <?php echo $data['payment']->payment_status ? 'Completed' : 'Pending'; ?>
                            </span>
                        <?php else: ?>
                            <span class="status-badge not-selected">Not Selected</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!isset($data['payment']) || !$data['payment']): ?>
                            <!-- No payment method selected yet -->
                            <div class="payment-message">
                                <i class="material-icons-sharp">info</i>
                                <p>Customer has not selected a payment method yet.</p>
                            </div>
                        <?php elseif (!$data['payment']->payment_status): ?>
                            <!-- Payment method selected but not completed -->
                            <div class="payment-details">
                                <div class="detail-row">
                                    <span class="detail-label">Payment Method:</span>
                                    <span class="detail-value method-badge <?php echo str_replace(' ', '_', $data['payment']->payment_method); ?>">
                                        <?php echo ucfirst($data['payment']->payment_method); ?>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Selected on:</span>
                                    <span class="detail-value"><?php echo date('F j, Y', strtotime($data['payment']->created_at)); ?></span>
                                </div>

                                <?php if ($data['payment']->payment_method == 'cash'): ?>
                                    <!-- Cash payment form -->
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/processFinalPayment" method="post" id="cashPaymentForm">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                        <input type="hidden" name="payment_id" value="<?php echo $data['payment']->id; ?>">

                                        <div class="form-group">
                                            <label for="amount">Payment Amount (Rs.):</label>
                                            <input type="number" id="amount" name="amount"
                                                value="<?php echo $data['final_payment_amount']; ?>"
                                                min="<?php echo $data['final_payment_amount']; ?>"
                                                max="<?php echo $data['final_payment_amount']; ?>"
                                                step="0.01" readonly required>
                                            <div class="amount-note">
                                                <small>This is the final payment amount (remaining balance after first payment)</small>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-primary" onclick="confirmCashPayment()">
                                            Process Payment
                                        </button>
                                    </form>

                                <?php elseif ($data['payment']->payment_method == 'bank deposit'): ?>
                                    <!-- Bank deposit handling -->
                                    <?php if (!isset($data['bank_slip']) || !$data['bank_slip']->slip_file): ?>
                                        <div class="payment-message">
                                            <i class="material-icons-sharp">info</i>
                                            <p>Customer has selected bank deposit but has not uploaded the slip yet.</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="bank-slip-details">
                                            <div class="detail-row">
                                                <span class="detail-label">Slip Status:</span>
                                                <span class="detail-value status-badge <?php echo $data['bank_slip']->status; ?>">
                                                    <?php echo ucfirst($data['bank_slip']->status); ?>
                                                </span>
                                            </div>

                                            <?php if ($data['bank_slip']->status == 'pending'): ?>
                                                <div class="bank-slip-preview">
                                                    <h3>Bank Slip</h3>
                                                    <div class="slip-image-container">
                                                        <img src="<?php echo URLROOT . '/' . $data['bank_slip']->slip_file; ?>" alt="Bank Slip" class="slip-image">
                                                    </div>

                                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/acceptFinalBankSlip" method="post" id="acceptSlipForm">
                                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                                        <input type="hidden" name="payment_id" value="<?php echo $data['payment']->id; ?>">
                                                        <input type="hidden" name="slip_id" value="<?php echo $data['bank_slip']->id; ?>">

                                                        <div class="form-group">
                                                            <label for="amount">Payment Amount (Rs.):</label>
                                                            <input type="number" id="amount" name="amount"
                                                                value="<?php echo $data['payment']->amount; ?>"
                                                                min="<?php echo $data['final_payment_amount']; ?>"
                                                                max="<?php echo $data['final_payment_amount']; ?>"
                                                                step="0.01" readonly required>
                                                        </div>

                                                        <div class="slip-actions">
                                                            <button type="button" class="btn btn-success" onclick="confirmAcceptSlip()">
                                                                Accept Slip
                                                            </button>
                                                            <button type="button" class="btn btn-danger" onclick="showRejectForm()">
                                                                Reject Slip
                                                            </button>
                                                            <a href="<?php echo URLROOT . '/' . $data['bank_slip']->slip_file; ?>"
                                                                download class="btn btn-secondary">
                                                                Download Slip
                                                            </a>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php elseif ($data['bank_slip']->status == 'reject'): ?>
                                                <div class="rejection-details">
                                                    <p class="rejection-reason">
                                                        <strong>Rejection Reason:</strong> <?php echo $data['bank_slip']->reject_reason; ?>
                                                    </p>
                                                    <p class="rejection-note">The customer will need to upload a new slip.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <!-- Payment completed -->
                            <div class="payment-complete">
                                <div class="success-icon">
                                    <i class="material-icons-sharp">check_circle</i>
                                </div>
                                <h3>Final Payment Completed</h3>
                                <div class="payment-info">
                                    <div class="detail-row">
                                        <span class="detail-label">Amount Paid:</span>
                                        <span class="detail-value">Rs. <?php echo number_format($data['payment']->amount, 2); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Payment Method:</span>
                                        <span class="detail-value method-badge <?php echo str_replace(' ', '_', $data['payment']->payment_method); ?>">
                                            <?php echo ucfirst($data['payment']->payment_method); ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Payment Date:</span>
                                        <span class="detail-value"><?php echo date('F j, Y', strtotime($data['payment']->updated_at)); ?></span>
                                    </div>

                                    <?php if ($data['payment']->payment_method == 'bank deposit' && isset($data['bank_slip'])): ?>
                                        <div class="detail-row">
                                            <span class="detail-label">Bank Slip:</span>
                                            <a href="<?php echo URLROOT . '/' . $data['bank_slip']->slip_file; ?>"
                                                download class="btn btn-sm btn-secondary">
                                                Download Slip
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="project-complete">
                                    <h4><i class="material-icons-sharp">celebration</i> Project Completed!</h4>
                                    <p>This project has been marked as completed with all payments received.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRejectModal()">&times;</span>
            <h2>Reject Bank Slip</h2>
            <form action="<?php echo URLROOT; ?>/operationsCoordinator/rejectFinalBankSlip" method="post" id="rejectSlipForm">
                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                <input type="hidden" name="slip_id" value="<?php echo isset($data['bank_slip']) ? $data['bank_slip']->id : ''; ?>">

                <div class="form-group">
                    <label for="reject_reason">Reason for Rejection:</label>
                    <textarea id="reject_reason" name="reject_reason" rows="4" required
                        placeholder="Please provide a reason for rejecting this slip..."></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeConfirmModal()">&times;</span>
            <h2>Confirm Final Payment</h2>
            <div class="confirm-details">
                <p>You are about to process the final payment with the following details:</p>
                <div class="detail-row">
                    <span class="detail-label">Customer:</span>
                    <span class="detail-value" id="confirmCustomerName"><?php echo $data['project']->customer_name; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value" id="confirmAmount">Rs. <?php echo number_format($data['final_payment_amount'], 2); ?></span>
                </div>
                <div class="note-box">
                    <p><strong>Note:</strong> Processing this payment will mark the project as completed.</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="finalConfirmButton">Confirm Payment</button>
            </div>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
        // Show reject modal
        function showRejectForm() {
            document.getElementById('rejectModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        // Close reject modal
        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Show confirmation modal for cash payment
        function confirmCashPayment() {
            document.getElementById('confirmModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            // Set up final confirm button to submit the form
            document.getElementById('finalConfirmButton').onclick = function() {
                document.getElementById('cashPaymentForm').submit();
            };
        }

        // Show confirmation modal for accepting bank slip
        function confirmAcceptSlip() {
            document.getElementById('confirmModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            // Set up final confirm button to submit the form
            document.getElementById('finalConfirmButton').onclick = function() {
                document.getElementById('acceptSlipForm').submit();
            };
        }

        // Close confirmation modal
        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>