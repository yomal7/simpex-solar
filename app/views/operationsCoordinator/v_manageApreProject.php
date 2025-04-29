<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageApreProject.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/preprojects" class="active">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
                <!-- Customer Information Card -->
                <div class="info-section">
                    <h2>Customer Information
                        <span class="status-tag <?php echo strtolower($data['project']->current_phase); ?>">
                            <?php echo ucfirst($data['project']->current_phase) . ' Phase'; ?>
                        </span>
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <p class="info-label">Customer Name</p>
                            <p class="info-value"><?php echo $data['project']->customer_name; ?></p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Phone</p>
                            <p class="info-value"><?php echo $data['project']->phone; ?></p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Email</p>
                            <p class="info-value"><?php echo $data['project']->email; ?></p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Address</p>
                            <p class="info-value"><?php echo $data['project']->address; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Phase Cards -->
                <div class="details-section">
                    <h3>Project Details</h3>
                    <div class="details-grid">
                        <div class="detail-card">
                            <h4>Package Information</h4>
                            <p><strong>Package Name:</strong> <?php echo $data['project']->package_name ?? 'Not specified'; ?></p>
                            <p><strong>Monthly Consumption:</strong> <?php echo $data['project']->monthly_consumption ?? 'Not specified'; ?> kWh</p>
                        </div>
                        <div class="detail-card">
                            <h4>Location Details</h4>
                            <p><strong>Nearest City:</strong> <?php echo $data['project']->nearest_city ?? 'Not specified'; ?></p>
                            <p><strong>Installation Address:</strong> <?php echo $data['project']->address ?? 'Not specified'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Phase Cards -->


                <div class="phase-list">
                    <?php
                    $phases = [
                        'quotation' => [
                            'icon' => '📄',
                            'title' => 'Quotation Phase',
                            'url' => URLROOT . '/operationsCoordinator/manageQuotation/' . $data['project']->pre_project_id
                        ],
                        'site_visit' => [
                            'icon' => '🏠',
                            'title' => 'Site Visit Phase',
                            'url' => URLROOT . '/operationsCoordinator/manageSiteVisit/' . $data['project']->pre_project_id
                        ],
                        'agreement' => [
                            'icon' => '📋',
                            'title' => 'Agreement Phase',
                            'url' => URLROOT . '/operationsCoordinator/manageAgreement/' . $data['project']->pre_project_id
                        ]
                    ];

                    $currentPhaseIndex = array_search($data['project']->current_phase, array_keys($phases));

                    foreach ($phases as $phase => $info):
                        $phaseIndex = array_search($phase, array_keys($phases));
                        $status = '';

                        if ($phaseIndex < $currentPhaseIndex) {
                            $status = 'completed';
                        } elseif ($phaseIndex === $currentPhaseIndex) {
                            $status = 'active';
                            if ($phase === 'quotation' && $data['project']->quotation_status) {
                                $status = $data['project']->quotation_status;
                            }
                        } else {
                            $status = 'pending';
                        }
                    ?>
                        <div class="phase-card" data-url="<?php echo $info['url']; ?>">
                            <div class="phase-icon"><?php echo $info['icon']; ?></div>
                            <div class="phase-content">
                                <h3><?php echo $info['title']; ?></h3>
                                <?php if ($phase === 'quotation' && $data['project']->quotation_status): ?>
                                    <p class="phase-status"><?php echo ucfirst($data['project']->quotation_status); ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="status-badge <?php echo $status; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Action Buttons -->
            <!-- <div class="actions-section">
                    <?php if ($data['project']->current_phase === 'quotation'): ?>
                        <button class="action-btn review-btn" onclick="reviewQuotation(<?php echo $data['project']->pre_project_id; ?>)">
                            Review Quotation
                        </button>
                    <?php elseif ($data['project']->current_phase === 'site_visit'): ?>
                        <button class="action-btn schedule-btn" onclick="scheduleSiteVisit(<?php echo $data['project']->pre_project_id; ?>)">
                            Schedule Visit
                        </button>
                    <?php elseif ($data['project']->current_phase === 'agreement'): ?>
                        <button class="action-btn agreement-btn" onclick="manageAgreement(<?php echo $data['project']->pre_project_id; ?>)">
                            Manage Agreement
                        </button>
                    <?php endif; ?>
                </div> -->
        </div>
    </div>



    </div>



    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/manageApreProject.js"></script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>