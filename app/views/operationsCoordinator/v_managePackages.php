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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages"  class="active">
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
                            <th>Price</th>
                            <th>Warranty</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="packagesTableBody"></tbody>
                </table>
            </div>
        </div>

        <div id="packageFormPopup" class="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h2 id="formTitle">Add New Package</h2>
                    <button class="close-managerBtn" onclick="closePopup('packageFormPopup')">×</button>
                </div>
                <form id="packageForm" onsubmit="handleSubmit(event)">
                    <input type="hidden" id="packageId">
                    
                    <div class="form-group">
                        <label for="title">Package Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group" class="select-type">
                        <label for="packageType">Package Type</label>
                        <select id="packageType" name="type" required class="form-select">
                            <option value="on-grid" class="on-grid">On-Grid</option>
                            <option value="off-grid" class="off-grid">Off-Grid</option>
                            <option value="hybrid" class="hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (Rs)</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="warranty">Warranty (Years)</label>
                            <input type="number" id="warranty" name="warranty_years" required>
                        </div>
                    </div>

                    <div class="features-section">
                        <div class="section-header">
                            <h3>Package Features</h3>
                            <button type="button" onclick="addFeature()" class="managerBtn managerBtn-small">+ Add Feature</button>
                        </div>
                        <div id="featuresContainer"></div>
                    </div>

                    <div class="equipment-section">
                        <div class="section-header">
                            <h3>Equipment</h3>
                            <button type="button" onclick="addEquipment()" class="managerBtn managerBtn-small">+ Add Equipment</button>
                        </div>
                        <div id="equipmentContainer"></div>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="closePopup('packageFormPopup')" class="managerBtn managerBtn-secondary">Cancel</button>
                        <button type="submit" class="managerBtn managerBtn-primary">Save Package</button>
                    </div>
                </form>
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
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/managePackages.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>