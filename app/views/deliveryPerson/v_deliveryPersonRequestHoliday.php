<?php require APPROOT . '/views/deliveryPerson/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- <link rel="stylesheet" href="<//?php echo URLROOT; ?>/css/deliveryPerson/requestHoliday.css"> -->

</head>

<body>

    <div class="request-holiday-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Delivery Person</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/deliveryPerson/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/orders">
                <span class="material-icons-sharp">local_shipping</span>
                <h3>Orders</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/requestHoliday" class="active">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/settings">
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

                <section class="request-holiday-form-container">
                    <h2 class="request-holiday-form-title">Request Leave Form</h2>
                    <form id="requestHolidayForm">
                        <div class="request-form-grid">
                            <div class="request-holiday-form-group">
                                <label for="requestHolidayFormStartDate">Start Date</label>
                                <input type="date" id="requestHolidayFormStartDate" name="startDate" required>
                            </div>
                            <div class="request-holiday-form-group">
                                <label for="requestHolidayFormEndDate">End Date</label>
                                <input type="date" id="requestHolidayFormEndDate" name="endDate" required>
                            </div>
                            <div class="request-holiday-form-group">
                                <label for="requestHolidayFormLeaveType">Leave Type</label>
                                <select id="requestHolidayFormLeaveType" name="leaveType" required>
                                    <option value="">Select type</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Annual Leave">Annual Leave</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="request-holiday-form-group">
                            <label for="requestHolidayFormReason">Reason</label>
                            <textarea id="requestHolidayFormReason" name="reason" rows="1" maxlength="75" required></textarea>
                        </div>
                        <button type="reset" class="request-holiday-form-cancel">Cancel</button>
                        <button type="submit" class="request-holiday-form-submit">Submit</button>
                    </form>
                </section>

                <div class="request-holiday-table-container">
                    <div class="request-holiday-table-header">
                        <h2>Leave Records</h2>
                    </div>
                    <table>
                        <colgroup>
                            <col style="width: 12%;"> <!-- leave_type -->
                            <col style="width: 12%;"> <!-- start_date -->
                            <col style="width: 12%;"> <!-- end_date -->
                            <col style="width: 10%;"> <!-- number_of_days -->
                            <col style="width: 35%;"> <!-- reason -->
                            <col style="width: 14%;"> <!-- status -->
                            <col style="width: 5%;"> <!-- details button -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Number of Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="holidayRecordsTableBody">
                            <?php if (!empty($data['holidayRecords'])): ?>
                                <?php foreach ($data['holidayRecords'] as $record): ?>
                                    <tr>
                                        <td class="leave-type"><?php echo $record->leave_type; ?></td>
                                        <td><?php echo $record->start_date; ?></td>
                                        <td><?php echo $record->end_date; ?></td>
                                        <td class="number-of-days"><?php echo $record->number_of_days; ?></td>
                                        <td class="reason"><?php echo $record->reason; ?></td>
                                        <td class="center-align"><span class="<?php echo strtolower($record->status); ?>"><?php echo ucfirst($record->status); ?></span></td>
                                        <td class="center-align">
                                                <button class="icon-button view-details-btn" onclick="location.href='<?php echo URLROOT; ?>/deliveryPerson/holidayDetails/<?php echo $record->id; ?>'" title="View Details"><i class="fas fa-eye"></i></button>
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


    <script>
        // Assuming you have the employee ID from the PHP session
        const employeeId = <?php echo $data['employee']->employee_id ?? 0; ?>;
    </script>
    <script src="<?php echo URLROOT; ?>/js/deliveryPerson/requestHoliday.js"></script>

    <?php require APPROOT . '/views/deliveryPerson/footer.php'; ?>