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
                
                <div class="agreement-details">
                    <input type="hidden" name="agreement_id" value="<?php echo $data['agreement']->agreement_id; ?>">
                    <input type="hidden" name="pre_project_id" value="<?php echo $data['agreement']->pre_project_id; ?>">
                    
                    <!-- System Details -->
                    <div class="details-section">
                        <h3>System Details</h3>
                        <div class="detail-row">
                            <span>System Capacity:</span>
                            <span><?php echo $data['agreement']->system_capacity; ?> kW</span>
                        </div>
                        <div class="detail-row">
                            <span>Estimated Generation:</span>
                            <span><?php echo $data['agreement']->estimated_generation; ?> kWh/year</span>
                        </div>
                    </div>

                    <!-- Equipment List -->
                    <div class="details-section">
                        <h3>Equipment List</h3>
                        <?php foreach($data['equipment'] as $item): ?>
                            <div class="equipment-row">
                                <span><?php echo $item->item_name; ?></span>
                                <span><?php echo $item->quantity; ?> units</span>
                                <span>Rs. <?php echo number_format($item->total_price, 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pricing Details -->
                    <div class="details-section">
                        <h3>Pricing Details</h3>
                        <div class="detail-row">
                            <span>Base Price:</span>
                            <span>Rs. <?php echo number_format($data['agreement']->base_price, 2); ?></span>
                        </div>
                        <div class="detail-row">
                            <span>Service Charge:</span>
                            <span>Rs. <?php echo number_format($data['agreement']->service_charge, 2); ?></span>
                        </div>

                        <div class="detail-row total">
                            <span>Total Price:</span>
                            <span>Rs. <?php echo number_format($data['agreement']->total_price, 2); ?></span>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="details-section">
                        <h3>Agreement Notes</h3>
                        <div class="notes-content">
                            <?php echo nl2br(htmlspecialchars($data['agreement']->notes)); ?>
                        </div>
                    </div>

                    <!-- Coordinator Signature Section -->
                    <div class="details-section">
                        <h3>Coordinator Signature</h3>
                        <?php if ($data['agreement']->coordinator_signature_id && $data['agreement']->coordinator_signature): ?>
                            <div class="signature-display">
                                <img src="<?php echo URLROOT; ?>/public/uploads/signatures/<?php echo $data['agreement']->coordinator_signature; ?>" 
                                    alt="Coordinator Signature">
                            </div>
                        <?php else: ?>
                            <p>Pending coordinator signature</p>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="button-group">
                    <?php if ($data['agreement']->status === 'pending'): ?>
                        <button class="btn btn-primary" onclick="openApprovePopup()">
                            <i>✓</i> Sign & Approve Agreement
                        </button>
                        <button class="btn btn-secondary" onclick="openSubmitAgainPopup()">
                            <i>↺</i> Request Revision
                        </button>
                        <button class="btn btn-danger" onclick="openCancelPopup()">
                            <i>✕</i> Cancel Project
                        </button>
                    <?php else: ?>
                        <div class="status-message">
                            <?php 
                            switch($data['agreement']->status) {
                                case 'revision_requested':
                                    echo '<p class="status warning">Agreement is under revision</p>';
                                    break;
                                case 'completed':
                                    echo '<p class="status success">Agreement has been signed</p>';
                                    break;
                                case 'cancelled':
                                    echo '<p class="status danger">Agreement has been cancelled</p>';
                                    break;
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>


            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Popup forms remain the same but moved outside the agreement-details div -->
        <div class="popup" id="approvePopup">
            <h2>Sign Agreement</h2>
            <p>By signing this agreement, you confirm that you have read and understood all terms and conditions.</p>
            <form id="signatureForm" enctype="multipart/form-data">
                <div class="signature-box" id="signatureBox">
                    <p>Click here to upload your signature</p>
                </div>
                <input type="file" id="signatureInput" name="signature" accept="image/*" style="display: none">
                <button type="button" class="btn btn-primary" onclick="submitSignature()">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="closePopup('approvePopup')">Cancel</button>
            </form>
        </div>

        <div class="popup" id="submitAgainPopup">
            <h2>Request Revision</h2>
            <textarea class="comment-box" placeholder="Enter your comments for changes..."></textarea>
            <button class="btn btn-primary" onclick="submitReview()">Submit</button>
            <button class="btn btn-secondary" onclick="closePopup('submitAgainPopup')">Cancel</button>
        </div>

        <div class="popup" id="cancelPopup">
            <h2>Warning!</h2>
            <p>Are you sure you want to cancel this project? This action cannot be undone.</p>
            <button class="btn btn-danger" onclick="confirmCancel()">Yes, Cancel</button>
            <button class="btn btn-secondary" onclick="closePopup('cancelPopup')">No, Keep</button>
        </div>

    <div class="overlay" id="overlay"></div>
    <script>

        const URLROOT = '<?php echo URLROOT; ?>';

    </script>
    <script src="<?php echo URLROOT; ?>/js/client/agreement.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>