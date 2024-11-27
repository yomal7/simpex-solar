<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageAproject.css">
</head>
<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />

            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject"  class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">

                <div class="customer-info">
                    <div class="customer-info-header">
                        <h2>Customer Information</h2>
                        <span class="phase-status status-pending">Agreement Phase</span>
                    </div>
                    <div class="customer-info-grid">
                        <div class="info-item">
                            <div class="info-label">Customer Name</div>
                            <div class="info-value">S.D Kumara</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">07123345678</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">kumara@gmail.com</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Address</div>
                            <div class="info-value">123 Main St, City, Country</div>
                        </div>
                    </div>
                </div>


        <!-- Agreement Phase Box -->
        <div class="phase-box">
            <div class="phase-header" id="agreementHeader">
                <div class="phase-title">
                    <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    Agreement Phase
                </div>
                <div class="phase-status status-completed">Completed</div>
            </div>
            <div class="phase-content" id="agreementContent">
                <div class="phase-content-inner">
                    <form id="agreementForm">
                        <div class="form-group">
                            <label for="title">Agreement Title</label>
                            <input type="text" id="title" class="form-control" placeholder="Enter agreement title">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control" rows="4" placeholder="Enter agreement description"></textarea>
                        </div>

                        <!-- Items Table -->
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th style="width: 120px;">Quantity</th>
                                    <th style="width: 150px;">Unit Price ($)</th>
                                    <th style="width: 150px;">Total ($)</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be added here -->
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-primary" id="addItemBtn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"></path>
                            </svg>
                            Add Item
                        </button>

                        <!-- Summary Section -->
                        <div class="summary-section">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="summary-row">
                                <div class="service-charge-row">
                                    <span>Service Charge ($):</span>
                                    <input type="number" id="serviceCharge" class="form-control" value="0" min="0" step="0.01" onchange="updateTotal()">
                                </div>
                                <span id="serviceChargeAmount">$0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Total Amount:</span>
                                <span id="totalAmount">$0.00</span>
                            </div>
                        </div>

                        <div class="customer-notes">
                            <h4>Customer Notes</h4>
                            <p id="customerNotes">Please review the agreement and make necessary changes.</p>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Submit Agreement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- site visit phase box  -->

        <div class="phase-box">
            <div class="phase-header" id="siteVisitHeader">
                <div class="phase-title">
                    <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Site Visit Phase
                </div>
                <div class="phase-status status-pending" id="visitPhaseStatus">Pending</div>
            </div>
            <div class="phase-content" id="siteVisitContent">
                <div class="phase-content-inner">
                    <form id="siteVisitForm">
                        <div class="datetime-grid">
                            <div class="form-group">
                                <label for="visitDate">Proposed Visit Date</label>
                                <input type="date" id="visitDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="visitTime">Proposed Visit Time</label>
                                <input type="time" id="visitTime" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="visitNotes">Additional Notes</label>
                            <textarea id="visitNotes" rows="3" class="form-control" 
                                placeholder="Add any additional notes or instructions..."></textarea>
                        </div>

                        <div class="visit-status proposed" id="scheduleStatus" style="display: none;">
                            <h4>Current Schedule Status</h4>
                            <p id="statusMessage">No visit scheduled yet</p>
                            <p id="scheduledDateTime"></p>
                        </div>

                        <div class="customer-response" id="customerResponse" style="display: none;">
                            <h4>Customer Response</h4>
                            <p id="customerMessage"></p>
                            <p id="customerProposedDateTime"></p>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary" id="proposeBtn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Propose Visit Schedule
                            </button>
                            <button type="button" class="btn btn-outline" id="completeBtn" disabled>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                                Mark as Completed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- first payment phase box  -->
        <div class="phase-box">
        <div class="phase-header" id="paymentHeader">
            <div class="phase-title">
                <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                First Payment Confirmation Phase
            </div>
            <div class="phase-status status-pending" id="paymentStatus">Pending Verification</div>
        </div>

        <div class="phase-content" id="paymentContent">
            <div class="phase-content-inner">
                <!-- Payment Summary -->
                <div class="payment-summary">
                    <h3>Payment Details</h3>
                    <div class="summary-row">
                        <span> 25 % of Agreement Amount:</span>
                        <span>Rs 2500000.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Service Charge:</span>
                        <span>Rs 50 000.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Amount:</span>
                        <span>Rs 300 000.00</span>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="payment-method selected">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Payment Slip Uploaded
                </div>

                <!-- Payment Slip Preview -->
                <div class="payment-slip" id="paymentSlip">
                    <img src="/api/placeholder/400/300" alt="Payment Slip" id="slipImage">
                    <div class="slip-overlay">
                        <button class="btn btn-primary" onclick="openSlipModal()">
                            View Full Image
                        </button>
                    </div>
                </div>

                <!-- Payment Verification Actions -->
                <div class="payment-actions">
                    <button class="btn btn-approve" onclick="approvePayment()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                        Verify Payment
                    </button>
                    <button class="btn btn-reject" onclick="showRejectionForm()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                        Reject Payment
                    </button>
                </div>

                <!-- Rejection Form -->
                <div class="rejection-form" id="rejectionForm">
                    <div class="form-group">
                        <label for="rejectionReason">Reason for Rejection</label>
                        <textarea id="rejectionReason" class="form-control" rows="3" 
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-reject" onclick="rejectPayment()">Confirm Rejection</button>
                        <button class="btn btn-outline" onclick="hideRejectionForm()">Cancel</button>
                    </div>
                </div>

                <!-- Payment Status Message -->
                <div class="payment-status status-pending" id="statusMessage">
                    <h4>Payment Status</h4>
                    <p>Waiting for payment verification</p>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Modal for Full Image View -->
    <div class="modal" id="slipModal">
        <div class="modal-content">
            <img src="/api/placeholder/800/600" alt="Payment Slip Full View">
        </div>
    </div>

    <!-- installation phase box -->

    <div class="phase-box">
        <div class="phase-header" id="installationHeader">
            <div class="phase-title">
                <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                Installation Phase
            </div>
            <div class="phase-status status-pending" id="installationPhaseStatus">Pending</div>
            
        </div>
        <div class="phase-content" id="installationContent">
            <div class="phase-content-inner">
                <form id="installationForm">
                    <div class="datetime-grid">
                        <div class="form-group">
                            <label for="installDate">Installation Date</label>
                            <input type="date" id="installDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="installTime">Installation Time</label>
                            <input type="time" id="installTime" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="installNotes">Installation Notes</label>
                        <textarea id="installNotes" rows="3" class="form-control" 
                            placeholder="Add any requirements for installation..."></textarea>
                    </div>

                    <div class="visit-status proposed" id="scheduleStatus" style="display: none;">
                        <h4>Installation Schedule Status</h4>
                        <p id="statusMessage">No installation scheduled yet</p>
                        <p id="scheduledDateTime"></p>
                    </div>

                    <div class="customer-response" id="customerResponse" style="display: none;">
                        <h4>Customer Response</h4>
                        <p id="customerMessage"></p>
                        <p id="customerProposedDateTime"></p>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary" id="proposeBtn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Schedule Installation
                        </button>
                        <button type="button" class="btn btn-outline" id="completeBtn" disabled>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            Verify Installation Complete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- second payment phase -->

    <div class="phase-box">
        <div class="phase-header" id="finalPaymentHeader">
            <div class="phase-title">
                <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Final Payment Confirmation Phase
            </div>
            <div class="phase-status status-pending" id="finalPaymentStatus">Pending Verification</div>
        </div>

        <div class="phase-content" id="finalPaymentContent">
            <div class="phase-content-inner">
                <!-- Payment Summary -->
                <div class="payment-summary">
                    <h3>Payment Details</h3>
                    <div class="summary-row">
                        <span>75% of Agreement Amount:</span>
                        <span>Rs 7,500,000.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Service Charge:</span>
                        <span>Rs 150,000.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Amount:</span>
                        <span>Rs 7,650,000.00</span>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="payment-method selected">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Payment Slip Uploaded
                </div>

                <!-- Payment Slip Preview -->
                <div class="payment-slip" id="finalPaymentSlip">
                    <img src="/api/placeholder/400/300" alt="Final Payment Slip" id="finalSlipImage">
                    <div class="slip-overlay">
                        <button class="btn btn-primary" onclick="openFinalSlipModal()">
                            View Full Image
                        </button>
                    </div>
                </div>

                <!-- Payment Verification Actions -->
                <div class="payment-actions">
                    <button class="btn btn-approve" onclick="approveFinalPayment()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                        Verify Final Payment
                    </button>
                    <button class="btn btn-reject" onclick="showFinalRejectionForm()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                        Reject Payment
                    </button>
                </div>

                <!-- Rejection Form -->
                <div class="rejection-form" id="finalRejectionForm">
                    <div class="form-group">
                        <label for="finalRejectionReason">Reason for Rejection</label>
                        <textarea id="finalRejectionReason" class="form-control" rows="3" 
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-reject" onclick="rejectFinalPayment()">Confirm Rejection</button>
                        <button class="btn btn-outline" onclick="hideFinalRejectionForm()">Cancel</button>
                    </div>
                </div>

                <!-- Payment Status Message -->
                <div class="payment-status status-pending" id="finalStatusMessage">
                    <h4>Payment Status</h4>
                    <p>Waiting for final payment verification</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Full Image View -->
    <div class="modal" id="finalSlipModal">
        <div class="modal-content">
            <img src="/api/placeholder/800/600" alt="Final Payment Slip Full View">
        </div>
    </div>

    <!-- Engieers approval phase -->

    <div class="phase-box">
        <div class="phase-header" id="engineerHeader">
            <div class="phase-title">
                <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
                Engineer Approval & Grid Connection Phase
            </div>
            <div class="phase-status status-pending" id="engineerPhaseStatus">Pending</div>
        </div>
        <div class="phase-content" id="engineerContent">
            <div class="phase-content-inner">
                <form id="engineerForm">
                    <div class="datetime-grid">
                        <div class="form-group">
                            <label for="inspectionDate">Engineer Inspection Date</label>
                            <input type="date" id="inspectionDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="inspectionTime">Engineer Inspection Time</label>
                            <input type="time" id="inspectionTime" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="engineerNotes">Inspection Requirements</label>
                        <textarea id="engineerNotes" rows="3" class="form-control" 
                            placeholder="Add any requirements or special instructions for the inspection..."></textarea>
                    </div>

                    <div class="visit-status proposed" id="scheduleStatus" style="display: none;">
                        <h4>Inspection Schedule Status</h4>
                        <p id="statusMessage">No inspection scheduled yet</p>
                        <p id="scheduledDateTime"></p>
                    </div>

                    <div class="customer-response" id="engineerResponse" style="display: none;">
                        <h4>Engineer Response</h4>
                        <p id="engineerMessage"></p>
                        <p id="engineerConfirmation"></p>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary" id="scheduleBtn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Schedule Engineer Inspection
                        </button>
                        <button type="button" class="btn btn-outline" id="approvalBtn" disabled>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            Verify Engineer Approval & Grid Connection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/quationPhase.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/siteVisit.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/manageAproject.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>