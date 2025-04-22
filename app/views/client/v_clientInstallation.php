<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/installation.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a>
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
            <?php flash('installation_message'); ?>

            <!-- Installation Status Card -->
            <div class="card installation-card">
                <div class="card-header">
                    <h2>Installation Schedule</h2>
                    <?php if (isset($data['schedule']) && is_object($data['schedule'])): ?>
                        <span class="status-badge <?php echo $data['schedule']->status; ?>">
                            <?php echo ucfirst($data['schedule']->status); ?>
                        </span>
                    <?php else: ?>
                        <span class="status-badge pending">Pending</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!isset($data['installation']) || !$data['installation']): ?>
                        <!-- No installation scheduled yet -->
                        <div class="waiting-message">
                            <i class="material-icons-sharp">schedule</i>
                            <h3>Waiting for Installation Schedule</h3>
                            <p>Our operations team is currently planning your installation. You will be notified once a schedule is proposed.</p>
                        </div>
                    <?php elseif (isset($data['schedule']) && $data['schedule']->status === 'pending'): ?>
                        <!-- Installation scheduled, waiting for confirmation -->
                        <div class="schedule-details">
                            <div class="schedule-info">
                                <h3>Proposed Installation Schedule</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label"><i class="far fa-calendar-alt"></i> Start Date:</span>
                                        <span class="info-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->start_date)); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label"><i class="far fa-clock"></i> Start Time:</span>
                                        <span class="info-value"><?php echo date('h:i A', strtotime($data['schedule']->start_time)); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label"><i class="far fa-calendar-check"></i> Estimated End Date:</span>
                                        <span class="info-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->end_date)); ?></span>
                                    </div>
                                    <?php if (isset($data['engineer'])): ?>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-hard-hat"></i> Lead Engineer:</span>
                                            <span class="info-value"><?php echo $data['engineer']->name; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="schedule-actions">
                                <form action="<?php echo URLROOT; ?>/client/acceptInstallationSchedule" method="POST" class="inline-form">
                                    <input type="hidden" name="schedule_id" value="<?php echo $data['schedule']->id; ?>">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">
                                    <button type="submit" class="btn btn-accept">
                                        <i class="fas fa-check"></i> Accept Schedule
                                    </button>
                                </form>
                                <button class="btn btn-reschedule" onclick="openRescheduleModal()">
                                    <i class="fas fa-calendar-alt"></i> Request Different Date
                                </button>
                            </div>
                        </div>
                    <?php elseif (isset($data['schedule']) && $data['schedule']->status === 'request'): ?>
                        <!-- Reschedule requested -->
                        <div class="reschedule-requested">
                            <i class="material-icons-sharp">update</i>
                            <h3>Reschedule Requested</h3>
                            <p>You have requested a different installation schedule. Our team will review your request and propose a new schedule soon.</p>

                            <div class="request-details">
                                <h4>Your Request:</h4>
                                <p><?php echo nl2br($data['schedule']->reschedule_request); ?></p>
                            </div>
                        </div>
                    <?php elseif (isset($data['schedule']) && $data['schedule']->status === 'accept'): ?>
                        <!-- Schedule accepted -->
                        <div class="schedule-confirmed">
                            <i class="material-icons-sharp check_circle">check_circle</i>
                            <h3>Installation Schedule Confirmed</h3>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label"><i class="far fa-calendar-alt"></i> Start Date:</span>
                                    <span class="info-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->start_date)); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="far fa-clock"></i> Start Time:</span>
                                    <span class="info-value"><?php echo date('h:i A', strtotime($data['schedule']->start_time)); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="far fa-calendar-check"></i> Estimated End Date:</span>
                                    <span class="info-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->end_date)); ?></span>
                                </div>
                                <?php if (isset($data['engineer'])): ?>
                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-hard-hat"></i> Lead Engineer:</span>
                                        <span class="info-value"><?php echo $data['engineer']->name; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="installation-note">
                                <p>Please ensure that you or an authorized representative will be present at the installation site on the scheduled date.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Installation Requirements Card -->
            <div class="card requirements-card">
                <div class="card-header">
                    <h2>Installation Requirements</h2>
                </div>
                <div class="card-body">
                    <div class="requirements-list">
                        <div class="requirement-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <h4>Property Access</h4>
                                <p>Please ensure that our installation team can access your property on the scheduled dates.</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-plug"></i>
                            <div>
                                <h4>Power Availability</h4>
                                <p>Access to electricity during installation is required for our power tools.</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-car"></i>
                            <div>
                                <h4>Parking Space</h4>
                                <p>Please provide parking space for our installation vehicle(s) close to your property.</p>
                            </div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-broom"></i>
                            <div>
                                <h4>Clear Installation Area</h4>
                                <p>Please ensure the roof and surrounding areas are clear of debris and personal items.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeRescheduleModal()">&times;</span>
            <h3>Request Different Installation Date</h3>
            <form action="<?php echo URLROOT; ?>/client/requestInstallationReschedule" method="POST">
                <input type="hidden" name="schedule_id" value="<?php echo isset($data['schedule']) ? $data['schedule']->id : ''; ?>">
                <input type="hidden" name="pre_project_id" value="<?php echo $data['pre_project_id']; ?>">

                <div class="form-group">
                    <label for="reschedule_reason">Please explain why you need a different date:</label>
                    <textarea id="reschedule_reason" name="reschedule_reason" rows="4" required placeholder="Provide details about your availability and preferred dates..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
    <div id="overlay" class="modal-overlay"></div>

    <script>
        // Modal functionality
        function openRescheduleModal() {
            document.getElementById('rescheduleModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeRescheduleModal() {
            document.getElementById('rescheduleModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Close modal when clicking overlay
        document.getElementById('overlay').addEventListener('click', closeRescheduleModal);
    </script>

    <?php require APPROOT . '/views/client/footer.php'; ?>