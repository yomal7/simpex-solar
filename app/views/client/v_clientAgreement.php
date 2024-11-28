<?php require APPROOT.'/views/client/header.php';?>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/agreement.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

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
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
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
            <div class="card">
                <div class="header-text">
                    <h1>Project Agreement</h1>
                    <div class="status-badge">Pending Review</div>
                </div>
                
                <div class="pdf-container" id="pdfContainer">
                    <div class="canvas-container" id="canvasContainer"></div>
                </div>

                <div class="button-group">
                    <button class="btn btn-secondary" onclick="downloadPDF()">
                        <i>📥</i> Download PDF
                    </button>
                    <button class="btn btn-primary" onclick="openApprovePopup()">
                        <i>✓</i> Approve Quotation
                    </button>
                    <button class="btn btn-secondary" onclick="openSubmitAgainPopup()">
                        <i>↺</i> Submit Again
                    </button>
                    <button class="btn btn-danger" onclick="openCancelPopup()">
                        <i>✕</i> Cancel Equation
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Existing popups remain the same -->
        <div class="popup" id="approvePopup">
            <h2 >Sign Agreement</h2>
            <p style="padding: 20px;">By clicking on this document, you agree to the terms and conditions outlined within the Project Agreement, 
                acknowledging that you have read and understood all provisions related to the project's scope, timelines, costs, 
                and responsibilities</p>
            <div class="signature-box" id="signatureBox">
                <p>Click here to upload signature</p>
                <input type="file" id="signatureInput" accept="image/*" style="display: none">
            </div>
            <button class="btn btn-primary" onclick="submitSignature()">Submit</button>
            <button class="btn btn-secondary" onclick="closePopup('approvePopup')">Cancel</button>
        </div>

        <div class="popup" id="submitAgainPopup">
            <h2>Submit Review</h2>
            <textarea class="comment-box" placeholder="Enter your comments for changes..."></textarea>
            <button class="btn btn-primary" onclick="submitReview()">Submit</button>
            <button class="btn btn-secondary" onclick="closePopup('submitAgainPopup')">Cancel</button>
        </div>

        <div class="popup" id="cancelPopup">
            <h2>Warning!</h2>
            <p>Are you sure you want to cancel this equation? This action cannot be undone.</p>
            <button class="btn btn-danger" onclick="confirmCancel()">Yes, Cancel</button>
            <button class="btn btn-secondary" onclick="closePopup('cancelPopup')">No, Keep</button>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/client/agreement.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>