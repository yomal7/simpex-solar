<?php require APPROOT.'/views/client/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/services.css">
</head>

<body>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/services" class="back-button"><i class='bx bx-arrow-back'></i>Back</a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <div class="page-title">
                <h3>Service Request Details</h3>
            </div>
        </nav>
        <!-- End of Navbar -->
            
        <div class="container">
            <?php flash('service_message'); ?>
            
            <div class="service-details-container">
                <div class="card">
                    <div class="card-header">
                        <h2>Service Request #<?php echo str_pad($data['service']->service_id, 5, '0', STR_PAD_LEFT); ?></h2>
                        <span class="status-badge <?php echo strtolower($data['service']->status); ?>">
                            <?php echo ucfirst($data['service']->status); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="service-info">
                            <div class="info-row">
                                <div class="info-label">Requested On:</div>
                                <div class="info-value"><?php echo date('d M Y, h:i A', strtotime($data['service']->requested_date)); ?></div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Project:</div>
                                <div class="info-value"><?php echo $data['service']->package_name; ?></div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Issue Type:</div>
                                <div class="info-value">
                                    <?php
                                        switch($data['service']->issue_type) {
                                            case 'electrical':
                                                echo 'Electrical Issues';
                                                break;
                                            case 'mechanical':
                                                echo 'Mechanical Issues';
                                                break;
                                            case 'performance':
                                                echo 'Performance Issues';
                                                break;
                                            default:
                                                echo 'Other Issues';
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="info-row description">
                                <div class="info-label">Description:</div>
                                <div class="info-value description-text"><?php echo nl2br(htmlspecialchars($data['service']->description)); ?></div>
                            </div>
                            
                            <?php if(!empty($data['service']->scheduled_date)): ?>
                            <div class="info-row">
                                <div class="info-label">Scheduled Date:</div>
                                <div class="info-value highlight"><?php echo date('d M Y, h:i A', strtotime($data['service']->scheduled_date)); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($data['service']->technician_notes)): ?>
                            <div class="info-row notes">
                                <div class="info-label">Technician Notes:</div>
                                <div class="info-value technician-notes"><?php echo nl2br(htmlspecialchars($data['service']->technician_notes)); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($data['service']->completion_date)): ?>
                            <div class="info-row">
                                <div class="info-label">Completed On:</div>
                                <div class="info-value highlight"><?php echo date('d M Y, h:i A', strtotime($data['service']->completion_date)); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($data['service']->status === 'cancelled'): ?>
                            <div class="info-row">
                                <div class="info-label">Cancelled On:</div>
                                <div class="info-value"><?php echo date('d M Y, h:i A', strtotime($data['service']->updated_at)); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($data['service']->status === 'pending'): ?>
                        <div class="service-actions">
                            <form action="<?php echo URLROOT; ?>/client/cancelServiceRequest/<?php echo $data['service']->service_id; ?>" method="POST" id="cancelServiceForm">
                                <button type="button" class="btn btn-danger" id="cancelServiceBtn">Cancel Request</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Project Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="service-info">
                            <div class="info-row">
                                <div class="info-label">Project ID:</div>
                                <div class="info-value">PR<?php echo str_pad($data['project']->project_id, 5, '0', STR_PAD_LEFT); ?></div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Package:</div>
                                <div class="info-value"><?php echo $data['project']->package_name; ?></div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Installation Date:</div>
                                <div class="info-value">
                                    <?php echo !empty($data['project']->completed_date) ? date('d M Y', strtotime($data['project']->completed_date)) : 'Not recorded'; ?>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Warranty Period:</div>
                                <div class="info-value"><?php echo $data['project']->warranty_years; ?> Years</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Warranty End Date:</div>
                                <div class="info-value highlight"><?php echo date('d M Y', strtotime($data['project']->warranty_end_date)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Cancellation</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this service request? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="closeModalBtn">No, Keep It</button>
                <button class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Request</button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality for cancellation
        const modal = document.getElementById('confirmationModal');
        const cancelBtn = document.getElementById('cancelServiceBtn');
        const closeBtn = document.querySelector('.close');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const confirmCancelBtn = document.getElementById('confirmCancelBtn');
        const cancelForm = document.getElementById('cancelServiceForm');
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                modal.style.display = 'block';
            });
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        if (confirmCancelBtn && cancelForm) {
            confirmCancelBtn.addEventListener('click', function() {
                cancelForm.submit();
            });
        }
        
        // Close modal if clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    </script>

    <script src="<?php echo URLROOT; ?>/js/client/services.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>