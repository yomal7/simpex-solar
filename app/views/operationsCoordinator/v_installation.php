<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/installation.css">
</head>

<body>
    <?php flash('installation_message'); ?>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Operations Coordinator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject/<?php echo $data['project']->project_id; ?>" class="sidebar-link">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="content">
                <div class="page-header">
                    <h2>Manage Installation</h2>
                </div>

                <div class="installation-container">
                    <!-- Project Info Card -->
                    <div class="card project-info">
                        <div class="card-header">
                            <h3>Project Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Customer Name:</label>
                                    <span><?php echo $data['project']->customer_name; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Contact:</label>
                                    <span><?php echo $data['project']->phone; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Email:</label>
                                    <span><?php echo $data['project']->email; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Location:</label>
                                    <span><?php echo $data['project']->location; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Address:</label>
                                    <span><?php echo $data['project']->address; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>System Capacity:</label>
                                    <span><?php echo $data['project']->system_capacity; ?> kW</span>
                                </div>
                                <div class="info-item">
                                    <label>Equipment Status:</label>
                                    <span class="status-badge <?php echo $data['project']->equipment_released ? 'released' : 'pending'; ?>">
                                        <?php echo $data['project']->equipment_released ? 'Released' : 'Pending Release'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Installation Management Card -->
                    <div class="card installation-management">
                        <div class="card-header">
                            <h3>Installation Management</h3>
                            <?php if ($data['installation']): ?>
                                <span class="status-badge <?php echo $data['installation']->status; ?>">
                                    <?php
                                    if ($data['installation']->status == 'completed') {
                                        echo 'Completed';
                                    } elseif ($data['installation']->status == 'active') {
                                        echo 'Active';
                                    } else {
                                        echo ucfirst($data['installation']->schedule_status);
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (!$data['project']->equipment_released): ?>
                                <!-- Wait for equipment release -->
                                <div class="waiting-equipment">
                                    <div class="info-message">
                                        <i class="material-icons-sharp">info</i>
                                        <p>Equipment release is pending. Installation scheduling will be available once equipment is released by the Supplier Coordinator.</p>
                                    </div>
                                </div>
                            <?php elseif (!$data['installation']): ?>
                                <!-- Schedule Form -->
                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/scheduleInstallation" method="POST" class="schedule-form">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                    <h4>Schedule Installation</h4>
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-primary">
                                            <i class="material-icons-sharp">schedule</i> Schedule Installation
                                        </button>
                                    </div>
                                </form>
                            <?php elseif ($data['installation']->schedule_status == 'requested'): ?>
                                <!-- Handle Reschedule Request -->
                                <div class="reschedule-request">
                                    <div class="request-details">
                                        <h4>Reschedule Request</h4>
                                        <div class="info-row">
                                            <label>Current Schedule:</label>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?> to <?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <label>Reason:</label>
                                            <p><?php echo nl2br($data['installation']->request_reason); ?></p>
                                        </div>
                                    </div>

                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/rescheduleInstallation" method="POST" class="reschedule-form">
                                        <input type="hidden" name="installation_id" value="<?php echo $data['installation']->id; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                        <h4>Reschedule Installation</h4>
                                        <div class="form-group">
                                            <label for="start_date">New Start Date</label>
                                            <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">New End Date</label>
                                            <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn-primary">
                                                <i class="material-icons-sharp">schedule</i> Confirm Reschedule
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php elseif ($data['installation']->status == 'active'): ?>
                                <!-- Active Installation Management -->
                                <div class="active-installation">
                                    <div class="installation-details">
                                        <h4>Installation Progress</h4>
                                        <div class="info-row">
                                            <label>Start Date:</label>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <label>End Date:</label>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <label>Days Remaining:</label>
                                            <?php
                                            $today = new DateTime();
                                            $endDate = new DateTime($data['installation']->end_date);
                                            $daysRemaining = $today->diff($endDate)->days;

                                            if ($endDate < $today) {
                                                echo '<span class="overdue">Overdue by ' . abs($daysRemaining) . ' days</span>';
                                            } else {
                                                echo '<span>' . $daysRemaining . ' days</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="installation-actions">
                                        <form action="<?php echo URLROOT; ?>/operationsCoordinator/extendInstallation" method="POST" class="extend-form">
                                            <input type="hidden" name="installation_id" value="<?php echo $data['installation']->id; ?>">
                                            <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                            <h4>Extend Installation</h4>
                                            <div class="form-group">
                                                <label for="end_date">New End Date</label>
                                                <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d', strtotime($data['installation']->end_date . ' +1 day')); ?>">
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn-secondary">
                                                    <i class="material-icons-sharp">date_range</i> Extend
                                                </button>
                                            </div>
                                        </form>

                                        <form action="<?php echo URLROOT; ?>/operationsCoordinator/completeInstallation" method="POST" class="complete-form">
                                            <input type="hidden" name="installation_id" value="<?php echo $data['installation']->id; ?>">
                                            <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                            <h4>Complete Installation</h4>
                                            <p>Mark this installation as completed once all work is done.</p>
                                            <div class="form-actions">
                                                <button type="submit" class="btn-primary">
                                                    <i class="material-icons-sharp">check_circle</i> Mark as Completed
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php elseif ($data['installation']->status == 'completed'): ?>
                                <!-- Completed Installation Details -->
                                <div class="completed-installation">
                                    <div class="completion-info">
                                        <i class="material-icons-sharp">check_circle</i>
                                        <h4>Installation Completed</h4>
                                    </div>
                                    <div class="info-row">
                                        <label>Start Date:</label>
                                        <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <label>End Date:</label>
                                        <span><?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <label>Completion Date:</label>
                                        <span><?php echo date('F j, Y', strtotime($data['installation']->completed_date)); ?></span>
                                    </div>
                                    <div class="info-row next-phase">
                                        <p>Project has moved to the Engineer Approval phase.</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Pending or Scheduled Installation -->
                                <div class="scheduled-installation">
                                    <div class="schedule-details">
                                        <h4>Installation Schedule</h4>
                                        <div class="info-row">
                                            <label>Start Date:</label>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->start_date)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <label>End Date:</label>
                                            <span><?php echo date('F j, Y', strtotime($data['installation']->end_date)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <label>Status:</label>
                                            <span class="status-badge <?php echo $data['installation']->schedule_status; ?>">
                                                <?php
                                                if ($data['installation']->schedule_status == 'approved') {
                                                    echo 'Approved';
                                                } else {
                                                    echo 'Pending Approval';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                    <?php if ($data['installation']->schedule_status == 'pending'): ?>
                                        <div class="waiting-approval">
                                            <div class="info-message">
                                                <i class="material-icons-sharp">schedule</i>
                                                <p>Waiting for customer approval of the installation schedule.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Option to reschedule -->
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/rescheduleInstallation" method="POST" class="reschedule-form">
                                        <input type="hidden" name="installation_id" value="<?php echo $data['installation']->id; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                        <h4>Reschedule Installation</h4>
                                        <div class="form-group">
                                            <label for="start_date">New Start Date</label>
                                            <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">New End Date</label>
                                            <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn-secondary">
                                                <i class="material-icons-sharp">schedule</i> Reschedule
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Form validation for dates
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                if (form.querySelector('#start_date') && form.querySelector('#end_date')) {
                    const startDateInput = form.querySelector('#start_date');
                    const endDateInput = form.querySelector('#end_date');

                    startDateInput.addEventListener('change', function() {
                        endDateInput.min = this.value;

                        // If end date is before start date, reset it
                        if (endDateInput.value && endDateInput.value < this.value) {
                            endDateInput.value = this.value;
                        }
                    });
                }
            });
        });
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>