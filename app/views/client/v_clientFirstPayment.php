<?php require APPROOT.'/views/client/header.php';?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/firstPayment.css">
</head>

<body>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            
            <li ><a href="#" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
            <li class="active"><a href="Dashboard.html"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li ><a href="Project.html"><i class='bx bx-analyse'></i>Project</a></li>
            <li><a href="#"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
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

            <div class="status-bar">
                <h2>Payment Status</h2>
                <div class="status-indicator" id="statusIndicator">Pending</div>
            </div>

            <div class="payment-container">
                <div class="amount-display">
                    <h3>Amount Due (25% of Total Project Cost)</h3>
                    <div class="amount">$2,500.00</div>
                </div>

                <div class="payment-options">
                    <div class="payment-option" onclick="showOnlinePayment()">
                        <i class="material-icons">credit_card</i>
                        <h3>Online Banking</h3>
                        <p>Pay securely using your bank account</p>
                    </div>

                    <div class="payment-option" onclick="showUploadSlip()">
                        <i class="material-icons">upload_file</i>
                        <h3>Upload Payment Slip</h3>
                        <p>Upload your payment confirmation</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Online Payment Popup -->
        <div class="popup" id="onlinePaymentPopup">
            <h2>Online Payment</h2>
            <form id="paymentForm" onsubmit="handlePaymentSubmit(event)">
                <div class="online-payment-container">
                    <div class="row">
                        <!-- Billing Address Section -->
                        <div class="col">
                            <h3 class="title">Billing Address</h3>
                            <div class="inputBox">
                                <label for="name">Full Name:</label>
                                <input type="text" id="name" placeholder="Enter your full name" required>
                            </div>
                            <div class="inputBox">
                                <label for="email">Email:</label>
                                <input type="email" id="email" placeholder="Enter email address" required>
                            </div>
                            <div class="inputBox">
                                <label for="address">Address:</label>
                                <input type="text" id="address" placeholder="Enter address" required>
                            </div>
                            <div class="inputBox">
                                <label for="city">City:</label>
                                <input type="text" id="city" placeholder="Enter city" required>
                            </div>
                            <div class="flex">
                                <div class="inputBox">
                                    <label for="state">State:</label>
                                    <input type="text" id="state" placeholder="Enter state" required>
                                </div>
                                <div class="inputBox">
                                    <label for="zip">Zip Code:</label>
                                    <input type="number" id="zip" placeholder="123456" required>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Details Section -->
                        <div class="col">
                            <h3 class="title">Payment</h3>
                            <div class="inputBox">
                                <label for="cardName">Name On Card:</label>
                                <input type="text" id="cardName" placeholder="Enter card name" required>
                            </div>
                            <div class="inputBox">
                                <label for="cardNum">Credit Card Number:</label>
                                <input type="text" id="cardNum" placeholder="1111 2222 3333 4444" maxlength="19" required>
                            </div>
                            <div class="inputBox">
                                <label for="expMonth">Exp Month:</label>
                                <select id="expMonth" required>
                                    <option value="">Choose month</option>
                                    <option value="">Choose month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="flex">
                                <div class="inputBox">
                                    <label for="expYear">Exp Year:</label>
                                    <select id="expYear" required>
                                        <option value="">Choose Year</option>
                                        <option value="">Choose Year</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                    </select>
                                </div>
                                <div class="inputBox">
                                    <label for="cvv">CVV:</label>
                                    <input type="number" id="cvv" placeholder="123" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Proceed to Checkout" class="submit_btn">
                    <button type="button" class="payment-btn btn-secondary" onclick="closePopup('onlinePaymentPopup')">Cancel</button>
                </div>
            </form>
    </div>


    <!-- Upload Slip Popup -->
    <div class="popup" id="uploadSlipPopup">
        <h2>Upload Payment Slip</h2>
        <div class="upload-area" onclick="triggerFileInput()">
            <i class="material-icons">cloud_upload</i>
            <p>Click or drag to upload payment slip</p>
        </div>
        <input type="file" id="slipInput" accept="image/*" style="display: none" onchange="handleFileUpload(event)">
        <button class="payment-btn btn-secondary" onclick="closePopup('uploadSlipPopup')">Cancel</button>
    </div>

    <!-- Success Popup -->
    <div class="popup success-popup-class" id="successPopup">
        <div class="success-circle">
            <i class="material-icons">check</i>
        </div>
        <h2>Payment Successful!</h2>
        <p style="margin-top: 10px;">Your payment has been processed successfully.</p>
        <button class="payment-btn btn-primary success-popup-btn" onclick="closePopup('successPopup')">Close</button>
    </div>

    <div class="overlay" id="overlay"></div>
    <div id="toastContainer" class="toast-container"></div>

    <script src="<?php echo URLROOT; ?>/js/client/firstPayment.js"></script>
<?php require APPROOT.'/views/packages/footer.php';?>
