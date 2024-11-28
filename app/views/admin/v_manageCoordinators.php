<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/manageCoordinators.css">
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
            <a href="#" class="active">
                <span class="material-icons-sharp">business</span>
                <h3>Coordinators</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Customers</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
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
                <div class="top-bar">
                    <h1>Manage Coordinators</h1>
                    <button id="addCoordinatorBtn" class="btn-add">
                        <i class="fas fa-plus"></i> Add Coordinator
                    </button>
                </div>

                <?php flash('coordinator_message'); ?>

                <div class="coordinators-grid">
                    <?php foreach($data['coordinator_types'] as $type): ?>
                    <div class="coordinator-card <?php echo strtolower($type); ?>">
                        <div class="card-header">
                            <h3><?php echo $type; ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $coordinator = null;
                            foreach($data['coordinators'] as $c) {
                                if($c->role === $type) {
                                    $coordinator = $c;
                                    break;
                                }
                            }
                            ?>
                            <?php if($coordinator): ?>
                                <div class="coordinator-info">
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Coordinator">
                                    <div class="info">
                                        <p class="name"><?php echo $coordinator->name; ?></p>
                                        <p class="email"><?php echo $coordinator->email; ?></p>
                                    </div>
                                    <div class="actions">
                                        <button class="btn-edit" onclick="openEditModal('<?php echo $coordinator->user_id; ?>', '<?php echo $coordinator->name; ?>', '<?php echo $coordinator->email; ?>', '<?php echo $coordinator->role; ?>')">
                                            <span class="material-icons-sharp">edit</span>
                                        </button>
                                        <button class="btn-delete" onclick="openDeleteModal('<?php echo $coordinator->user_id; ?>', '<?php echo $coordinator->name; ?>')">
                                            <span class="material-icons-sharp">delete</span>
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-user-plus"></i>
                                    <p>No coordinator assigned</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Add Coordinator Modal -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Coordinator</h2>
                    <span class="close">&times;</span>
                </div>
                <form action="<?php echo URLROOT; ?>/operationsCoordinator/createPackage" method="POST" enctype="multipart/form-data" class="package-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="chiefCoordinator">Chief Coordinator</option>
                            <option value="operationsCoordinator">Operations Coordinator</option>
                            <option value="hRAdministrator">HR Administrator</option>
                            <option value="supplierCoordinator">Supplier Coordinator</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Add Coordinator</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Coordinator Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Coordinator</h2>
                    <span class="close">&times;</span>
                </div>
                <form id="editCoordinatorForm" action="<?php echo URLROOT; ?>/admin/updateCoordinator" method="POST">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Package Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="on-grid" <?php echo ($data['type'] === 'on-grid') ? 'selected' : ''; ?>>On Grid</option>
                            <option value="off-grid" <?php echo ($data['type'] === 'off-grid') ? 'selected' : ''; ?>>Off Grid</option>
                            <option value="hybrid" <?php echo ($data['type'] === 'hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                        </select>
                        <span class="error"><?php echo $data['errors']['type'] ?? ''; ?></span>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Update Coordinator</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Delete Coordinator</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <span id="deleteCoordinatorName"></span>?</p>
                    <form id="deleteCoordinatorForm" action="<?php echo URLROOT; ?>/admin/deleteCoordinator" method="POST">
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <div class="form-actions">
                            <button type="button" class="btn-cancel model-close" onclick="closeDeleteModal()">Cancel</button>
                            <button type="submit" class="btn-delete model-delete" >Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script src="<?php echo URLROOT; ?>/js/admin/manageCoordinators.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>