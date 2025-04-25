<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/attendance.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/employee_attendance.css">
</head>

<body>
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
                    <p>HR Administrator</p>
                </div>
            </div>
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="attendance-header">
                <h1>Employee Monthly Attendance</h1>
                <div class="breadcrumb">
                    <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">Attendance</a> / 
                    <span>View Employee Attendance</span>
                </div>
            </div>

            <div class="employee-info-card">
                <div class="employee-details">
                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Employee Photo" class="employee-photo">
                    <div class="employee-data">
                        <h2><?php echo isset($data['employee']) ? $data['employee']->name : ''; ?></h2>
                        <p>Emp ID: <?php echo isset($data['employee']) ? $data['employee']->employee_id : ''; ?></p>
                        <p>Role: <?php echo isset($data['employee']) ? $data['employee']->role : ''; ?></p>
                    </div>
                </div>
                <div class="attendance-summary">
                    <div class="summary-item">
                        <span class="summary-value"><?php echo isset($data['summary']) ? $data['summary']->present : '0'; ?></span>
                        <span class="summary-label">Present</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-value"><?php echo isset($data['summary']) ? $data['summary']->absent : '0'; ?></span>
                        <span class="summary-label">Absent</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-value"><?php echo isset($data['summary']) ? $data['summary']->leave : '0'; ?></span>
                        <span class="summary-label">Leave</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-value"><?php echo isset($data['summary']) ? $data['summary']->late : '0'; ?></span>
                        <span class="summary-label">Late</span>
                    </div>
                </div>
            </div>

            <div class="date-selector">
                <form action="" method="GET" class="month-selector-form">
                    <input type="hidden" name="employee_id" value="<?php echo isset($_GET['employee_id']) ? $_GET['employee_id'] : ''; ?>">
                    <label for="month">Select Month:</label>
                    <select name="month" id="month" onchange="this.form.submit()">
                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
                        
                        for ($i = 1; $i <= 12; $i++) {
                            $selected = ($i == $currentMonth) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>{$months[$i-1]}</option>";
                        }
                        ?>
                    </select>
                    
                    <label for="year">Year:</label>
                    <select name="year" id="year" onchange="this.form.submit()">
                        <?php
                        $currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        $startYear = date('Y') - 2;
                        for ($i = $startYear; $i <= date('Y'); $i++) {
                            $selected = ($i == $currentYear) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>$i</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>

            <div class="attendance-table-container">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Working Hours</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['attendance']) && !empty($data['attendance'])): ?>
                            <?php foreach($data['attendance'] as $record): ?>
                                <tr class="<?php echo strtolower($record->status); ?>">
                                    <td><?php echo date('d M Y', strtotime($record->date)); ?></td>
                                    <td><?php echo date('l', strtotime($record->date)); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($record->status); ?>">
                                            <?php echo $record->status; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $record->clock_in ? date('h:i A', strtotime($record->clock_in)) : '-'; ?></td>
                                    <td><?php echo $record->clock_out ? date('h:i A', strtotime($record->clock_out)) : '-'; ?></td>
                                    <td><?php echo $record->working_hours ? $record->working_hours : '-'; ?></td>
                                    <td><?php echo $record->remarks ? $record->remarks : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-records">No attendance records found for the selected month.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <a href="<?php echo URLROOT ?>/hRAdministrator/attendance" class="btn btn-secondary">Back to Attendance</a>
                <button class="btn" onclick="printAttendance()">Print Attendance</button>
                <button class="btn btn-secondary" onclick="exportToPDF()">Export to PDF</button>
            </div>
        </div>
    </div>

    <script>
        function printAttendance() {
            window.print();
        }
        
        function exportToPDF() {
            // Implementation for PDF export functionality
            alert("Export to PDF functionality will be implemented here");
        }
    </script>

    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>