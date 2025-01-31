<?php require APPROOT . '/views/technician/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- <link rel="stylesheet" href="</?php echo URLROOT; ?>/css/technician/dashboard.css"> -->

</head>

<body>
    <div class="projectTasks-container">

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
            <a href="<?php echo URLROOT ?>/technician/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
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

                <div class="projectTasks-table-container">
                    <div class="projectTasks-table-header">
                        <h2>Tasks</h2>
                    </div>
                    <table>
                        <colgroup>
                            <col style="width: 12%; text-align: center;"> <!-- Task ID -->
                            <col style="width: 12%; text-align: center;"> <!-- Project ID -->
                            <col style="width: 33%;"> <!-- Task -->
                            <col style="width: 20%; text-align: center;"> <!-- Due Date -->
                            <col style="width: 15%; text-align: center;"> <!-- Completion -->
                            <col style="width: 8%; text-align: center;"> <!-- Comment -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Project ID</th>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Completion</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="projectTasksTableBody">
                            <?php if (!empty($data['projectTasks'])): ?>
                                <?php foreach ($data['projectTasks'] as $task): ?>
                                    <tr>
                                        <td class="center-align">TSK<?php echo str_pad($task->id, 6, '0', STR_PAD_LEFT); ?></td>
                                        <td class="center-align">PRJ<?php echo str_pad($task->project_id, 6, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo $task->title; ?></td>
                                        <td class="center-align"><?php echo $task->end_date; ?></td>
                                        <td class="center-align">
                                            <button class="status-button <?php echo strtolower($task->status); ?>" onclick="openStatusPopup(<?php echo $task->id; ?>, '<?php echo $task->status; ?>')">
                                                <?php echo str_replace('_', ' ', ucfirst($task->status)); ?>
                                            </button>
                                        </td>
                                        <td class="center-align">
                                            <button class="icon-button view-details-btn" onclick="location.href='<?php echo URLROOT; ?>/technician/details/<?php echo $task->id; ?>'" title="View Details"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">No tasks found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($data['totalTasks'] > 1): ?>
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

    <div class="popup" id="statusPopup">
        <img src="<?php echo URLROOT ?>/assets/tick.png" alt="Success">
        <h2><span id="currentStatus"></span></h2>
        <div class="select-container">
        <select id="statusSelect">
            <option value="incomplete">Incomplete</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
        </div>
        <div class="popup-buttons">
            <button type="button" class="update-btn" onclick="updateStatus()">Update</button>
            <button type="button" class="cancel-btn" onclick="closePopup('statusPopup')">Cancel</button>
        </div>
    </div>

</body>
<script src="<?php echo URLROOT; ?>/js/technician/tasks.js"></script>