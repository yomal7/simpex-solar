<?php require APPROOT . '/views/engineer/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/approvalProjects.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="engineer profile-picture"
                class="profile-picture" />

            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits">
                <span class="material-icons-sharp">home</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/projects">
                <span class="material-icons-sharp">engineering</span>
                <h3>Installations</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/approvalProjects" class="active">
                <span class="material-icons-sharp">fact_check</span>
                <h3>Approvals</h3>
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
                    <h1>Certification Projects</h1>
                </div>

                <?php flash('approval_message'); ?>

                <div class="projects-container">
                    <?php if (empty($data['projects'])): ?>
                        <div class="no-projects">
                            <div class="no-data-icon">
                                <span class="material-icons-sharp">fact_check</span>
                            </div>
                            <h3>No Certification Projects</h3>
                            <p>You have no projects assigned for certification at this moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="projects-grid">
                            <?php foreach ($data['projects'] as $project): ?>
                                <div class="project-card <?php echo $project->completed_at ? 'completed' : 'pending'; ?>">
                                    <div class="card-header">
                                        <h3><?php echo $project->customer_name; ?></h3>
                                        <span class="status-badge <?php echo $project->completed_at ? 'completed' : 'pending'; ?>">
                                            <?php echo $project->completed_at ? 'Completed' : 'Pending'; ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="project-details">
                                            <div class="detail-row">
                                                <span class="label">Project ID:</span>
                                                <span class="value"><?php echo $project->project_id; ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Location:</span>
                                                <span class="value"><?php echo $project->location ?? 'Not specified'; ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">System Capacity:</span>
                                                <span class="value"><?php echo $project->system_capacity ?? 'Not specified'; ?> kW</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Assigned Date:</span>
                                                <span class="value"><?php echo date('M j, Y', strtotime($project->created_at)); ?></span>
                                            </div>
                                            <?php if ($project->completed_at): ?>
                                                <div class="detail-row">
                                                    <span class="label">Completed Date:</span>
                                                    <span class="value"><?php echo date('M j, Y', strtotime($project->completed_at)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-actions">
                                        <?php if ($project->completed_at): ?>
                                            <a href="<?php echo URLROOT; ?>/engineer/projectCertification/<?php echo $project->project_id; ?>" class="btn btn-secondary">
                                                <span class="material-icons-sharp">visibility</span>
                                                View Details
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo URLROOT; ?>/engineer/projectCertification/<?php echo $project->project_id; ?>" class="btn btn-primary">
                                                <span class="material-icons-sharp">fact_check</span>
                                                Submit Certification
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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