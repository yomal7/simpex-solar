<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/engineerApproval.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />

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
                <!-- Header Section -->
                <div class="section-header">
                    <h2>Engineer Approval</h2>
                    <div class="project-meta">
                        <span class="project-id">Project ID: <?php echo $data['project']->project_id; ?></span>
                        <span class="customer-name"><?php echo $data['customer']->customer_name; ?></span>
                    </div>
                </div>

                <?php flash('engineer_approval_message'); ?>

                <!-- Customer & Project Information -->
                <div class="info-section">
                    <div class="info-card">
                        <div class="card-header">
                            <h3>Customer Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Customer Name</span>
                                    <span class="value"><?php echo $data['customer']->customer_name; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Email</span>
                                    <span class="value"><?php echo $data['customer']->email; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Phone</span>
                                    <span class="value"><?php echo $data['customer']->phone; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Address</span>
                                    <span class="value"><?php echo $data['customer']->address ?? $data['customer']->location; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="card-header">
                            <h3>System Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Package</span>
                                    <span class="value"><?php echo $data['project']->package_name ?? 'Not specified'; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">System Capacity</span>
                                    <span class="value"><?php echo $data['project']->system_capacity ?? 'Not specified'; ?> kW</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Installation Date</span>
                                    <span class="value"><?php echo isset($certificate->installation_completed_at) ? date('M d, Y', strtotime($certificate->installation_completed_at)) : 'Not completed yet'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Engineer Approval Status -->
                <div class="approval-section">
                    <div class="approval-card">
                        <div class="card-header">
                            <h3>Engineer Approval Status</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!$data['certificate']): ?>
                                <!-- No engineer assigned yet -->
                                <div class="assign-engineer">
                                    <h4>Assign Engineer for Approval</h4>
                                    <p>No engineer has been assigned to review and certify this installation. Please select an engineer to proceed.</p>

                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/assignEngineerToApproval" method="POST" class="assign-form">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                                        <div class="form-group">
                                            <label for="engineer_id">Select Engineer:</label>
                                            <select name="engineer_id" id="engineer_id" class="form-control" required>
                                                <option value="">-- Select Engineer --</option>
                                                <?php foreach ($data['engineers'] as $engineer): ?>
                                                    <option value="<?php echo $engineer->employee_id; ?>">
                                                        <?php echo $engineer->name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Assign Engineer</button>
                                    </form>
                                </div>
                            <?php elseif ($data['certificate'] && $data['certificate']->completed_at === null): ?>
                                <!-- Engineer assigned but not completed -->
                                <div class="approval-pending">
                                    <div class="status-icon pending">
                                        <span class="material-icons-sharp">pending</span>
                                    </div>
                                    <h4>Approval In Progress</h4>
                                    <p>An engineer has been assigned to review and certify this installation.</p>

                                    <div class="engineer-info">
                                        <div class="info-row">
                                            <span class="label">Assigned Engineer:</span>
                                            <span class="value"><?php echo $data['certificate']->engineer_name; ?></span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Assigned Date:</span>
                                            <span class="value"><?php echo date('F j, Y', strtotime($data['certificate']->created_at)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Status:</span>
                                            <span class="value status-badge pending">Awaiting Certification</span>
                                        </div>
                                    </div>

                                    <div class="note-box">
                                        <span class="material-icons-sharp">info</span>
                                        <p>The assigned engineer needs to upload the required certification documents. You will be notified once the process is complete.</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Engineer has completed certification -->
                                <div class="approval-complete">
                                    <div class="status-icon completed">
                                        <span class="material-icons-sharp">check_circle</span>
                                    </div>
                                    <h4>Certification Complete</h4>
                                    <p>The engineer has completed the review and certification process.</p>

                                    <div class="engineer-info">
                                        <div class="info-row">
                                            <span class="label">Certified By:</span>
                                            <span class="value"><?php echo $data['certificate']->engineer_name; ?></span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Certified On:</span>
                                            <span class="value"><?php echo date('F j, Y', strtotime($data['certificate']->completed_at)); ?></span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Status:</span>
                                            <span class="value status-badge completed">Certification Complete</span>
                                        </div>
                                    </div>

                                    <!-- Installation Images -->
                                    <div class="installation-images">
                                        <h5>Installation Photos</h5>
                                        <div class="image-grid">
                                            <div class="image-item">
                                                <img src="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_1; ?>" alt="Installation Image 1">
                                                <span class="image-label">Installation Image 1</span>
                                            </div>

                                            <?php if ($data['certificate']->installation_image_2): ?>
                                                <div class="image-item">
                                                    <img src="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_2; ?>" alt="Installation Image 2">
                                                    <span class="image-label">Installation Image 2</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Certification Documents -->
                                    <div class="certification-documents">
                                        <h5>Certification Documents</h5>
                                        <div class="document-grid">
                                            <div class="document-item">
                                                <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_certificate_1; ?>" target="_blank" class="document-link">
                                                    <span class="material-icons-sharp">description</span>
                                                    <span class="document-label">Installation Certificate 1</span>
                                                    <span class="view-btn">View</span>
                                                </a>
                                            </div>

                                            <?php if ($data['certificate']->installation_certificate_2): ?>
                                                <div class="document-item">
                                                    <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_certificate_2; ?>" target="_blank" class="document-link">
                                                        <span class="material-icons-sharp">description</span>
                                                        <span class="document-label">Installation Certificate 2</span>
                                                        <span class="view-btn">View</span>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Complete Approval Button -->
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/completeEngineerApproval" method="POST" class="complete-form">
                                        <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                                        <button type="submit" class="btn btn-success">
                                            <span class="material-icons-sharp">check</span>
                                            Complete Approval & Move to Grid Connection
                                        </button>
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
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>