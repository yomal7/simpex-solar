<?php require APPROOT . '/views/technician/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="request-holiday-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/assets/profile.png"
                alt="technician profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/technician/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/requestHoliday" class="active">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/settings">
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
                <div class="holiday-details-container">
                    <div class="actions">
                        <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/technician/requestHoliday'" class="back-btn">
                            Back to Leave Requests
                        </button>
                    </div>
                    
                    <div class="details">
                        <h2>Leave Request Details</h2>
                        <?php if (isset($data['record']) && $data['record']): ?>
                            <div class="holiday-info">
                                <div class="info-group">
                                    <label>Leave Type:</label>
                                    <span><?php echo $data['record']->leave_type; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Start Date:</label>
                                    <span><?php echo $data['record']->start_date; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>End Date:</label>
                                    <span><?php echo $data['record']->end_date; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Number of Days:</label>
                                    <span><?php echo $data['record']->number_of_days; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Reason:</label>
                                    <span><?php echo $data['record']->reason; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Status:</label>
                                    <span class="status-badge <?php echo strtolower($data['record']->status); ?>">
                                        <?php echo ucfirst($data['record']->status); ?>
                                    </span>
                                </div>
                            </div>
                    </div>
                    
                    <div class="comments">
                        <div class="comments-section">
                            <h3>Comments</h3>
                            <div id="commentsList">
                                <?php if (!empty($data['record']->comment)): ?>
                                    <div class="comment">
                                        <p><?php echo $data['record']->comment; ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="comment">
                                        <p class="no-comments">No comments.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <div class="alert alert-danger">
                            Leave request not found
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/technician/requestHoliday.js"></script>
    <?php require APPROOT . '/views/technician/footer.php'; ?>