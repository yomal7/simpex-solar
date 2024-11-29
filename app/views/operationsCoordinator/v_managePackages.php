<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
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
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
       
        <div class="main-content">
            <div class="page-header">
                <h2>Manage Packages</h2>
                <a href="<?php echo URLROOT; ?>/operationsCoordinator/packages" class="btn-add">
                    <span class="material-icons-sharp">add</span>
                    New Package
                </a>
            </div>

            <?php flash('package_message'); ?>

            <div class="packages-grid">
                <?php if(!empty($data['packages'])): ?>
                    <?php foreach($data['packages'] as $package): ?>
                        <div class="package-card" data-aos="fade-up">
                            <div class="package-image">
                                <?php if(!empty($package->image)): ?>
                                    <img src="<?php echo URLROOT . '/' . $package->image; ?>" alt="<?php echo htmlspecialchars($package->title); ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <span class="material-icons-sharp">image_not_supported</span>
                                    </div>
                                <?php endif; ?>
                                <div class="package-type <?php echo strtolower($package->type); ?>">
                                    <?php echo htmlspecialchars($package->type); ?>
                                </div>
                            </div>

                            <div class="package-content">
                                <h3><?php echo htmlspecialchars($package->title); ?></h3>
                                <div class="package-meta">
                                    <span>
                                        <i class="material-icons-sharp package-warranty">warranty</i> 
                                        <span style="color: red; font-size: 1.2em; font-weight: bold"><?php echo intval($package->warranty_years); ?> years warranty</span>
                                    </span>
                                </div>

                                <p class="package-description">
                                    <?php echo htmlspecialchars(substr($package->description ?? '', 0, 100)); ?>...
                                </p>

                                <div class="package-pricing">
                                    <div class="price">
                                        <span class="label">Price:</span>
                                        <span class="amount">Rs <?php echo number_format($package->final_price ?? 0, 2); ?></span>
                                    </div>
                                    <div class="service-charge">
                                        <span class="label">Service Charge:</span>
                                        <span class="amount">Rs <?php echo number_format($package->service_charge ?? 0, 2); ?></span>
                                    </div>
                                </div>

                                <div class="package-actions">
                                    <a href="<?php echo URLROOT; ?>/operationsCoordinator/editPackage/<?php echo $package->package_id; ?>" 
                                        class="btn-edit" title="Edit Package">
                                        <span class="material-icons-sharp">edit</span>
                                    </a>
                                    <button onclick="showDeleteModal(<?php echo $package->package_id; ?>)" 
                                            class="btn-delete" title="Delete Package">
                                        <span class="material-icons-sharp">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-packages">
                        <span class="material-icons-sharp">inventory_2</span>
                        <h3>No Packages Found</h3>
                        <p>Start by creating your first package</p>
                    </div>
                <?php endif; ?>
            </div>
            <div id="deleteModal" class="modal">
                <div class="modal-content">
                    <h3>Delete Package</h3>
                    <p>Are you sure you want to delete this package? This action cannot be undone.</p>
                    <div class="modal-actions">
                        <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                        <form id="deletePackageForm" method="POST">
                            <button type="submit" class="btn-delete model-delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/managePackages.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>