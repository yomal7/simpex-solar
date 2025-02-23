<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/siteVisit.css">
</head>

<body>
    <?php flash('site_visit_message'); ?>   
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />  
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePreProject/<?php echo $data['project']->pre_project_id; ?>" class="active">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <!-- <div class="container"> -->
            <div class="content">
                <div class="page-header">
                    <h2>Manage Site Visit</h2>
                    <nav class="breadcrumb">
                        <a href="<?php echo URLROOT; ?>/operationsCoordinator/preProjects">Pre Projects</a> /
                        <span>Site Visit Management</span>
                    </nav>
                </div>

                <div class="site-visit-container">
                    <!-- Project Info Card -->
                    <div class="card project-info">
                        <div class="card-header">
                            <h3>Project Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Customer Name:</label>
                                    <span><?php echo $data['project']->customer_name; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Contact:</label>
                                    <span><?php echo $data['project']->phone; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Location:</label>
                                    <span><?php echo $data['project']->nearest_city; ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Address:</label>
                                    <span><?php echo $data['project']->address; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Site Visit Management Card -->
                    <div class="card site-visit-management">
                        <div class="card-header">
                            <h3>Site Visit Details</h3>
                            <span class="status-badge <?php echo $data['site_visit']->status; ?>">
                                <?php echo ucfirst($data['site_visit']->status); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php if($data['site_visit']->status == 'pending'): ?>
                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/scheduleSiteVisit" method="POST" class="schedule-form">
                                    <input type="hidden" name="pre_project_id" value="<?php echo $data['project']->pre_project_id; ?>">
                                    <div class="form-group">
                                        <label>Select Visit Date</label>
                                        <input type="date" name="visit_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Select Visit Time</label>
                                        <input type="time" name="visit_time" required>
                                        <small>Site visits are scheduled between 8 AM and 4 PM</small>
                                    </div>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-calendar-check"></i> Schedule Visit
                                    </button>
                                </form>

                                <?php elseif($data['site_visit']->status == 'scheduled'): ?>
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/completeSiteVisit" method="POST">
                                        <input type="hidden" name="pre_project_id" value="<?php echo $data['project']->pre_project_id; ?>">
                                        <div class="form-group">
                                            <label>Site Visit Notes</label>
                                            <textarea name="site_notes" rows="4" required 
                                                placeholder="Enter site visit findings, requirements, and any special considerations..."></textarea>
                                        </div>
                                        <button type="submit" class="btn-success">
                                            <i class="fas fa-check-circle"></i> Mark as Completed
                                        </button>
                                    </form>

                                    <?php elseif($data['site_visit']->status == 'reschedule_requested'): ?>
                                        <div class="reschedule-details">
                                            <div class="reschedule-info">
                                                <h4>Reschedule Request Details</h4>
                                                <div class="detail-row">
                                                    <label>Customer's Note:</label>
                                                    <p><?php echo nl2br($data['site_visit']->reschedule_request); ?></p>
                                                </div>
                                            </div>

                                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/handleReschedule" method="POST" class="reschedule-form">
                                                <input type="hidden" name="visit_id" value="<?php echo $data['site_visit']->visit_id; ?>">
                                                <input type="hidden" name="pre_project_id" value="<?php echo $data['project']->pre_project_id; ?>">
                                                
                                                <div class="form-group">
                                                    <label>New Visit Date</label>
                                                    <input type="date" name="new_date" required min="<?php echo date('Y-m-d'); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>New Visit Time</label>
                                                    <input type="time" name="new_time" required>
                                                    <small>Site visits are scheduled between 8 AM and 4 PM</small>
                                                </div>
                                                <button type="submit" class="btn-primary">
                                                    <i class="fas fa-calendar-check"></i> Confirm New Schedule
                                                </button>
                                            </form>
                                        </div>
                            <?php elseif($data['site_visit']->status == 'scheduled' || $data['site_visit']->status == 'confirmed'): ?>
                                    <div class="scheduled-details">
                                        <!-- Show current schedule -->
                                        <div class="current-schedule">
                                            <h4>Current Schedule</h4>
                                            <div class="detail-row">
                                                <i class="fas fa-calendar"></i>
                                                <span>Date: <?php echo date('F j, Y', strtotime($data['site_visit']->visit_date)); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <i class="fas fa-clock"></i>
                                                <span>Time: <?php echo date('h:i A', strtotime($data['site_visit']->visit_time)); ?></span>
                                            </div>
                                        </div>

                                        <!-- Completion Form -->
                                        <form action="<?php echo URLROOT; ?>/operationsCoordinator/completeSiteVisit" method="POST" class="completion-form">
                                            <input type="hidden" name="pre_project_id" value="<?php echo $data['site_visit']->pre_project_id; ?>">
                                            
                                            <div class="form-group">
                                                <label>Site Visit Notes</label>
                                                <textarea name="site_notes" rows="4" required 
                                                    placeholder="Enter site visit findings, requirements, and any special considerations..."
                                                    class="form-control"></textarea>
                                            </div>

                                            <button type="submit" class="btn-success">
                                                <i class="fas fa-check-circle"></i> Complete Site Visit
                                            </button>
                                        </form>
                                    </div>

                                    <?php elseif($data['site_visit']->status == 'completed'): ?>
                                        <div class="completed-visit-details">
                                            <div class="schedule-info">
                                                <h4>Visit Details</h4>
                                                <div class="detail-row">
                                                    <i class="fas fa-calendar"></i>
                                                    <span>Date: <?php echo date('F j, Y', strtotime($data['site_visit']->visit_date)); ?></span>
                                                </div>
                                                <div class="detail-row">
                                                    <i class="fas fa-clock"></i>
                                                    <span>Time: <?php echo date('h:i A', strtotime($data['site_visit']->visit_time)); ?></span>
                                                </div>
                                            </div>

                                            <div class="visit-notes">
                                                <h4>Site Visit Notes</h4>
                                                <div class="notes-content">
                                                    <?php echo nl2br($data['site_visit']->site_notes); ?>
                                                </div>
                                            </div>
                                        </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- <div class="calendar-section">
                        <div class="card">
                            <div class="card-header">
                                <h3>Site Visit Calendar</h3>
                                <div class="calendar-nav">
                                    <button onclick="previousMonth()" class="btn-icon"><i class="fas fa-chevron-left"></i></button>
                                    <h4 id="currentMonth"></h4>
                                    <button onclick="nextMonth()" class="btn-icon"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="calendar-grid">
                                    <div class="calendar-days">
                                        <span>Sun</span>
                                        <span>Mon</span>
                                        <span>Tue</span>
                                        <span>Wed</span>
                                        <span>Thu</span>
                                        <span>Fri</span>
                                        <span>Sat</span>
                                    </div>
                                    <div id="calendarDates" class="calendar-dates"></div>
                                </div>

                                <div id="daySchedule" class="day-schedule">
                                    <h4>Selected Day Schedule</h4>
                                    <div class="schedule-list" id="scheduleList"></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
                
        </div>   

    </div>

    <div id="toast" class="toast"></div>

    <script>
        const preProjectId = <?php echo $data['project']->pre_project_id; ?>;
    </script>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/siteVisit.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>