<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/installation.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project/<?php echo $data['project']->pre_project_id; ?>" style="background-color: rgb(192, 236, 192);" class="back-buttons">
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
            <?php flash('installation_message'); ?>

            <!-- Installation Status Card -->
            <div class="card installation-card">
                <div class="card-header">
                    <h2>Solar Panel Installation</h2>
                    <?php if (isset($data['installation'])): ?>
                        <span class="status-badge <?php echo $data['installation']->status . ' ' . $data['installation']->schedule_status; ?>">
                            <?php
                            if ($data['installation']->status == 'completed') {
                                echo 'Completed';
                            } elseif ($data['installation']->status == 'active') {
                                echo 'In Progress';
                            } elseif ($data['installation']->schedule_status == 'approved') {
                                echo 'Scheduled';
                            } elseif ($data['installation']->schedule_status == 'pending') {
                                echo 'Pending Approval';
                            } elseif ($data['installation']->schedule_status == 'requested') {
                                echo 'Reschedule Requested';
                            } else {
                                echo 'Scheduled';
                            }
                            ?>
                        </span>
                    <?php else: ?>
                        <span class="status-badge pending">Not Scheduled</span>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if (!$data['project']->equipment_released): ?>
                        <!-- Waiting for Equipment Release -->
                        <div class="waiting-message">
                            <i class="fas fa-truck-loading"></i>
                            <div class="message-content">
                                <h3>Equipment Preparation</h3>
                                <p>We're preparing the equipment for your installation. Our team will schedule the installation once everything is ready.</p>
                            </div>
                        </div>
                    <?php elseif (!isset($data['installation'])): ?>
                        <!-- No Installation Scheduled Yet -->
                        <div class="waiting-message">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="message-content">
                                <h3>Scheduling in Progress</h3>
                                <p>Our operations team is preparing to schedule your installation. You'll be notified once the dates are proposed.</p>
                            </div>
                        </div>
                    <?php elseif ($data['installation']->schedule_status == 'pending'): ?>
                        <!-- Pending Approval -->
                        <div class="schedule-approval">
                            <div class="schedule-details">
                                <h3>Proposed Installation Schedule</h3>
                                <div class="date-display">
                                    <div class="date-range">
                                        <div class="date">
                                            <span class="day"><?php echo date('d', strtotime($data['installation']->start_date)); ?></span>
                                            <span class="month"><?php echo date('M', strtotime($data['installation']->start_date)); ?></span>
                                            <span class="year"><?php echo date('Y', strtotime($data['installation']->start_date)); ?></span>
                                        </div>
                                        <span class="to">to</span>
                                        <div class="date">
                                            <span class="day"><?php echo date('d', strtotime($data['installation']->end_date)); ?></span>
                                            <span class="month"><?php echo date('M', strtotime($data['installation']->end_date)); ?></span>
                                            <span class="year"><?php echo date('Y', strtotime($data['installation']->end_date)); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <p class="schedule-notice">Please review and confirm the proposed installation dates.</p>

                                <div class="action-buttons">
                                    <form action="<?php echo URLROOT; ?>/client/acceptInstallation" method="POST">
                                        <input type="hidden" name="installation_id" value="<?php echo $data['installation']->id; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                        <button type="submit" class="btn-confirm">
                                            <i class="fas fa-check"></i> Confirm Schedule
                                        </button>
                                    </form>
                                    <button class="btn-reschedule" onclick="showRescheduleForm()">
                                        <i class="fas fa-calendar-alt"></i> Request Different Dates
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($data['installation']->schedule_status == 'requested'): ?>
                        <!-- Reschedule Requested -->
                        <div class="reschedule-requested">
                            <i class="fas fa-clock"></i>
                            <div class="message-content">
                                <h3>Reschedule Request Submitted</h3>
                                <p>We've received your request to reschedule the installation. Our team will review it and propose new dates soon.</p>

                                <div class="request-details">
                                    <h4>Your Request</h4>
                                    <div class="detail-row">
                                        <span class="label">Reason:</span>
                                        <p class="reason"><?php echo nl2br($data['installation']->request_reason); ?></p>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Original Dates:</span>
                                        <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?> to <?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($data['installation']->schedule_status == 'approved'): ?>
                        <?php if ($data['installation']->status == 'active'): ?>
                            <!-- Active Installation -->
                            <div class="active-installation">
                                <div class="status-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="installation-content">
                                    <h3>Installation in Progress</h3>
                                    <p>Our team is currently working on installing your solar panel system.</p>

                                    <div class="progress-details">
                                        <div class="detail-row">
                                            <span class="label">Start Date:</span>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="label">Expected Completion:</span>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                        </div>

                                        <?php
                                        $today = new DateTime();
                                        $startDate = new DateTime($data['installation']->start_date);
                                        $endDate = new DateTime($data['installation']->end_date);
                                        $totalDays = $startDate->diff($endDate)->days + 1;
                                        $daysElapsed = $startDate->diff($today)->days;
                                        $progressPercent = min(100, max(0, ($daysElapsed / $totalDays) * 100));
                                        ?>

                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: <?php echo $progressPercent; ?>%"></div>
                                        </div>
                                        <div class="progress-text">
                                            <span>Day <?php echo $daysElapsed + 1; ?> of <?php echo $totalDays; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($data['installation']->status == 'completed'): ?>
                            <!-- Completed Installation -->
                            <div class="completed-installation">
                                <div class="status-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="completion-content">
                                    <h3>Installation Completed</h3>
                                    <p>Great news! Your solar panel installation has been completed successfully.</p>

                                    <div class="completion-details">
                                        <div class="detail-row">
                                            <span class="label">Start Date:</span>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="label">Completion Date:</span>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->completed_date)); ?></span>
                                        </div>
                                        <div class="next-step">
                                            <h4>Next Steps</h4>
                                            <p>Your project has moved to the Engineer Approval phase. Our engineering team will inspect the installation to ensure everything meets our quality standards.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Scheduled Installation -->
                            <div class="scheduled-installation">
                                <div class="status-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="schedule-content">
                                    <h3>Installation Scheduled</h3>
                                    <p>Your installation has been scheduled and confirmed.</p>

                                    <div class="schedule-details">
                                        <div class="date-range">
                                            <div class="date">
                                                <span class="day"><?php echo date('d', strtotime($data['installation']->start_date)); ?></span>
                                                <span class="month"><?php echo date('M', strtotime($data['installation']->start_date)); ?></span>
                                                <span class="year"><?php echo date('Y', strtotime($data['installation']->start_date)); ?></span>
                                            </div>
                                            <span class="to">to</span>
                                            <div class="date">
                                                <span class="day"><?php echo date('d', strtotime($data['installation']->end_date)); ?></span>
                                                <span class="month"><?php echo date('M', strtotime($data['installation']->end_date)); ?></span>
                                                <span class="year"><?php echo date('Y', strtotime($data['installation']->end_date)); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $today = new DateTime();
                                    $startDate = new DateTime($data['installation']->start_date);
                                    $daysUntilStart = $today->diff($startDate)->days;
                                    $isInFuture = $startDate > $today;
                                    ?>

                                    <div class="countdown">
                                        <?php if ($isInFuture): ?>
                                            <p>Installation will begin in <strong><?php echo $daysUntilStart; ?> days</strong></p>
                                        <?php else: ?>
                                            <p>Installation will begin <strong>today</strong></p>
                                        <?php endif; ?>
                                    </div>

                                    <button class="btn-reschedule" onclick="showRescheduleForm()">
                                        <i class="fas fa-calendar-alt"></i> Request Different Dates
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Installation Guidelines Card -->
            <div class="card guidelines-card">
                <div class="card-header">
                    <h3>Installation Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="guidelines-list">
                        <div class="guideline-item">
                            <i class="fas fa-home"></i>
                            <div>
                                <h4>Prepare Your Property</h4>
                                <p>Clear access to installation areas including the roof, attic, and electrical panel. Remove vehicles from driveway and move valuable items.</p>
                            </div>
                        </div>
                        <div class="guideline-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <h4>Be Available</h4>
                                <p>An adult (18+) must be present during the installation. Our team will need access to electrical panels and other areas of your property.</p>
                            </div>
                        </div>
                        <div class="guideline-item">
                            <i class="fas fa-bolt"></i>
                            <div>
                                <h4>Temporary Power Outage</h4>
                                <p>There will be a brief power outage (typically 1-2 hours) when we connect your system to the electrical panel. Plan accordingly.</p>
                            </div>
                        </div>
                        <div class="guideline-item">
                            <i class="fas fa-tasks"></i>
                            <div>
                                <h4>Installation Process</h4>
                                <p>The installation typically takes 1-3 days depending on system size. Our team will clean up the work area daily.</p>
                            </div>
                        </div>
                        <div class="guideline-item">
                            <i class="fas fa-paw"></i>
                            <div>
                                <h4>Secure Pets</h4>
                                <p>Please secure any pets during the installation for their safety and the safety of our installation team.</p>
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
            <h3>Request Different Installation Dates</h3>
            <form action="<?php echo URLROOT; ?>/client/requestInstallationReschedule" method="POST">
                <input type="hidden" name="installation_id" value="<?php echo isset($data['installation']) ? $data['installation']->id : ''; ?>">
                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                <div class="form-group">
                    <label>Reason for Reschedule</label>
                    <textarea name="reason" rows="4" required
                        placeholder="Please explain why you need to reschedule..."></textarea>
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

        // Toggle sidebar
        document.querySelector('.bx-menu').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>

    <?php require APPROOT . '/views/client/footer.php'; ?>