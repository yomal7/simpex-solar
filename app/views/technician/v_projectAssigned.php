<?php require APPROOT . '/views/technician/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/technician/projectInstallation.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="technician profile-picture" class="profile-picture" />

            <a href="<?php echo URLROOT ?>/technician/projects">
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
                <?php flash('installation_message'); ?>

                <!-- Project Information Card -->
                <div class="card project-info-card">
                    <div class="card-header">
                        <h2>Project #<?php echo isset($data['project']) ? $data['project']->project_id : 'N/A'; ?></h2>
                        <span class="status-badge <?php echo isset($data['installation']) ? $data['installation']->status : 'unknown'; ?>">
                            <?php echo isset($data['installation']) ? ucfirst($data['installation']->status) : 'Unknown'; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-group">
                                <h3>Client Information</h3>
                                <div class="info-item">
                                    <span class="info-label">Client Name:</span>
                                    <span class="info-value"><?php echo isset($data['project']) ? $data['project']->customer_name : 'Not available'; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Installation Address:</span>
                                    <span class="info-value"><?php echo isset($data['project']) ? $data['project']->address : 'Not available'; ?></span>
                                </div>
                            </div>

                            <div class="info-group">
                                <h3>System Details</h3>
                                <div class="info-item">
                                    <span class="info-label">System Capacity:</span>
                                    <span class="info-value"><?php echo $data['project']->system_capacity ?? 'Not specified'; ?> kW</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Estimated Generation:</span>
                                    <span class="info-value"><?php echo $data['project']->estimated_generation ?? 'Not specified'; ?> kWh/month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="card schedule-card">
                    <div class="card-header">
                        <h2>Installation Schedule</h2>
                    </div>
                    <div class="card-body">
                        <div class="schedule-details">
                            <div class="schedule-item">
                                <span class="schedule-label"><i class="material-icons-sharp">calendar_today</i> Start Date:</span>
                                <span class="schedule-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->start_date)); ?></span>
                            </div>
                            <div class="schedule-item">
                                <span class="schedule-label"><i class="material-icons-sharp">schedule</i> Start Time:</span>
                                <span class="schedule-value"><?php echo date('h:i A', strtotime($data['schedule']->start_time)); ?></span>
                            </div>
                            <div class="schedule-item">
                                <span class="schedule-label"><i class="material-icons-sharp">event_available</i> End Date:</span>
                                <span class="schedule-value"><?php echo date('l, F j, Y', strtotime($data['schedule']->end_date)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Members Card -->
                <div class="card team-card">
                    <div class="card-header">
                        <h2>Installation Team</h2>
                    </div>
                    <div class="card-body">
                        <div class="team-lead">
                            <div class="team-member-avatar">
                                <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Lead technician">
                            </div>
                            <div class="team-member-info">
                                <h3><?php echo $data['technician']->name; ?></h3>
                                <span class="role-badge">Lead technician</span>
                            </div>
                        </div>

                        <h3 class="technicians-heading">Technicians</h3>
                        <div class="technicians-grid">
                            <?php if (!empty($data['team_members'])): ?>
                                <?php foreach ($data['team_members'] as $member): ?>
                                    <div class="team-member">
                                        <div class="team-member-avatar">
                                            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Technician">
                                        </div>
                                        <div class="team-member-info">
                                            <h4><?php echo $member->name; ?></h4>
                                            <span class="role-badge">Technician</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-technicians">No technicians assigned to this installation.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmation-modal">
        <div class="modal-content">
            <h3 id="modal-title">Confirm Action</h3>
            <p id="modal-message">Are you sure you want to proceed with this action?</p>
            <div class="modal-buttons">
                <button id="modal-cancel" class="btn-secondary">Cancel</button>
                <button id="modal-confirm" class="btn-primary">Confirm</button>
            </div>
        </div>
    </div>
    <div id="modal-overlay" class="modal-overlay"></div>


    <?php require APPROOT . '/views/technician/footer.php'; ?>