<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/clientBankDeposit.css">
</head>

<body data-user-id="<?php echo $_SESSION['user_id']; ?>" data-user-role="customer" data-urlroot="<?php echo URLROOT; ?>">

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <<li>
                <a href="<?php echo URLROOT; ?>/client/chat">
                    <i class='bx bx-message-square-dots'></i>Chat
                    <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
                </a>
            </li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
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

            <div class="container">
                <div class="header">
                    <h1>Bank Deposit Payment</h1>
                    <p>Please upload your deposit slip after making the payment</p>
                </div>

                <div class="card">
                    <h2 class="card-title">Order Summary</h2>
                    <div class="order-summary">
                        <?php
                        $subtotal = $data['order']->price * $data['order']->quantity;
                        $deliveryFee = $data['order']->delivery_option === 'deliver' ? $data['order']->delivery_fee : 0;
                        $discount = $data['order']->discount ?? 0;
                        $total = $subtotal + $deliveryFee - $discount;
                        ?>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>Rs. <?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <?php if ($data['order']->delivery_option === 'deliver'): ?>
                            <div class="summary-row">
                                <span>Delivery Fee</span>
                                <span>Rs. <?php echo number_format($deliveryFee, 2); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="summary-row discount">
                            <span>Discount</span>
                            <span>-Rs. <?php echo number_format($discount, 2); ?></span>
                        </div>
                        <div class="divider"></div>
                        <div class="summary-row total-row">
                            <span>Total</span>
                            <span>Rs. <?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bank-cards">
                    <?php foreach (BANK_ACCOUNTS as $bank): ?>
                        <div class="bank-card">
                            <h3><?php echo $bank['bank_name']; ?></h3>
                            <div class="bank-detail">
                                <span>Account Name:</span> <?php echo $bank['account_name']; ?>
                            </div>
                            <div class="bank-detail">
                                <span>Account Number:</span> <?php echo $bank['account_number']; ?>
                            </div>
                            <div class="bank-detail">
                                <span>Branch :</span> <?php echo $bank['branch']; ?>
                            </div>
                            <div class="bank-detail">
                                <span>Branch Code:</span> <?php echo $bank['branch_code']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="upload-section">
                    <h2>Upload Deposit Slip</h2>
                    <div class="file-drop-area" id="dropArea">
                        <!-- Upload UI elements -->
                        <div class="upload-ui" id="uploadUI">
                            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            <p>Drag and drop your deposit slip here or</p>
                            <label class="choose-file-btn">
                                Choose File
                                <input type="file" id="fileInput" hidden accept="image/*">
                            </label>
                            <p>Supported formats: PNG, JPG (Max 5MB)</p>
                        </div>
                        <!-- Preview image container -->
                        <div class="preview-container" id="previewContainer">
                            <img id="dropAreaPreview" alt="Preview">
                            <button class="remove-preview" id="removeFile">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor">
                                    <path d="M18 6L6 18M6 6l12 12" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="orderId" value="<?php echo $data['order']->id; ?>">
                    <button id="submitButton" class="submit-button" disabled>
                        Submit Deposit Slip
                    </button>
                </div>
            </div>

        </main>

    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/client/clientBankDeposit.js"></script>
    <?php require APPROOT . '/views/client/footer.php'; ?>