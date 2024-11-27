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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects"  class="active">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
    
            <header>
                <h1>Solar Package Management</h1>
                <button id="addPackagemanagerBtn" class="managerBtn managerBtn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    Add Package
                </button>
            </header>



                <div class="table-container">
                    <table id="packagesTable">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th>Details</th>
                                <th>Pricing</th>
                                <th>Warranty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="packagesTableBody"></tbody>
                    </table>
                </div>
            </div>


        <div id="deleteConfirmPopup" class="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h2>Delete Package</h2>
                    <button class="close-managerBtn" onclick="closePopup('deleteConfirmPopup')">×</button>
                </div>
                <div class="delete-confirm-content">
                    <div class="warning-icon">⚠️</div>
                    <p>Are you sure you want to delete this package? This action cannot be undone.</p>
                    <div class="form-actions">
                        <button onclick="closePopup('deleteConfirmPopup')" class="managerBtn managerBtn-secondary">Cancel</button>
                        <button onclick="confirmDelete()" class="managerBtn managerBtn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div id="overlay"></div>
    <script src="script.js"></script>
    <!-- <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/managePackages.js"></script> -->
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>