<?php require APPROOT . '/views/blog/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/purchaseRequest.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <div class="container">
        <div class="request-header">
            <h1>Purchase Request Submission</h1>
            <p>Please review your order and provide additional information for approval</p>
        </div>

        <div class="request-form-container">
            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-step completed">
                    <div class="step-number">1</div>
                    <div class="step-label">Products</div>
                </div>
                <div class="progress-step completed">
                    <div class="step-number">2</div>
                    <div class="step-label">Collection</div>
                </div>
                <div class="progress-step active">
                    <div class="step-number">3</div>
                    <div class="step-label">Request</div>
                </div>
            </div>

            <form class="form-section" id="purchaseRequestForm" action="<?php echo URLROOT; ?>/shop/submitPurchaseRequest" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                <input type="hidden" name="delivery_option" value="<?php echo $data['delivery_option']; ?>">
                <input type="hidden" name="quantity" value="<?php echo $data['quantity']; ?>">

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <ul class="product-list">
                        <li class="product-item">
                            <div class="product-info">
                                <img src="<?php echo URLROOT . '/public/uploads/store/' . $data['product']->image1; ?>"
                                    alt="<?php echo $data['product']->name; ?>" class="product-image">
                                <div class="product-details">
                                    <h4><?php echo htmlspecialchars($data['product']->name); ?></h4>
                                    <p>Quantity: <?php echo $data['quantity']; ?></p>
                                </div>
                            </div>
                            <div class="product-price">Rs. <?php echo number_format($data['product']->price, 2); ?></div>
                        </li>
                    </ul>
                    <div class="total-section">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>Rs. <?php echo number_format($data['subtotal'], 2); ?></span>
                        </div>
                        <?php if ($data['delivery_option'] === 'deliver'): ?>
                            <div class="total-row">
                                <span>Delivery Fee</span>
                                <span>Rs. <?php echo number_format($data['delivery_fee'], 2); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="total-row final">
                            <span>Total</span>
                            <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Collection Information -->
                <div class="collection-info">
                    <h3>Collection Method</h3>
                    <div class="info-grid">
                        <?php if ($data['delivery_option'] === 'deliver'): ?>
                            <div class="info-item">
                                <i class="fas fa-truck"></i>
                                <div>
                                    <strong>Delivery</strong>
                                    <p>123 Solar Street, Green City, 12345</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="info-item">
                                <i class="fas fa-warehouse"></i>
                                <div>
                                    <strong>Pick Up</strong>
                                    <p><?php echo address ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Preferred Date</strong>
                                <p><?php echo date('F d, Y', strtotime('+7 days')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="form-section-title">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name">Full Name*</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $_SESSION['user_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number*</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section-title">
                    <h3><i class="fas fa-map-marker-alt"></i> Address Details</h3>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="street_address">Street Address*</label>
                        <input type="text" class="form-control" id="street_address" name="street_address" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City*</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>

                    <div class="form-group">
                        <label for="province">Province*</label>
                        <input type="text" class="form-control" id="province" name="province" required>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Postal Code*</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="address_notes">Address Notes (Optional)</label>
                        <textarea class="form-control" id="address_notes" name="address_notes" rows="2"
                            placeholder="Provide any additional details about your address (e.g., landmarks, access instructions)"></textarea>
                    </div>
                </div>

                <!-- Installation Information -->



                <!-- Terms and Conditions -->
                <div class="form-section-title">
                    <h3><i class="fas fa-file-contract"></i> Terms and Conditions</h3>
                </div>
                <div class="terms-section">
                    <div class="form-group">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="termsAgree" name="termsAgree" required>
                            <label for="termsAgree">I agree to the terms and conditions of purchase*</label>
                        </div>
                        <small class="form-text">By submitting this request, you agree to our terms of service and privacy policy</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="action-button back-button" onclick="history.back()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="action-button submit-button">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script src="<?php echo URLROOT; ?>/js/shop/purchaseRequest.js"></script>
    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/shop/footer.php'; ?>