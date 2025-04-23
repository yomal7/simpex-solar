<?php require APPROOT . '/views/technician/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/technician/projects.css">

</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/public/assets/profile.png"
                alt="technician profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/technician/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/projects" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <h1>My Installation Projects</h1>
                </div>
                <div class="project-cards">

                    <?php if (isset($data['message'])): ?>
                        <div class="alert alert-info"><?php echo $data['message']; ?></div>
                    <?php else: ?>
                        <?php if (empty($data['projects'])): ?>
                            <div class="alert alert-info">No projects found.</div>
                        <?php else: ?>
                            <div class="project-grid">
                                <?php foreach ($data['projects'] as $project): ?>
                                    <div class="project-header">
                                        <h4>Project #<?php echo $project->project_id; ?></h4>
                                    </div>
                                    <div class="project-details">
                                        <div class="detail-item">
                                            <i class="fas fa-user"></i>
                                            <p><?php echo isset($project->customer) ? $project->customer->name : 'No customer data'; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p><?php echo isset($project->customer) && isset($project->customer->nearest_city) ? $project->customer->nearest_city : 'No location data'; ?></p>
                                        </div>
                                    </div>
                                    <div class="project-actions">
                                        <a href="<?php echo URLROOT; ?>/technician/viewInstallation/<?php echo $project->installation_id; ?>" class="btn btn-primary">View Details</a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>


            </div>
        </div>
    </div>

    <script>
        // // Filter projects by status
        // document.querySelectorAll('.filter-btn').forEach(button => {
        //     button.addEventListener('click', () => {
        //         // Get status from data attribute
        //         const status = button.getAttribute('data-status');

        //         // Hide all project containers
        //         document.querySelectorAll('.projects-container').forEach(container => {
        //             container.style.display = 'none';
        //         });

        //         // Show selected container
        //         document.getElementById(status + '-projects').style.display = 'block';

        //         // Update active class on buttons
        //         document.querySelectorAll('.filter-btn').forEach(btn => {
        //             btn.classList.remove('active');
        //         });
        //         button.classList.add('active');
        //     });
        // });

        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/technician/footer.php'; ?>