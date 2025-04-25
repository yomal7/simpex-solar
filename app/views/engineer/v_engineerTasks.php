<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/dashboard.css">

</head>

<body>
    <div class="tasks-container">

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
                    <p>Engineer</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits">
                <span class="material-icons-sharp">location_on</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/settings">
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

                <div class="tasks-table-container">
                    <div class="tasks-table-header">
                        <h2>Tasks</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Date</th>
                                <th>Details</th>
                                <th>Confirmation</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody id="tasksTableBody"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="popup" id="confirmationPopup">
        <img src="<?php echo URLROOT ?>/assets/tick.png" alt="Success">
        <h2>Change Confirmation</h2>
        <p>Select the new confirmation status:</p>
        <select id="confirmationSelect">
            <option value="confirmed">Confirmed</option>
            <option value="not-confirmed">Not Confirmed</option>
        </select>
        <button type="button" onclick="updateConfirmationChange()">Update</button>
    </div>

    <div class="popup" id="addCommentPopup">
        <img src="<?php echo URLROOT ?>/assets/addComment.png" alt="addComment">
        <h2>Comment</h2>
        <textarea id="addCommentText" name="comment" rows="1" oninput="autoResize(this)"></textarea>
        <button type="button" onclick="confirmAddComment()">Add</button>
    </div>

    <div class="popup" id="viewPopup">
        <img src="<?php echo URLROOT ?>/assets/view.png" alt="view">
        <h2>Details</h2>
        <p>Delivery ID: <span id="viewDeliveryID"></span></p>
        <p>Date: <span id="viewDate"></span></p>
        <p>Address: <span id="viewAddress"></span></p>
        <p>Phone Number: <span id="viewPhoneNumber"></span></p>
        <button type="button" onclick="confirmView()">Done</button>
    </div>


    <script src="<?php echo URLROOT; ?>/js/engineer/tasks.js"></script>

<?php require APPROOT.'/views/engineer/footer.php';?>
