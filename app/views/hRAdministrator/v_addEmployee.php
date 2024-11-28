<?php require APPROOT.'/views/operationsCoordinator/header.php';?>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/employees.css">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
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

            <h2>Assign New Task</h2>
        <form action="<?php echo URLROOT; ?>/operationsCoordinator/addTask" method="POST" id="userForm" id="addUserForm" onsubmit="handleSubmit(event)">
            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Task Title</label>
                    <input type="text" name="title" id="title" placeholder="Task Title" value="<?php $data['title'];?>">
                    <span class="form-invalid"><?php echo isset($data['title_err']) ? $data['title_err'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="text" name="start_time" id="start_time" placeholder="Start Time" value="<?php $data['start_time'];?>">
                    <span class="form-invalid"><?php echo isset($data['start_time_err']) ? $data['start_time_err'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="text" name="end_time" id="end_time" placeholder="End Time" value="<?php $data['end_time'];?>">
                    <span class="form-invalid"><?php echo isset($data['end_time_err']) ? $data['end_time_err'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="role">Assign To</label>
                    <select id="role" name="role" required>
                        <option value="">Select Employee...</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Task Description</label>
                    <textarea name="description" id="description" placeholder="Task Description" rows="10" cols="10" value="<?php $data['description'];?>"></textarea>
                    <span class="form-invalid"><?php echo isset($data['description_err']) ? $data['description_err'] : ''; ?></span>
                </div>
                
            </div>
            <div class="button-group">
                <input type="submit" value="Assign Task" class="btn btn-primary">
                <a href="<?php echo URLROOT; ?>/operationsCoordinator/tasks"><button type="button" class="btn btn-secondary" style="background-color: red;" onclick="closePopup('userFormPopup')">Cancel</button></a>
                
            </div>
        </form>







            </div>
        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->




    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>