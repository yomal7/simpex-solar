<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/attendance.css">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance" class="active">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/payroll">
                <span class="material-icons-sharp">money</span>
                <h3>Payroll</h3>
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
            <!-- <a href="<?php echo URLROOT; ?>/operationsCoordinator/addTask"><button class="new-employee-btn" data-toggle="modal" data-target="#myModal">Select Date</button></a> -->

            <div class="date-selector-container">
                <form method="POST" action="<?php echo URLROOT; ?>/clerk/viewAttendance" class="date-form">
                    <label for="attendance_date">Select Date:</label>
                    <input type="date" id="attendance_date" name="attendance_date" value="<?php echo $data['date']; ?>">
                    <button type="submit" class="view-date-btn">View Attendance</button>
                </form>
                <?php if (isset($data['date'])): ?>
                    <div class="current-date-display">
                        Showing attendance for: <strong><?php echo date('F j, Y', strtotime($data['date'])); ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <!-- /* Attendance table */ -->
            <div class="table-section">

                <table class="project-table">
                    <thead>
                        <tr>
                            <th>Emp. ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($data['attendanceRecords'] as $attendanceRecord): ?>
                            <tr>
                                <td><?php echo $attendanceRecord->employee_id; ?></td>
                                <td><?php echo $attendanceRecord->name; ?></td>
                                <td><?php echo $attendanceRecord->emp_role; ?></td>
                                <td><span class="attendance-status <?php echo strtolower($attendanceRecord->status); ?>"><?php echo $attendanceRecord->status; ?></span></td>
                                <td><?php echo $attendanceRecord->time_in; ?></td>
                                <td><?php echo $attendanceRecord->time_out; ?></td>
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

    <!-- <div class="overlay" id="overlay"></div> -->
    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>