<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/tasks.css">
</head>

<body data-user-role="operationsCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
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

                <a href="<?php echo URLROOT; ?>/operationsCoordinator/addTask"><button class="new-task-btn" data-toggle="modal" data-target="#myModal">Assign New Task</button></a>
                <!-- <div class="card-container">
                    <div class="card" id="total-projects">

                    </div>
                </div> -->


                <!-- /* Task table */ -->
                <div class="table-section">

                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Task Title</th>
                                <th>Assigned To</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($data['tasks'] as $task): ?>
                                <tr>
                                    <td><?php echo $task->id; ?></td>
                                    <td><?php echo $task->title; ?></td>
                                    <td><?php echo $task->employee_name; ?></td>
                                    <td><?php echo $task->start_date; ?></td>
                                    <td><?php echo $task->end_date; ?></td>
                                    <td><span class="task-status <?php echo strtolower($task->status); ?>"><?php echo $task->status; ?></span></td>
                                    <td><a href="<?php echo URLROOT; ?>/operationsCoordinator/viewTask/<?php echo $task->id; ?>"><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </div>
                </div>








            </div>
        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->




    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>