<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/viewService.css">
</head>

<body data-user-role="operationsCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar remains the same -->
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Operations Coordinator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/preProjects">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Pre-Project</h3>
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/services" class="active">
                <span class="material-icons-sharp">build</span>
                <h3>Services</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/chat">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <?php flash('service_message'); ?>
                
                <div class="back-nav">
                    <a href="<?php echo URLROOT; ?>/operationsCoordinator/services" class="btn-back">
                        <i class="material-icons-sharp">arrow_back</i> Back to Services
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-header">
                        <h2 class="service-title">Service Request #<?php echo $data['service']->service_id; ?></h2>
                        <span class="service-status <?php echo strtolower($data['service']->status); ?>">
                            <?php echo str_replace('_', ' ', ucfirst($data['service']->status)); ?>
                        </span>
                    </div>
                    
                    <div class="service-details">
                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="material-icons-sharp">person</i>
                                <div class="detail-content">
                                    <label>Customer</label>
                                    <p><?php echo $data['customer']->name; ?></p>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <i class="material-icons-sharp">calendar_today</i>
                                <div class="detail-content">
                                    <label>Requested Date</label>
                                    <p><?php echo date('F j, Y', strtotime($data['service']->requested_date)); ?></p>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <i class="material-icons-sharp">build</i>
                                <div class="detail-content">
                                    <label>Issue Type</label>
                                    <p><?php echo ucfirst($data['service']->issue_type); ?></p>
                                </div>
                            </div>

                            
                            <div class="detail-item">
                                <i class="material-icons-sharp">folder</i>
                                <div class="detail-content">
                                    <label>Project ID</label>
                                    <p><?php echo $data['service']->project_id; ?></p>
                                </div>
                            </div>
                            
                            <div class="detail-item description">
                                <i class="material-icons-sharp">description</i>
                                <div class="detail-content">
                                    <label>Description</label>
                                    <p><?php echo nl2br(htmlspecialchars($data['service']->description)); ?></p>
                                </div>
                            </div>
                            
                            <?php if (!empty($data['service']->comments)): ?>
                            <div class="detail-item description">
                                <i class="material-icons-sharp">note</i>
                                <div class="detail-content">
                                    <label>Comments</label>
                                    <p><?php echo nl2br(htmlspecialchars($data['service']->comments)); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <?php if ($data['service']->status === 'pending'): ?>
                            <button type="button" class="btn btn-accept" id="acceptBtn">
                                <i class="material-icons-sharp">check</i> Accept Request
                            </button>
                            
                            <button type="button" class="btn btn-reject" id="rejectBtn">
                                <i class="material-icons-sharp">close</i> Reject Request
                            </button>
                        <?php elseif ($data['service']->status === 'accepted'): ?>
                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/updateServiceStatus" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $data['service']->service_id; ?>">
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-progress">
                                    <i class="material-icons-sharp">build</i> Start Service
                                </button>
                            </form>
                        <?php elseif ($data['service']->status === 'in_progress'): ?>
                            <form action="<?php echo URLROOT; ?>/operationsCoordinator/updateServiceStatus" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $data['service']->service_id; ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-complete">
                                    <i class="material-icons-sharp">done_all</i> Mark as Completed
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acceptance Modal -->
    <div class="modal" id="acceptanceModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Acceptance Comments</h3>
                <span class="close-modal" id="closeAcceptModal">&times;</span>
            </div>
            <form action="<?php echo URLROOT; ?>/operationsCoordinator/updateServiceStatus" method="POST">
                <input type="hidden" name="service_id" value="<?php echo $data['service']->service_id; ?>">
                <input type="hidden" name="status" value="accepted">
                
                <div class="modal-body">
                    <textarea name="comments" id="acceptanceNotes" placeholder="Add any comments about accepting this service request (optional)"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" id="cancelAccept">Cancel</button>
                    <button type="submit" class="btn btn-accept">Confirm Acceptance</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Rejection Modal -->
    <div class="modal" id="rejectionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Rejection Reason</h3>
                <span class="close-modal" id="closeRejectModal">&times;</span>
            </div>
            <form action="<?php echo URLROOT; ?>/operationsCoordinator/updateServiceStatus" method="POST">
                <input type="hidden" name="service_id" value="<?php echo $data['service']->service_id; ?>">
                <input type="hidden" name="status" value="rejected">
                
                <div class="modal-body">
                    <textarea name="comments" id="rejectionNotes" placeholder="Please provide a reason for rejecting this service request..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" id="cancelReject">Cancel</button>
                    <button type="submit" class="btn btn-reject">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Accept Modal handling
        const acceptBtn = document.getElementById('acceptBtn');
        const acceptanceModal = document.getElementById('acceptanceModal');
        const closeAcceptModal = document.getElementById('closeAcceptModal');
        const cancelAccept = document.getElementById('cancelAccept');
        
        if (acceptBtn) {
            acceptBtn.addEventListener('click', () => {
                acceptanceModal.style.display = 'flex';
            });
        }
        
        if (closeAcceptModal) {
            closeAcceptModal.addEventListener('click', () => {
                acceptanceModal.style.display = 'none';
            });
        }
        
        if (cancelAccept) {
            cancelAccept.addEventListener('click', () => {
                acceptanceModal.style.display = 'none';
            });
        }
        
        // Reject Modal handling
        const rejectBtn = document.getElementById('rejectBtn');
        const rejectionModal = document.getElementById('rejectionModal');
        const closeRejectModal = document.getElementById('closeRejectModal');
        const cancelReject = document.getElementById('cancelReject');
        
        if (rejectBtn) {
            rejectBtn.addEventListener('click', () => {
                rejectionModal.style.display = 'flex';
            });
        }
        
        if (closeRejectModal) {
            closeRejectModal.addEventListener('click', () => {
                rejectionModal.style.display = 'none';
            });
        }
        
        if (cancelReject) {
            cancelReject.addEventListener('click', () => {
                rejectionModal.style.display = 'none';
            });
        }
        
        // Close modals if clicked outside
        window.addEventListener('click', (e) => {
            if (e.target === rejectionModal) {
                rejectionModal.style.display = 'none';
            }
            if (e.target === acceptanceModal) {
                acceptanceModal.style.display = 'none';
            }
        });
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>