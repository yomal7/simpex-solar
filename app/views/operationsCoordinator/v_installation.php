<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/installation.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="manager profile-picture" class="profile-picture" />

            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject/<?php echo $data['project']->project_id; ?>">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <!-- Installation Header -->
                <div class="page-header">
                    <h1>Installation Phase Management</h1>
                    <span class="project-id">Project ID: <?php echo $data['project']->project_id; ?></span>
                </div>

                <?php flash('installation_message'); ?>

                <!-- Customer Information Card -->
                <div class="card customer-card">
                    <div class="card-header">
                        <h2>Customer Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">Customer Name</p>
                                <p class="info-value"><?php echo $data['project']->customer_name; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Phone</p>
                                <p class="info-value"><?php echo $data['project']->phone; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Email</p>
                                <p class="info-value"><?php echo $data['project']->email; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Address</p>
                                <p class="info-value"><?php echo $data['project']->address ?? $data['project']->location; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Details Card -->
                <div class="card project-details-card">
                    <div class="card-header">
                        <h2>Project Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">System Capacity</p>
                                <p class="info-value"><?php echo $data['agreement']->system_capacity; ?> kW</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Estimated Generation</p>
                                <p class="info-value"><?php echo $data['agreement']->estimated_generation; ?> kWh/month</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Project Cost</p>
                                <p class="info-value">Rs. <?php echo number_format($data['agreement']->total_price, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment Status Card -->
                <?php if (!$data['project']->equipment_released): ?>
                    <div class="card equipment-status-card">
                        <div class="card-header">
                            <h2>Equipment Status</h2>
                            <span class="status-badge pending">Pending</span>
                        </div>
                        <div class="card-body">
                            <div class="equipment-pending">
                                <div class="icon-container">
                                    <span class="material-icons-sharp">inventory</span>
                                </div>
                                <h3>Equipment Not Released</h3>
                                <p>The Supplier Coordinator has not released the equipment for this project yet. Installation scheduling can begin once equipment is released.</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>

                    <!-- Installation Management Card -->
                    <div class="card installation-card">
                        <div class="card-header">
                            <h2>Installation Management</h2>
                            <?php if (isset($data['installation']) && is_object($data['installation'])): ?>
                                <span class="status-badge <?php echo $data['installation']->status; ?>">
                                    <?php echo ucfirst($data['installation']->status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (!isset($data['installation'])): ?>
                                <!-- No installation record yet, show scheduling form -->
                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/scheduleInstallation" method="POST" class="installation-form" id="installationForm">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                                    <div class="form-group">
                                        <label for="startDate">Installation Start Date:</label>
                                        <input type="date" id="startDate" name="start_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="startTime">Installation Start Time:</label>
                                        <input type="time" id="startTime" name="start_time" required min="08:00" max="12:00">
                                        <small>Must be between 8:00 AM and 12:00 PM</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="durationDays">Estimated Duration (days):</label>
                                        <input type="number" id="durationDays" name="duration_days" min="1" max="14" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="engineerId">Assign Engineer:</label>
                                        <select id="engineerId" name="engineer_id" required>
                                            <option value="">Select Engineer...</option>
                                            <?php if (isset($data['engineers']) && !empty($data['engineers'])): ?>
                                                <?php foreach ($data['engineers'] as $engineer): ?>
                                                    <option value="<?php echo $engineer->employee_id; ?>">
                                                        <?php echo $engineer->name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn-primary">Schedule Installation</button>
                                    </div>
                                </form>
                            <?php elseif (isset($data['schedule']) && $data['schedule']->status == 'pending'): ?>
                                <!-- Pending customer approval -->
                                <div class="pending-approval">
                                    <div class="icon-container">
                                        <span class="material-icons-sharp">schedule</span>
                                    </div>
                                    <h3>Waiting for Customer Approval</h3>
                                    <p>The installation has been scheduled. Waiting for customer to approve the proposed dates.</p>

                                    <div class="schedule-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Start Date:</span>
                                            <span class="detail-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->start_date)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Start Time:</span>
                                            <span class="detail-value"><?php echo date('h:i A', strtotime($data['schedule']->start_time)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">End Date:</span>
                                            <span class="detail-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->end_date)); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif (isset($data['schedule']) && $data['schedule']->status == 'request'): ?>
                                <!-- Customer requested reschedule -->
                                <div class="reschedule-request">
                                    <div class="icon-container">
                                        <span class="material-icons-sharp">update</span>
                                    </div>
                                    <h3>Customer Requested Reschedule</h3>
                                    <div class="reschedule-reason">
                                        <h4>Reason for Reschedule:</h4>
                                        <p><?php echo $data['schedule']->reschedule_request; ?></p>
                                    </div>

                                    <!-- Show scheduling form again -->
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/rescheduleInstallation" method="POST" class="installation-form" id="rescheduleForm">
                                        <input type="hidden" name="installation_id" value="<?php echo $data['installation']->installation_id; ?>">
                                        <input type="hidden" name="schedule_id" value="<?php echo $data['schedule']->id; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                                        <div class="form-group">
                                            <label for="newStartDate">New Start Date:</label>
                                            <input type="date" id="newStartDate" name="start_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="newStartTime">New Start Time:</label>
                                            <input type="time" id="newStartTime" name="start_time" required min="08:00" max="12:00">
                                            <small>Must be between 8:00 AM and 12:00 PM</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="newDurationDays">Estimated Duration (days):</label>
                                            <input type="number" id="newDurationDays" name="duration_days" min="1" max="14" required>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn-primary">Reschedule Installation</button>
                                        </div>
                                    </form>
                                </div>
                            <?php elseif (isset($data['schedule']) && $data['schedule']->status == 'accept'): ?>
                                <!-- Customer approved schedule -->
                                <div class="schedule-approved">
                                    <div class="icon-container success">
                                        <span class="material-icons-sharp">check_circle</span>
                                    </div>
                                    <h3>Installation Schedule Confirmed</h3>
                                    <p>The customer has approved the installation schedule.</p>

                                    <div class="schedule-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Start Date:</span>
                                            <span class="detail-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->start_date)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Start Time:</span>
                                            <span class="detail-value"><?php echo date('h:i A', strtotime($data['schedule']->start_time)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">End Date:</span>
                                            <span class="detail-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->end_date)); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Engineer:</span>
                                            <span class="detail-value"><?php echo $data['engineer']->name ?? 'Not assigned'; ?></span>
                                        </div>
                                    </div>

                                    <!-- Progress tracking for steps -->
                                    <div class="installation-progress">
                                        <h4>Installation Progress</h4>
                                        <div class="progress-steps">
                                            <!-- Step sections go here -->
                                        </div>
                                    </div>

                                    <?php if ($data['installation']->step_01 && $data['installation']->step_02 && $data['installation']->step_03 && $data['installation']->step_04): ?>
                                        <!-- All steps completed, show complete installation button -->
                                        <div class="complete-installation">
                                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/completeInstallation" method="POST">
                                                <input type="hidden" name="installation_id" value="<?php echo $data['installation']->installation_id; ?>">
                                                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                                <button type="submit" class="btn-success">Complete Installation Phase</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Time validation for installation form
        document.addEventListener('DOMContentLoaded', function() {
            const startTimeInput = document.getElementById('startTime');
            if (startTimeInput) {
                startTimeInput.addEventListener('change', function() {
                    const time = this.value;
                    const hour = parseInt(time.split(':')[0]);

                    if (hour < 8 || hour > 12) {
                        alert('Installation start time must be between 8:00 AM and 12:00 PM');
                        this.value = '08:00';
                    }
                });
            }
        });
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>