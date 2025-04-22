<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projects.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/holiday.css">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday" class="active">
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
            <div class="container">

                <div class="leaveRecords-table-container">
                    <div class="leaveRecords-table-header">
                        <h2>Leave Requests</h2>
                    </div>
                    <table>
                        <colgroup>
                            <col style="width: 15%;"> <!-- employee_id -->
                            <col style="width: 15%;"> <!-- leave_type -->
                            <col style="width: 20%;"> <!-- start_date -->
                            <col style="width: 15%;"> <!-- number_of_days -->
                            <col style="width: 20%;"> <!-- status -->
                            <col style="width: 15%;"> <!-- details -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>Number of Days</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="holidayRecordsTableBody">
                            <?php if (!empty($data['holidayRecords'])): ?>
                                <?php foreach ($data['holidayRecords'] as $record): ?>
                                    <tr>
                                        <td class="center-align"><span>EMP<?php echo str_pad($record->employee_id, 6, '0', STR_PAD_LEFT); ?></span></td>
                                        <td class="left-align"><?php echo $record->leave_type; ?></td>
                                        <td class="center-align"><?php echo $record->start_date; ?></td>
                                        <td class="center-align"><?php echo $record->number_of_days; ?></td>
                                        <td class="center-align">
                                            <span class="status-button <?php echo strtolower($record->status); ?>">
                                                <?php echo str_replace('_', ' ', ucfirst($record->status)); ?>
                                            </span>
                                        </td>
                                        <td class="center-align">
                                            <button class="icon-button view-details-btn" onclick="location.href='<?php echo URLROOT; ?>/hRAdministrator/details/<?php echo $record->id; ?>'" title="View Details"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No holiday records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($data['totalRecords'] > 1): ?>
                        <div class="pagination">
                            <?php if ($data['currentPage'] > 1): ?>
                                <a href="?page=<?php echo $data['currentPage'] - 1 ?>" class="page-link">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            <?php endif; ?>

                            <button class="page-info">
                                <?php echo $data['currentPage'] ?>
                            </button>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <a href="?page=<?php echo $data['currentPage'] + 1 ?>" class="page-link">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>


            </div>

        </div>

    </div>

    <div class="overlay" id="overlay"></div>


    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>