<?php require APPROOT.'/views/blog/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/navbarfooter.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/shop/purchaseRequest.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
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

            <form class="form-section" id="purchaseRequestForm">

                            <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <ul class="product-list">
                        <li class="product-item">
                            <div class="product-info">
                                <img src="/api/placeholder/60/60" alt="Solar Panel" class="product-image">
                                <div class="product-details">
                                    <h4>Premium Solar Panel 400W</h4>
                                    <p>Quantity: 2</p>
                                </div>
                            </div>
                            <div class="product-price">$599.98</div>
                        </li>
                        <!-- Add more products as needed -->
                    </ul>
                    <div class="total-section">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>$599.98</span>
                        </div>
                        <div class="total-row">
                            <span>Delivery Fee</span>
                            <span>$50.00</span>
                        </div>
                        <div class="total-row final">
                            <span>Total</span>
                            <span>$649.98</span>
                        </div>
                    </div>
                </div>

                <!-- Collection Information -->
                <div class="collection-info">
                    <h3>Collection Method</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Delivery</strong>
                                <p>123 Solar Street, Green City, 12345</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Preferred Date</strong>
                                <p>September 15, 2024</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section-title">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fullName">Full Name*</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $_SESSION['user_name']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address*</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number*</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                        <small class="form-text">Please provide a number where we can reach you</small>
                    </div>

                </div>

                <!-- Address Information -->
                <div class="form-section-title">
                    <h3><i class="fas fa-map-marker-alt"></i> Address Details</h3>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="streetAddress">Street Address*</label>
                        <input type="text" class="form-control" id="streetAddress" name="streetAddress" placeholder="Enter your street address" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City*</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province*</label>
                        <input type="text" class="form-control" id="state" name="state" required>
                    </div>

                    <div class="form-group">
                        <label for="postalCode">Postal Code*</label>
                        <input type="text" class="form-control" id="postalCode" name="postalCode" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="addressNotes">Address Notes (Optional)</label>
                        <textarea class="form-control" id="addressNotes" name="addressNotes" rows="2" 
                                placeholder="Provide any additional details about your address (e.g., landmarks, access instructions)"></textarea>
                    </div>
                </div>

                <!-- Installation Information -->
            
                <!-- Additional Requirements -->
                <div class="form-section-title">
                    <h3><i class="fas fa-clipboard-list"></i> Additional Requirements</h3>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="timeline">Expected Timeline*</label>
                        <select class="form-control" id="timeline" name="timeline" required>
                            <option value="">Select timeline</option>
                            <option value="immediate">Immediate</option>
                            <option value="1month">Within 1 Month</option>
                            <option value="3months">Within 3 Months</option>
                            <option value="flexible">Flexible</option>
                        </select>
                    </div>

                </div>

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

    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>

<script src="<?php echo URLROOT; ?>/js/shop/purchaseRequest.js"></script>
<?php require APPROOT.'/views/shop/footer.php';?>