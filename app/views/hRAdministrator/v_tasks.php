<?php require APPROOT.'/views/operationsCoordinator/header.php';?>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/tasks.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img

                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
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
            <a href="#">
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
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
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
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>