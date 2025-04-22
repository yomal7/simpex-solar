<?php require APPROOT . '/views/technician/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- <link rel="stylesheet" href="</?php echo URLROOT; ?>/css/technician/dashboard.css"> -->

</head>

<body>
    <div class="dashboard-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/technician/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/requestHoliday">
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

                <section class="dashboard-cards">
                    <?php if (isset($data['tasks']) && is_array($data['tasks'])): ?>
                        <?php foreach ($data['tasks'] as $task): ?>
                            <div class="card" onclick="window.location='<?php echo URLROOT; ?>/technician/details/<?php echo $task->id; ?>';">
                                <div class="card-header">
                                    <i class="fas fa-tasks"></i>
                                    <h2 class="card-title">TSK<?php echo str_pad($task->id, 6, '0', STR_PAD_LEFT); ?></h2>

                                    <div class="card-status-button <?php echo strtolower($task->status); ?>">
                                        <?php echo str_replace('_', ' ', ucfirst($task->status)); ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h2>PRJ<?php echo str_pad($task->project_id, 6, '0', STR_PAD_LEFT); ?></h2>

                                    <h4><?php echo strlen($task->title) > 60 ? substr($task->title, 0, 60) . '...' : $task->title; ?></h4>

                                </div>
                                <div class="days-indicator <?php
                                                            $endDate = new DateTime($task->end_date);
                                                            $today = new DateTime();
                                                            $interval = $today->diff($endDate);
                                                            echo $interval->invert ? 'overdue' : '';
                                                            ?>">
                                    <?php
                                    if ($interval->invert) {
                                        echo '<span class="days-status overdue">' . $interval->days . 'ds overdue</span>';
                                    } else {
                                        echo '<span class="days-status remaining">' . $interval->days . 'ds remaining</span>';
                                    }
                                    ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>


            </div>
        </div>
    </div>

    <div class="överlay" id="overlay"></div>


<script src="<?php echo URLROOT; ?>/js/technician/dashboard.js"></script>

<?php require APPROOT . '/views/technician/footer.php'; ?>