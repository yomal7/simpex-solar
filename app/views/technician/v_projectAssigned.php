<?php require APPROOT . '/views/technician/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/technician/project.css">

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
                <div class="page-header">
                    <h1>Installation Details</h1>
                    <a href="<?php echo URLROOT; ?>/technician/projects" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Projects
                    </a>
                </div>

                <div class="project-details-card">
                    <div class="section">
                        <h2>Project Information</h2>
                        <div class="detail-row">
                            <div class="detail-label">Project #<?php echo $data['project']->project_id; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Start Date:</div>
                            <div class="detail-value"><?php echo isset($data['installation']->start_date) ? date('F j, Y', strtotime($data['installation']->start_date)) : 'Not set'; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">End Date:</div>
                            <div class="detail-value"><?php echo isset($data['installation']->end_date) ? date('F j, Y', strtotime($data['installation']->end_date)) : 'Not set'; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="status-badge status-<?php echo strtolower($data['project']->status); ?>"><?php echo $data['project']->status; ?></span></div>
                        </div>
                    </div>

                    <div class="section">
                        <h2>Customer Information</h2>
                        <div class="detail-row">
                            <div class="detail-label">Name:</div>
                            <div class="detail-value"><?php echo $data['customer']->name; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address:</div>
                            <div class="detail-value">
                                <?php echo isset($data['customer']->address) ? $data['customer']->address : ''; ?>
                                <?php if (isset($data['customer']->nearest_city)): ?>
                                    <br><?php echo $data['customer']->nearest_city; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <h2>Assigned Engineer</h2>
                        <?php if (!$data['engineers']): ?>
                            <p>No engineer assigned to this project yet.</p>
                        <?php else: ?>
                            <div class="engineers-list">
                                <div class="engineer-card">
                                    <div class="engineer-name"><?php echo $data['engineers']->name; ?></div>
                                    <div class="engineer-contact">
                                        <div><i class="fas fa-phone"></i> <?php echo $data['engineers']->phone; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Add this after the Assigned Engineer section -->
                    <div class="section">
                        <h2>Installation Team Members</h2>
                        <?php if (empty($data['members'])): ?>
                            <p>No team members assigned to this installation yet.</p>
                        <?php else: ?>
                            <div class="team-members-list">
                                <?php foreach ($data['members'] as $member): ?>
                                    <div class="team-member-card">
                                        <div class="member-name"><?php echo $member->name; ?></div>
                                        <div class="member-contact">
                                            <div><i class="fas fa-phone"></i> <?php echo $member->phone; ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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



    <?php require APPROOT . '/views/technician/footer.php'; ?>