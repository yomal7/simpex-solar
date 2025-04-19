<?php require APPROOT.'/views/engineer/header.php';?>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/dashboard.css">

</head>

<body>
    
<div class="request-holiday-container">

<button class="menu-toggle" onclick="toggleSidebar()">☰</button>        
<div class="sidebar" id="sidebar">
    <img   
        src="<?php echo URLROOT ?>/assets/profile.png"
        alt="manager profile-picture"
        class="profile-picture"
    />
    <a href="<?php echo URLROOT?>/engineer/dashboard">
        <span class="material-icons-sharp">dashboard</span>
        <h3>Dashboard</h3>
    </a>
    <a href="<?php echo URLROOT ?>/engineer/siteVisits">
        <span class="material-icons-sharp">location_on</span>
        <h3>Site Visits</h3>
    </a>
    <a href="<?php echo URLROOT?>/engineer/tasks">
        <span class="material-icons-sharp">task</span>
        <h3>Tasks</h3>
    </a>
    <a href="<?php echo URLROOT?>/engineer/requestHoliday" class="active">
        <span class="material-icons-sharp">event</span>
        <h3>Request Holiday</h3>
    </a>
    <a href="<?php echo URLROOT?>/engineer/settings">
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
            <h2 class="request-holiday-form-title">Request Holiday Form</h2>
            <form id="requestHolidayForm" onsubmit="handleSubmit(event)">
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
                            <option value="sickLeave">Sick Leave</option>
                            <option value="casualLeave">Casual Leave</option>
                            <option value="maternityLeave">Maternity Leave</option>
                            <option value="paternityLeave">Paternity Leave</option>
                        </select>
                    </div>
                </div>
                <div class="request-holiday-form-group">
                    <label for="requestHolidayFormReason">Reason</label>
                    <textarea id="requestHolidayFormReason" name="reason" required></textarea>
                </div>
                <button type="submit" class="request-holiday-form-submit">Submit</button>
            </form>
        </section>

        <div class="request-holiday-table-container">
            <div class="request-holiday-table-header">
                <h2>Holiday Records</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Number of Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="holidayRecordsTableBody"></tbody>
            </table>
        </div>

    </div>
</div>
</div>


    <div class="overlay" id="overlay"></div>

</body>
    <script src="<?php echo URLROOT; ?>/js/engineer/requestHoliday.js"></script>