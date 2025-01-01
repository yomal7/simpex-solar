<?php require APPROOT.'/views/client/header.php';?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/sitevisit.css">
</head>
<body>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" class="back-button">
                    <i class='bx bx-arrow-back'></i>Back
                </a>
            </li>
        </ul>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <div class="container">
            <?php flash('site_visit_message'); ?>
            
            <!-- Site Visit Status Card -->
            <div class="card site-visit-card">
                <div class="card-header">
                    <h2>Site Visit Schedule</h2>
                    <span class="status-badge <?php echo $data['site_visit']->status; ?>">
                        <?php echo ucfirst($data['site_visit']->status); ?>
                    </span>
                </div>

                <div class="card-body">
                    <?php if($data['site_visit']->status == 'pending'): ?>
                        <!-- Waiting for Schedule -->
                        <div class="waiting-message">
                            <i class="fas fa-clock"></i>
                            <p>Waiting for operations team to schedule your site visit.</p>
                        </div>

                    <?php elseif($data['site_visit']->status == 'scheduled'): ?>
                        <!-- Show Scheduled Details -->
                        <div class="schedule-details">
                            <div class="date-display">
                                <i class="fas fa-calendar-alt"></i>
                                <div class="date">
                                    <span class="day"><?php echo date('d', strtotime($data['site_visit']->visit_date)); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($data['site_visit']->visit_date)); ?></span>
                                </div>
                            </div>
                            <div class="time-display">
                                <i class="fas fa-clock"></i>
                                <span><?php echo date('h:i A', strtotime($data['site_visit']->visit_time)); ?></span>
                            </div>

                            <div class="action-buttons">
                                <form action="<?php echo URLROOT; ?>/client/confirmSchedule" method="POST">
                                    <input type="hidden" name="visit_id" value="<?php echo $data['site_visit']->visit_id; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['site_visit']->pre_project_id; ?>">
                                    <button type="submit" class="btn-confirm">
                                        <i class="fas fa-check"></i> Confirm Schedule
                                    </button>
                                </form>
                                <button class="btn-reschedule" onclick="showRescheduleForm()">
                                    <i class="fas fa-calendar-alt"></i> Request Reschedule
                                </button>
                            </div>
                        </div>

                    <?php elseif($data['site_visit']->status == 'confirmed'): ?>
                        <!-- Show Confirmed Status -->
                        <div class="confirmed-details">
                            <i class="fas fa-check-circle"></i>
                            <h3>Schedule Confirmed</h3>
                            <div class="schedule-info">
                                <div class="detail-row">
                                    <span class="label">Date:</span>
                                    <span><?php echo date('F j, Y', strtotime($data['site_visit']->visit_date)); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Time:</span>
                                    <span><?php echo date('h:i A', strtotime($data['site_visit']->visit_time)); ?></span>
                                </div>
                            </div>
                        </div>

                        <?php elseif($data['site_visit']->status == 'reschedule_requested'): ?>
                            <div class="reschedule-details">
                                <div class="reschedule-info">
                                    <h4>Reschedule Request Details</h4>
                                    <div class="detail-row">
                                        <label>Customer's Note:</label>
                                        <p><?php echo nl2br($data['site_visit']->reschedule_request); ?></p>
                                    </div>
                                </div>

                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/handleReschedule" method="POST" class="reschedule-form">
                                    <input type="hidden" name="visit_id" value="<?php echo $data['site_visit']->visit_id; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['project']->pre_project_id; ?>">
                                    
                                    <div class="form-group">
                                        <label>New Visit Date</label>
                                        <input type="date" name="new_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>New Visit Time</label>
                                        <input type="time" name="new_time" required>
                                        <small>Site visits are scheduled between 8 AM and 4 PM</small>
                                    </div>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-calendar-check"></i> Confirm New Schedule
                                    </button>
                                </form>
                            </div>

                    <?php elseif($data['site_visit']->status == 'completed'): ?>
                        <!-- Show Completed Status -->
                        <div class="completion-details">
                            <i class="fas fa-check-circle"></i>
                            <h3>Site Visit Completed</h3>
                            <div class="completion-info">
                                <div class="detail-row">
                                    <span class="label">Visit Date:</span>
                                    <span><?php echo date('F j, Y', strtotime($data['site_visit']->visit_date)); ?></span>
                                </div>
                                <?php if($data['site_visit']->site_notes): ?>
                                    <div class="detail-row">
                                        <span class="label">Notes:</span>
                                        <p class="site-notes"><?php echo nl2br($data['site_visit']->site_notes); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Requirements Card -->
            <div class="card requirements-card">
                <div class="card-header">
                    <h3>Site Visit Requirements</h3>
                </div>
                <div class="card-body">
                    <div class="requirements-list">
                        <div class="requirement-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <h4>Property Owner Presence</h4>
                                <p>The property owner must be present during the site visit</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-file-alt"></i>
                            <div>
                                <h4>Required Documents</h4>
                                <p>Please have your electricity bills from the past 3 months ready</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Duration</h4>
                                <p>The site visit typically takes 1-2 hours</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-tools"></i>
                            <div>
                                <h4>Access Requirements</h4>
                                <p>Access to roof and electrical panel must be available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Popup -->
    <div id="reschedulePopup" class="popup">
        <div class="popup-content">
            <h3>Request Reschedule</h3>
            <form action="<?php echo URLROOT; ?>/client/requestReschedule" method="POST">
                <input type="hidden" name="visit_id" value="<?php echo $data['site_visit']->visit_id; ?>">
                <input type="hidden" name="pre_project_id" value="<?php echo $data['site_visit']->pre_project_id; ?>">
                
                <div class="form-group">
                    <label>Reason for Reschedule</label>
                    <textarea name="reason" rows="4" required 
                        placeholder="Please provide a reason for rescheduling..."></textarea>
                </div>

                <div class="popup-buttons">
                    <button type="button" class="btn-secondary" onclick="closeRescheduleForm()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
    function showRescheduleForm() {
        document.getElementById('reschedulePopup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closeRescheduleForm() {
        document.getElementById('reschedulePopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    // Close popup when clicking overlay
    document.getElementById('overlay').addEventListener('click', closeRescheduleForm);
    </script>

<?php require APPROOT.'/views/client/footer.php';?>