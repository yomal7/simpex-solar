<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageAproject.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

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

            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
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
                            <?php echo ucfirst(str_replace('_', ' ', $data['project']->current_phase)); ?> Phase
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
                            <p class="info-value"><?php echo $data['project']->address ?? $data['project']->location; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="details-section">
                    <h3>Project Details</h3>
                    <div class="details-grid">
                        <div class="detail-card">
                            <h4>Package Information</h4>
                            <p><strong>Package Name:</strong> <?php echo $data['project']->package_name ?? 'Not specified'; ?></p>
                            <p><strong>System Capacity:</strong> <?php echo $data['project']->system_capacity ?? 'Not specified'; ?> kW</p>
                            <p><strong>Estimated Generation:</strong> <?php echo $data['project']->estimated_generation ?? 'Not specified'; ?> kWh/month</p>
                        </div>
                        <div class="detail-card">
                            <h4>Financial Details</h4>
                            <p><strong>Total Cost:</strong> Rs. <?php echo number_format($data['project']->total_price ?? 0); ?></p>
                            <p><strong>First Payment:</strong> Rs. <?php echo number_format($data['project']->first_payment_amount ?? 0); ?></p>
                            <p><strong>Final Payment:</strong> Rs. <?php echo number_format($data['project']->final_payment_amount ?? 0); ?></p>
                        </div>
                    </div>
                </div>

                <!-- PreProject Phases (Always Completed) -->
                <div class="phase-section">
                    <h3>Pre-Project History</h3>
                    <div class="phase-list">
                        <div class="phase-card completed">
                            <div class="phase-icon">📄</div>
                            <div class="phase-content">
                                <h3>Quotation Phase</h3>
                                <p class="phase-status">Completed</p>
                            </div>
                            <span class="status-badge completed">Completed</span>
                        </div>

                        <div class="phase-card completed">
                            <div class="phase-icon">🏠</div>
                            <div class="phase-content">
                                <h3>Site Visit Phase</h3>
                                <p class="phase-status">Completed</p>
                            </div>
                            <span class="status-badge completed">Completed</span>
                        </div>

                        <div class="phase-card completed">
                            <div class="phase-icon">📋</div>
                            <div class="phase-content">
                                <h3>Agreement Phase</h3>
                                <p class="phase-status">Completed</p>
                            </div>
                            <span class="status-badge completed">Completed</span>
                        </div>
                    </div>
                </div>

                <!-- Project Phases -->
                <div class="phase-section">
                    <h3>Project Progress</h3>
                    <div class="phase-list">
                        <?php
                        $projectPhases = [
                            'document_submission' => [
                                'icon' => '📑',
                                'title' => 'Document Submission'
                            ],
                            'first_payment' => [
                                'icon' => '💰',
                                'title' => 'First Payment'
                            ],
                            'installation' => [
                                'icon' => '🔧',
                                'title' => 'Installation'
                            ],
                            'final_payment' => [
                                'icon' => '💵',
                                'title' => 'Final Payment'
                            ],
                            'engineer_approval' => [
                                'icon' => '✅',
                                'title' => 'Engineer Approval'
                            ]
                        ];

                        $phaseOrder = array_keys($projectPhases);
                        $currentPhaseIndex = array_search($data['project']->current_phase, $phaseOrder);

                        foreach ($projectPhases as $phase => $info):
                            $phaseIndex = array_search($phase, $phaseOrder);

                            if ($phaseIndex < $currentPhaseIndex) {
                                $status = 'completed';
                                $statusText = 'Completed';
                            } elseif ($phaseIndex === $currentPhaseIndex) {
                                $status = 'active';
                                $statusText = 'In Progress';
                            } else {
                                $status = 'pending';
                                $statusText = 'Pending';
                            }

                            $url = URLROOT . "/operationsCoordinator/manageProjectPhase/{$data['project']->project_id}/{$phase}";
                        ?>
                            <div class="phase-card" data-phase="<?php echo $phase; ?>"
                                <?php if ($phase === 'document_submission'): ?>
                                onclick="window.location.href='<?php echo URLROOT ?>/operationsCoordinator/documentSubmission/<?php echo $data['project']->project_id; ?>'"
                                <?php elseif ($phase === 'first_payment'): ?>
                                onclick="window.location.href='<?php echo URLROOT ?>/operationsCoordinator/firstPayment/<?php echo $data['project']->project_id; ?>'"
                                <?php elseif ($phase === 'installation'): ?>
                                onclick="window.location.href='<?php echo URLROOT ?>/operationsCoordinator/installation/<?php echo $data['project']->project_id; ?>'"
                                <?php else: ?>
                                data-url="<?php echo $url; ?>"
                                <?php endif; ?>>
                                <div class="phase-icon"><?php echo $info['icon']; ?></div>
                                <div class="phase-content">
                                    <h3><?php echo $info['title']; ?></h3>
                                    <?php if ($phase === $data['project']->current_phase && isset($data['project']->phase_status)): ?>
                                        <p class="phase-status"><?php echo ucfirst(str_replace('_', ' ', $data['project']->phase_status)); ?></p>
                                    <?php endif; ?>

                                    <?php if ($phase === 'document_submission' && $status === 'completed'): ?>
                                        <p class="phase-details">Documents submitted on <?php echo date('M d, Y', strtotime($data['project']->documents_submitted ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'first_payment' && $status === 'completed'): ?>
                                        <p class="phase-details">Payment of Rs. <?php echo number_format($data['project']->first_payment_amount ?? 0); ?> on <?php echo date('M d, Y', strtotime($data['project']->first_payment_date ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'final_payment' && $status === 'completed'): ?>
                                        <p class="phase-details">Payment of Rs. <?php echo number_format($data['project']->final_payment_amount ?? 0); ?> on <?php echo date('M d, Y', strtotime($data['project']->final_payment_date ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'engineer_approval' && $status === 'completed'): ?>
                                        <p class="phase-details">Approved on <?php echo date('M d, Y', strtotime($data['project']->engineer_approval_date ?? $data['project']->updated_at)); ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="status-badge <?php echo $status; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/manageAproject.js"></script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>