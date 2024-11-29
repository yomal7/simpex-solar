<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/managePackages.css">
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
                class="profile-picture"
            />
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="active">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
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

                <a href="<?php echo URLROOT; ?>/operationsCoordinator/addTask"><button class="new-task-btn" data-toggle="modal" data-target="#myModal">Assign New Task</button></a>
                <!-- <div class="card-container">
                    <div class="card" id="total-projects">

                    </div>
                </div> -->


                <!-- /* Task table */ -->
                <div class="table-section">

                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Task Title</th>
                                <th>Assigned To</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>mytitle</td>
                                <td>fullname</td>
                                <td>2024-11-23 15:00</td>
                                <td>2024-11-28 12:00</td>
                                <td>In Progress</td>
                                <td><button class="view-btn" onclick="downloadQuotation('${project.id}')">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </div>
                </div>








            </div>
        </div>
    </div>

    <div id="overlay"></div>
    <script src="script.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/managePackages.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>