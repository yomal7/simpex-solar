<?php require APPROOT . '/views/clerk/header.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/clerk/attendance.css">

</head>

<body>
    <div class="dashboard-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/clerk/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/attendance" class="active">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">

            <a href="<?php echo URLROOT; ?>/clerk/viewAttendance"><button class="new-employee-btn">View Attendance History</button></a>
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
                            <th>Mark</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($data['attendanceRecords'] as $attendanceRecord): ?>
                            <tr>
                                <td class="employee-id"><?php echo $attendanceRecord->employee_id; ?></td>
                                <td class="employee-name"><?php echo $attendanceRecord->name; ?></td>
                                <td class="employee-role"><?php echo $attendanceRecord->emp_role; ?></td>
                                <td class="status-cell"><span class="attendance-status <?php echo strtolower($attendanceRecord->status); ?>"><?php echo $attendanceRecord->status; ?></span></td>
                                <td class="time-in-cell"><?php echo $attendanceRecord->time_in; ?></td>
                                <td class="time-out-cell"><?php echo $attendanceRecord->time_out; ?></td>
                                <td class="action-cell">
                                    <?php if (!empty($attendanceRecord->time_in)): ?>
                                        <?php if (!empty($attendanceRecord->time_out)): ?>
                                            <button
                                                class="clock-out-btn" disabled>
                                                Completed
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="clock-out-btn"
                                                data-employee-id="<?php echo $attendanceRecord->employee_id; ?>"
                                                data-date="<?php echo $attendanceRecord->date; ?>"
                                                onclick="clockOut(this)">
                                                Clock Out
                                            </button>
                                        <?php endif; ?>
                                    <?php elseif (empty($attendanceRecord->time_in)): ?>
                                        <button
                                            class="clock-in-btn"
                                            data-employee-id="<?php echo $attendanceRecord->employee_id; ?>"
                                            data-date="<?php echo date('Y-m-d'); ?>"
                                            onclick="clockIn(this)">
                                            Clock In
                                        </button>
                                    <?php else: ?>
                                        <button class="clock-in-btn" disabled>Clock In</button>
                                    <?php endif; ?>
                                </td>

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
    <!-- <div class="överlay" id="overlay"></div> -->

    <script src="<?php echo URLROOT; ?>/js/clerk/attendance.js"></script>

    <?php require APPROOT . '/views/clerk/footer.php'; ?>