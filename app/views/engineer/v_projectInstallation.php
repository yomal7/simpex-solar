<?php require APPROOT . '/views/engineer/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/projectInstallation.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="engineer profile-picture" class="profile-picture" />

            <a href="<?php echo URLROOT ?>/engineer/projects">
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
                                <div class="info-item">
                                    <span class="info-label">Contact Number:</span>
                                    <span class="info-value"><?php echo $data['project']->phone ?? 'Not available'; ?></span>
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
                                <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Lead Engineer">
                            </div>
                            <div class="team-member-info">
                                <h3><?php echo $data['engineer']->name; ?></h3>
                                <span class="role-badge">Lead Engineer</span>
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

                <!-- Installation Progress Card -->
                <div class="card progress-card">
                    <div class="card-header">
                        <h2>Installation Progress</h2>
                    </div>
                    <div class="card-body">
                        <div class="progress-steps">
                            <!-- Step 0: Start Installation -->
                            <div class="progress-step <?php echo $data['installation']->status == 'active' ? 'completed' : ''; ?>" id="step-start">
                                <div class="step-indicator">
                                    <?php if ($data['installation']->status == 'active'): ?>
                                        <span class="material-icons-sharp check-icon">check_circle</span>
                                    <?php else: ?>
                                        <span class="step-number">0</span>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h3>Start Installation</h3>
                                    <p>Begin the installation process</p>
                                    <?php if ($data['installation']->status == 'initial'): ?>
                                        <button class="btn-action" id="btn-start-installation" data-id="<?php echo $data['installation']->installation_id; ?>">
                                            <i class="material-icons-sharp">play_arrow</i> Start Installation
                                        </button>
                                    <?php elseif ($data['installation']->status == 'active'): ?>
                                        <span class="completion-date">Started: <?php echo isset($data['installation']->start_date) ? date('M d, Y', strtotime($data['installation']->start_date)) : date('M d, Y'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 1: Mounting Panel Structure -->
                            <div class="progress-step <?php echo $data['installation']->step_1 ? 'completed' : ($data['installation']->status == 'active' ? 'active' : 'locked'); ?>" id="step-1">
                                <div class="step-indicator">
                                    <?php if ($data['installation']->step_1): ?>
                                        <span class="material-icons-sharp check-icon">check_circle</span>
                                    <?php else: ?>
                                        <span class="step-number">1</span>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h3>Mounting the Panel Structure</h3>
                                    <p>Install mounting brackets and support structure</p>
                                    <?php if ($data['installation']->status == 'active' && !$data['installation']->step_1): ?>
                                        <button class="btn-action" id="btn-complete-step-1" data-id="<?php echo $data['installation']->installation_id; ?>">
                                            <i class="material-icons-sharp">check</i> Mark as Completed
                                        </button>
                                    <?php elseif ($data['installation']->step_1): ?>
                                        <span class="completion-date">Completed: <?php echo isset($data['installation']->step_1_date) ? date('M d, Y', strtotime($data['installation']->step_1_date)) : date('M d, Y'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 2: Placing and Fixing Solar Panels -->
                            <div class="progress-step <?php echo $data['installation']->step_2 ? 'completed' : ($data['installation']->step_1 ? 'active' : 'locked'); ?>" id="step-2">
                                <div class="step-indicator">
                                    <?php if ($data['installation']->step_2): ?>
                                        <span class="material-icons-sharp check-icon">check_circle</span>
                                    <?php else: ?>
                                        <span class="step-number">2</span>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h3>Placing and Fixing Solar Panels</h3>
                                    <p>Mount and secure solar panels to the structure</p>
                                    <?php if ($data['installation']->step_1 && !$data['installation']->step_2): ?>
                                        <button class="btn-action" id="btn-complete-step-2" data-id="<?php echo $data['installation']->installation_id; ?>">
                                            <i class="material-icons-sharp">check</i> Mark as Completed
                                        </button>
                                    <?php elseif ($data['installation']->step_2): ?>
                                        <span class="completion-date">Completed: <?php echo isset($data['installation']->step_2_date) ? date('M d, Y', strtotime($data['installation']->step_2_date)) : date('M d, Y'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 3: Electrical Wiring -->
                            <div class="progress-step <?php echo $data['installation']->step_3 ? 'completed' : ($data['installation']->step_2 ? 'active' : 'locked'); ?>" id="step-3">
                                <div class="step-indicator">
                                    <?php if ($data['installation']->step_3): ?>
                                        <span class="material-icons-sharp check-icon">check_circle</span>
                                    <?php else: ?>
                                        <span class="step-number">3</span>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h3>Electrical Wiring</h3>
                                    <p>Connect panels to inverter and electrical system</p>
                                    <?php if ($data['installation']->step_2 && !$data['installation']->step_3): ?>
                                        <button class="btn-action" id="btn-complete-step-3" data-id="<?php echo $data['installation']->installation_id; ?>">
                                            <i class="material-icons-sharp">check</i> Mark as Completed
                                        </button>
                                    <?php elseif ($data['installation']->step_3): ?>
                                        <span class="completion-date">Completed: <?php echo isset($data['installation']->step_3_date) ? date('M d, Y', strtotime($data['installation']->step_3_date)) : date('M d, Y'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 4: System Testing -->
                            <div class="progress-step <?php echo $data['installation']->step_4 ? 'completed' : ($data['installation']->step_3 ? 'active' : 'locked'); ?>" id="step-4">
                                <div class="step-indicator">
                                    <?php if ($data['installation']->step_4): ?>
                                        <span class="material-icons-sharp check-icon">check_circle</span>
                                    <?php else: ?>
                                        <span class="step-number">4</span>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h3>System Testing</h3>
                                    <p>Test system functionality and verify performance</p>
                                    <?php if ($data['installation']->step_3 && !$data['installation']->step_4): ?>
                                        <button class="btn-action" id="btn-complete-step-4" data-id="<?php echo $data['installation']->installation_id; ?>">
                                            <i class="material-icons-sharp">check</i> Mark as Completed
                                        </button>
                                    <?php elseif ($data['installation']->step_4): ?>
                                        <span class="completion-date">Completed: <?php echo isset($data['installation']->step_4_date) ? date('M d, Y', strtotime($data['installation']->step_4_date)) : date('M d, Y'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Installation Complete (only shown when completed) -->
                            <?php if ($data['installation']->status == 'completed'): ?>
                                <div class="progress-step completed" id="step-complete">
                                    <div class="step-indicator">
                                        <span class="material-icons-sharp check-icon">stars</span>
                                    </div>
                                    <div class="step-content">
                                        <h3>Installation Completed</h3>
                                        <p>All installation steps successfully completed</p>
                                        <span class="completion-date">Completed: <?php echo isset($data['installation']->completion_date) ? date('M d, Y', strtotime($data['installation']->completion_date)) : date('M d, Y'); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Notes and Documentation Card -->
                <div class="card notes-card">
                    <div class="card-header">
                        <h2>Installation Notes</h2>
                    </div>
                    <div class="card-body">
                        <form id="notes-form">
                            <input type="hidden" id="installation_id" value="<?php echo $data['installation']->installation_id; ?>">
                            <div class="form-group">
                                <label for="installation_notes">Add Notes About Installation Process</label>
                                <textarea id="installation_notes" rows="6" placeholder="Document important details, issues encountered, or special considerations about the installation..."><?php echo $data['installation']->notes ?? ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn-save-notes">
                                <i class="material-icons-sharp">save</i> Save Notes
                            </button>
                        </form>

                        <?php if (!empty($data['installation']->notes)): ?>
                            <div class="saved-notes">
                                <h3>Current Notes</h3>
                                <p><?php echo nl2br($data['installation']->notes); ?></p>
                                <span class="notes-timestamp">Last updated: <?php echo date('M d, Y g:i A', strtotime($data['installation']->updated_at)); ?></span>
                            </div>
                        <?php endif; ?>
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

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';

        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Start Installation button
            const startInstallationBtn = document.getElementById('btn-start-installation');
            if (startInstallationBtn) {
                startInstallationBtn.addEventListener('click', function() {
                    showConfirmationModal(
                        'Start Installation',
                        'Are you sure you want to start the installation process? This will update the installation status to "active".',
                        () => startInstallation(this.dataset.id)
                    );
                });
            }

            // Step 1 completion button
            const completeStep1Btn = document.getElementById('btn-complete-step-1');
            if (completeStep1Btn) {
                completeStep1Btn.addEventListener('click', function() {
                    showConfirmationModal(
                        'Complete Step 1',
                        'Confirm that you have completed mounting the panel structure?',
                        () => completeStep(this.dataset.id, 1)
                    );
                });
            }

            // Step 2 completion button
            const completeStep2Btn = document.getElementById('btn-complete-step-2');
            if (completeStep2Btn) {
                completeStep2Btn.addEventListener('click', function() {
                    showConfirmationModal(
                        'Complete Step 2',
                        'Confirm that you have completed placing and fixing solar panels?',
                        () => completeStep(this.dataset.id, 2)
                    );
                });
            }

            // Step 3 completion button
            const completeStep3Btn = document.getElementById('btn-complete-step-3');
            if (completeStep3Btn) {
                completeStep3Btn.addEventListener('click', function() {
                    showConfirmationModal(
                        'Complete Step 3',
                        'Confirm that you have completed electrical wiring?',
                        () => completeStep(this.dataset.id, 3)
                    );
                });
            }

            // Step 4 completion button
            const completeStep4Btn = document.getElementById('btn-complete-step-4');
            if (completeStep4Btn) {
                completeStep4Btn.addEventListener('click', function() {
                    showConfirmationModal(
                        'Complete Step 4',
                        'Confirm that you have completed system testing?',
                        () => completeStep(this.dataset.id, 4)
                    );
                });
            }

            // Notes form submission
            const notesForm = document.getElementById('notes-form');
            if (notesForm) {
                notesForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveNotes();
                });
            }

            // Function to show confirmation modal
            function showConfirmationModal(title, message, confirmCallback) {
                document.getElementById('modal-title').textContent = title;
                document.getElementById('modal-message').textContent = message;
                document.getElementById('modal-overlay').style.display = 'block';
                document.getElementById('confirmation-modal').style.display = 'block';

                // Cancel button event
                document.getElementById('modal-cancel').onclick = function() {
                    closeModal();
                };

                // Confirm button event
                document.getElementById('modal-confirm').onclick = function() {
                    confirmCallback();
                    closeModal();
                };
            }

            // Function to close the modal
            function closeModal() {
                document.getElementById('modal-overlay').style.display = 'none';
                document.getElementById('confirmation-modal').style.display = 'none';
            }

            // Function to start installation
            async function startInstallation(installationId) {
                try {
                    const response = await fetch(`${URLROOT}/engineer/startInstallation`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            installation_id: installationId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to start installation: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request');
                }
            }

            // Function to complete a step
            async function completeStep(installationId, stepNumber) {
                try {
                    const response = await fetch(`${URLROOT}/engineer/completeInstallationStep`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            installation_id: installationId,
                            step: stepNumber
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to complete step: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request');
                }
            }

            // Function to save installation notes
            async function saveNotes() {
                const installationId = document.getElementById('installation_id').value;
                const notes = document.getElementById('installation_notes').value;
                const saveButton = document.querySelector('.btn-save-notes');

                try {
                    // Show saving indicator
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<i class="material-icons-sharp">hourglass_empty</i> Saving...';

                    const response = await fetch(`${URLROOT}/engineer/saveInstallationNotes`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            installation_id: installationId,
                            notes: notes
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'success-message';
                        successMessage.innerHTML = '<i class="material-icons-sharp">check_circle</i> Notes saved successfully';
                        notesForm.appendChild(successMessage);

                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                    } else {
                        throw new Error(data.message || 'Failed to save notes');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving notes: ' + error.message);
                } finally {
                    // Reset button state
                    saveButton.disabled = false;
                    saveButton.innerHTML = '<i class="material-icons-sharp">save</i> Save Notes';
                }
            }
        });
    </script>

    <?php require APPROOT . '/views/engineer/footer.php'; ?>