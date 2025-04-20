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

                <div class="card upcoming-installations-card">
                    <div class="card-header dropdown-toggle" onclick="toggleUpcomingInstallations()">
                        <h2>Upcoming Installations</h2>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="card-body" id="upcomingInstallationsBody" style="display: none;">
                        <?php if (isset($data['upcoming_installations']) && !empty($data['upcoming_installations'])): ?>
                            <div class="table-responsive">
                                <table class="installations-table">
                                    <thead>
                                        <tr>
                                            <th>Project ID</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Engineer</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['upcoming_installations'] as $installation): ?>
                                            <?php if ($installation->schedule_status != 'request'): ?>
                                                <tr>
                                                    <td><?php echo $installation->project_id; ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($installation->start_date)); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($installation->end_date)); ?></td>
                                                    <td><?php echo $installation->engineer_name ?? 'Not assigned'; ?></td>
                                                    <td>
                                                        <span class="status-pill <?php echo $installation->schedule_status; ?>">
                                                            <?php echo ucfirst($installation->schedule_status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn-sm btn-primary" onclick="viewInstallationDetails(<?php echo $installation->installation_id; ?>)">
                                                            View More
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-installations">
                                <p>No upcoming installations scheduled for the next month.</p>
                            </div>
                        <?php endif; ?>
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
                                <span class="status-badge <?php echo $data['schedule']->status; ?>">
                                    <?php echo ucfirst($data['schedule']->status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (!$data['installation']): ?>
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

                                    <!-- Team Management Section -->
                                    <div class="team-management">
                                        <h4>Installation Team</h4>

                                        <!-- Engineer Assignment -->
                                        <div class="form-group">
                                            <label>Lead Engineer:</label>
                                            <?php if (isset($data['engineer']) && $data['engineer']): ?>
                                                <div class="assigned-engineer">
                                                    <span><?php echo $data['engineer']->name; ?></span>
                                                    <button type="button" class="btn-sm btn-secondary" onclick="showEngineerReassign()">
                                                        Change
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/reassignEngineer" method="POST">
                                                    <input type="hidden" name="installation_id" value="<?php echo $data['installation']->installation_id; ?>">
                                                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                                    <div class="form-row">
                                                        <select name="engineer_id" required class="form-control">
                                                            <option value="">Select Engineer...</option>
                                                            <?php foreach ($data['engineers'] as $engineer): ?>
                                                                <option value="<?php echo $engineer->employee_id; ?>">
                                                                    <?php echo $engineer->name; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="submit" class="btn-sm btn-primary">Assign</button>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Team Members -->
                                        <div class="team-members">
                                            <h5>Team Members</h5>
                                            <?php if (isset($data['team_members']) && !empty($data['team_members'])): ?>
                                                <div class="team-list">
                                                    <?php foreach ($data['team_members'] as $member): ?>
                                                        <div class="team-member">
                                                            <span><?php echo $member->name; ?></span>
                                                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/removeTeamMember" method="POST" class="inline-form">
                                                                <input type="hidden" name="id" value="<?php echo $member->id; ?>">
                                                                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                                                <button type="submit" class="btn-sm btn-danger">Remove</button>
                                                            </form>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p>No team members assigned yet.</p>
                                            <?php endif; ?>

                                            <!-- Add Team Member Form -->
                                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/addTeamMember" method="POST" class="add-member-form">
                                                <input type="hidden" name="installation_id" value="<?php echo $data['installation']->installation_id; ?>">
                                                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                                <div class="form-row">
                                                    <select name="employee_id" required class="form-control">
                                                        <option value="">Add Team Member...</option>
                                                        <?php if (isset($data['technicians']) && !empty($data['technicians'])): ?>
                                                            <?php foreach ($data['technicians'] as $tech): ?>
                                                                <option value="<?php echo $tech->employee_id; ?>">
                                                                    <?php echo $tech->name; ?> (Technician)
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                    <button type="submit" class="btn-sm btn-primary">Add</button>
                                                </div>
                                            </form>
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

        // Add these functions to your existing script
        function showEngineerReassign() {
            document.getElementById('engineerModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeEngineerModal() {
            document.getElementById('engineerModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Toggle the upcoming installations dropdown

        function toggleUpcomingInstallations() {
            const body = document.getElementById('upcomingInstallationsBody');
            const toggleIcon = document.querySelector('.toggle-icon');

            if (body.style.display === 'none') {
                body.style.display = 'block';
                toggleIcon.textContent = '▲';
            } else {
                body.style.display = 'none';
                toggleIcon.textContent = '▼';
            }
        }

        // View installation details
        function viewInstallationDetails(installationId) {
            const modal = document.getElementById('installationDetailsModal');
            const content = document.getElementById('installationDetailsContent');

            // Show modal with loading state
            modal.style.display = 'block';
            content.innerHTML = '<div class="loading">Loading...</div>';

            // Fetch installation details
            fetch(`${URLROOT}/operationsCoordinator/getInstallationDetails/${installationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Format installation details
                        let html = `
                    <div class="installation-detail-section">
                        <h3>Project Information</h3>
                        <div class="detail-row">
                            <span class="detail-label">Project ID:</span>
                            <span class="detail-value">${data.installation.project_id}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value status-pill ${data.installation.status}">${data.installation.status.charAt(0).toUpperCase() + data.installation.status.slice(1)}</span>
                        </div>
                    </div>
                    <div class="installation-detail-section">
                        <h3>Schedule</h3>
                        <div class="detail-row">
                            <span class="detail-label">Start Date:</span>
                            <span class="detail-value">${new Date(data.schedule.start_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Start Time:</span>
                            <span class="detail-value">${new Date('2000-01-01T' + data.schedule.start_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">End Date:</span>
                            <span class="detail-value">${new Date(data.schedule.end_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                        </div>
                    </div>
                `;

                        if (data.engineer) {
                            html += `
                        <div class="installation-detail-section">
                            <h3>Lead Engineer</h3>
                            <div class="detail-row">
                                <span class="detail-label">Name:</span>
                                <span class="detail-value">${data.engineer.name}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">${data.engineer.phone}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">${data.engineer.email}</span>
                            </div>
                        </div>
                    `;
                        }

                        if (data.team_members && data.team_members.length > 0) {
                            html += `
                        <div class="installation-detail-section">
                            <h3>Team Members</h3>
                            <div class="team-list">
                    `;

                            data.team_members.forEach(member => {
                                html += `<div class="team-member">${member.name}</div>`;
                            });

                            html += `
                            </div>
                        </div>
                    `;
                        }

                        content.innerHTML = html;
                    } else {
                        content.innerHTML = '<div class="error-message">Failed to load installation details.</div>';
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="error-message">An error occurred while loading installation details.</div>';
                    console.error('Error:', error);
                });
        }

        // Close the installation details modal
        function closeInstallationModal() {
            document.getElementById('installationDetailsModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('installationDetailsModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <!-- Installation Details Modal -->
    <div id="installationDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeInstallationModal()">&times;</span>
            <h2>Installation Details</h2>
            <div id="installationDetailsContent">
                <!-- Content will be loaded here dynamically -->
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Engineer Reassignment Modal -->
    <?php if (isset($data['installation']) && is_object($data['installation'])): ?>
        <div id="engineerModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEngineerModal()">&times;</span>
                <h3>Reassign Lead Engineer</h3>
                <form action="<?php echo URLROOT; ?>/operationsCoordinator/reassignEngineer" method="POST">
                    <input type="hidden" name="installation_id" value="<?php echo isset($data['installation']) && is_object($data['installation']) ? $data['installation']->installation_id : ''; ?>">
                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                    <div class="form-group">
                        <label>Select New Engineer:</label>
                        <select name="engineer_id" required class="form-control">
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
                        <button type="button" class="btn-secondary" onclick="closeEngineerModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Reassign</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>