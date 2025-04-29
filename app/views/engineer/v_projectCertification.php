<?php require APPROOT . '/views/engineer/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/projectCertification.css">
</head>

<body>
    <div class="dashboard-container">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Engineer</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits">
                <span class="material-icons-sharp">home</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/approvalProjects" class="active">
                <span class="material-icons-sharp">fact_check</span>
                <h3>Approvals</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <a href="<?php echo URLROOT ?>/engineer/approvalProjects" class="back-link">
                        <span class="material-icons-sharp">arrow_back</span> Back to Approvals
                    </a>
                    <h1>Project Certification</h1>
                </div>

                <?php flash('certification_message'); ?>

                <div class="certification-container">
                    <!-- Project Info Card -->
                    <div class="info-card">
                        <div class="card-header">
                            <h2>Project Information</h2>
                            <span class="status-badge <?php echo $data['certificate']->completed_at ? 'completed' : 'pending'; ?>">
                                <?php echo $data['certificate']->completed_at ? 'Completed' : 'Pending Certification'; ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Customer Name</label>
                                    <p><?php echo $data['customer']->customer_name; ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Phone</label>
                                    <p><?php echo $data['customer']->phone; ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <p><?php echo $data['customer']->email; ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Location</label>
                                    <p><?php echo $data['customer']->address ?? 'Not specified'; ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Project ID</label>
                                    <p><?php echo $data['project']->project_id; ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Current Phase</label>
                                    <p><?php echo ucfirst(str_replace('_', ' ', $data['project']->current_phase)); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($data['certificate']->completed_at): ?>
                        <!-- Completed Certification View -->
                        <div class="certificate-card">
                            <div class="card-header">
                                <h2>Submitted Certificates</h2>
                                <span class="completion-date">
                                    <span class="material-icons-sharp">event</span>
                                    Completed on <?php echo date('M d, Y', strtotime($data['certificate']->completed_at)); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="certificate-files">
                                    <h3>Certificate Files</h3>
                                    <div class="files-grid">
                                        <div class="file-item">
                                            <div class="file-icon pdf">
                                                <span class="material-icons-sharp">description</span>
                                            </div>
                                            <div class="file-details">
                                                <h4>Installation Certificate 1</h4>
                                                <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_certificate_1; ?>" target="_blank" class="view-btn">
                                                    <span class="material-icons-sharp">visibility</span> View
                                                </a>
                                            </div>
                                        </div>

                                        <?php if ($data['certificate']->installation_certificate_2): ?>
                                            <div class="file-item">
                                                <div class="file-icon pdf">
                                                    <span class="material-icons-sharp">description</span>
                                                </div>
                                                <div class="file-details">
                                                    <h4>Installation Certificate 2</h4>
                                                    <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_certificate_2; ?>" target="_blank" class="view-btn">
                                                        <span class="material-icons-sharp">visibility</span> View
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="installation-images">
                                    <h3>Installation Photos</h3>
                                    <div class="image-grid">
                                        <div class="image-item">
                                            <img src="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_1; ?>" alt="Installation Image 1">
                                            <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_1; ?>" target="_blank" class="view-full">
                                                <span class="material-icons-sharp">fullscreen</span>
                                            </a>
                                        </div>

                                        <?php if ($data['certificate']->installation_image_2): ?>
                                            <div class="image-item">
                                                <img src="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_2; ?>" alt="Installation Image 2">
                                                <a href="<?php echo URLROOT; ?>/public/uploads/certificates/<?php echo $data['certificate']->installation_image_2; ?>" target="_blank" class="view-full">
                                                    <span class="material-icons-sharp">fullscreen</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Certificate Upload Form -->
                        <div class="certificate-card">
                            <div class="card-header">
                                <h2>Submit Certificates</h2>
                            </div>
                            <div class="card-body">
                                <p class="instruction">Please upload the installation certificates and photos of the completed solar installation.</p>

                                <form action="<?php echo URLROOT; ?>/engineer/submitCertificates" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">

                                    <div class="form-group">
                                        <label for="installation_certificate_1">Installation Certificate 1 <span class="required">*</span></label>
                                        <div class="file-input-container">
                                            <input type="file" name="installation_certificate_1" id="installation_certificate_1" accept=".pdf" required>
                                            <div class="file-input-info">
                                                <p class="file-format">PDF Document</p>
                                                <p class="file-limit">Max size: 5MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="installation_certificate_2">Installation Certificate 2 (Optional)</label>
                                        <div class="file-input-container">
                                            <input type="file" name="installation_certificate_2" id="installation_certificate_2" accept=".pdf">
                                            <div class="file-input-info">
                                                <p class="file-format">PDF Document</p>
                                                <p class="file-limit">Max size: 5MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="installation_image_1">Installation Image 1 <span class="required">*</span></label>
                                        <div class="file-input-container">
                                            <input type="file" name="installation_image_1" id="installation_image_1" accept=".jpg,.jpeg,.png" required>
                                            <div class="file-input-info">
                                                <p class="file-format">JPG, JPEG, or PNG</p>
                                                <p class="file-limit">Max size: 5MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="installation_image_2">Installation Image 2 (Optional)</label>
                                        <div class="file-input-container">
                                            <input type="file" name="installation_image_2" id="installation_image_2" accept=".jpg,.jpeg,.png">
                                            <div class="file-input-info">
                                                <p class="file-format">JPG, JPEG, or PNG</p>
                                                <p class="file-limit">Max size: 5MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="material-icons-sharp">upload_file</span> Submit Certificates
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/engineer/footer.php'; ?>