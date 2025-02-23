<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/employees.css">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="active">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
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

                <a href="<?php echo URLROOT; ?>/hRAdministrator/addEmployee"><button class="new-employee-btn">Add New Employee</button></a>
                <!-- <div class="card-container">
                    <div class="card" id="total-projects">

                    </div>
                </div> -->


                <!-- /* Employee table */ -->
                <div class="table-section">

                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Emp. ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($data['employees'] as $employee): ?>
                                <tr>
                                    <td><?php echo $employee->employee_id; ?></td>
                                    <td><?php echo $employee->name; ?></td>
                                    <td><?php echo $employee->email; ?></td>
                                    <td><?php echo $employee->phone; ?></td>
                                    <td><?php echo $employee->role; ?></td>
                                    <td><a href="<?php echo URLROOT; ?>/hRAdministrator/viewEmployee/<?php echo $employee->employee_id; ?>"><button class="view-btn" onclick="">View</button></a></td>
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




    <script src="<?php echo URLROOT; ?>/js/hRAdministrator/dashboard.js"></script>
    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>