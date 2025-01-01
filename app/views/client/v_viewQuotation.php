<?php require APPROOT.'/views/client/header.php';?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/viewQuotation.css">
     <meta name="urlroot" content="<?php echo URLROOT; ?>">
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
                <div class="status-header">
                    <h1>Quotation Details</h1>
                    <?php flash('quotation_message'); ?>
                </div>

                <div class="quotation-card">
                    <div class="status-badge <?php echo $data['quotation']->status; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $data['quotation']->status)); ?>
                    </div>

                    <div class="quotation-details">
                        <div class="detail-row">
                            <span class="detail-label">Quotation ID:</span>
                            <span>QT<?php echo str_pad($data['quotation']->quotation_id, 4, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Package Type:</span>
                            <span><?php echo ucfirst($data['quotation']->package_type); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Monthly Consumption:</span>
                            <span><?php echo $data['quotation']->monthly_consumption; ?> kWh</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span><?php echo $data['quotation']->nearest_city; ?></span>
                        </div>
                    </div>

                    <?php if ($data['reviewed_quotation']): ?>
                    <div class="review-section">
                        <h3>System Details</h3>
                        <div class="detail-row">
                            <span class="detail-label">System Capacity:</span>
                            <span><?php echo $data['reviewed_quotation']->system_capacity; ?> kW</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estimated Generation:</span>
                            <span><?php echo $data['reviewed_quotation']->estimated_generation; ?> kWh/year</span>
                        </div>

                        <!-- Equipment List -->
                        <?php if (!empty($data['equipment'])): ?>
                        <div class="equipment-list">
                            <h3>System Equipment</h3>
                            <?php foreach($data['equipment'] as $item): ?>
                                <div class="equipment-item">
                                    <span class="item-name"><?php echo $item->item_name; ?></span>
                                    <span class="item-quantity">x<?php echo $item->quantity; ?></span>
                                    <span class="item-price">Rs. <?php echo number_format($item->total_price, 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="pricing-section">
                            <div class="price-row">
                                <span>Base Price:</span>
                                <span>Rs. <?php echo number_format($data['reviewed_quotation']->base_price, 2); ?></span>
                            </div>
                            <div class="price-row">
                                <span>Service Charge:</span>
                                <span>Rs. <?php echo number_format($data['reviewed_quotation']->service_charge, 2); ?></span>
                            </div>
                            <div class="price-row total">
                                <span>Total Price:</span>
                                <span>Rs. <?php echo number_format($data['reviewed_quotation']->total_price, 2); ?></span>
                            </div>
                        </div>

                        <?php if ($data['reviewed_quotation']->status === 'pending_customer_review'): ?>
                        <div class="action-buttons">
                            <form id="acceptForm" action="<?php echo URLROOT; ?>/client/acceptQuotation/<?php echo $data['quotation']->quotation_id; ?>" method="POST">
                                <button type="submit" class="btn btn-primary" onclick="return confirmAction('accept')">Accept Quotation</button>
                            </form>
                            <form id="rejectForm" action="<?php echo URLROOT; ?>/client/rejectQuotation/<?php echo $data['quotation']->quotation_id; ?>" method="POST">
                                <button type="submit" class="btn btn-secondary" onclick="return confirmAction('reject')">Reject Quotation</button>
                            </form>
                            <button class="btn btn-outline" onclick="downloadQuotation()">Download PDF</button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
    </div>   


    <div class="popup-overlay" id="overlay"></div>

    <div class="popup" id="acceptPopup">
        <h2>Accept Quotation</h2>
        <p>Are you sure you want to accept this quotation?</p>
        <div class="popup-buttons">
            <form action="<?php echo URLROOT; ?>/client/acceptQuotation/<?php echo $data['quotation']->quotation_id; ?>" method="POST">
                <button type="submit" class="btn-primary">Yes, Accept</button>
            </form>
            <button class="btn-secondary" onclick="closePopup('acceptPopup')">Cancel</button>
        </div>
    </div>

    <div class="popup" id="rejectPopup">
        <h2>Reject Quotation</h2>
        <p>Are you sure you want to reject this quotation?</p>
        <div class="popup-buttons">
            <form action="<?php echo URLROOT; ?>/client/rejectQuotation/<?php echo $data['quotation']->quotation_id; ?>" method="POST">
                <button type="submit" class="btn-danger">Yes, Reject</button>
            </form>
            <button class="btn-secondary" onclick="closePopup('rejectPopup')">Cancel</button>
        </div>
    </div>


    <script src="<?php echo URLROOT; ?>/js/client/viewQuotation.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>

