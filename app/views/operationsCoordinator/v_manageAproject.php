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
            <!-- Customer Header -->
            <div class="customer-header">
                <div class="customer-info">
                    <h1><?php echo $data['customer']->customer_name; ?></h1>
                    <div class="customer-details">
                        <span><i class="material-icons-sharp">phone</i> <?php echo $data['customer']->phone; ?></span>
                        <span><i class="material-icons-sharp">email</i> <?php echo $data['customer']->email; ?></span>
                        <span><i class="material-icons-sharp">location_on</i> <?php echo $data['customer']->address ?? $data['customer']->location; ?></span>
                    </div>
                </div>
                <div class="project-status">
                    <div class="current-phase">
                        <h3>Current Phase</h3>
                        <span class="phase-chip <?php echo strtolower($data['project']->current_phase); ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $data['project']->current_phase)); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Project Details Summary -->
            <div class="summary-card">
                <div class="summary-section">
                    <h3>Package Details</h3>
                    <div class="detail-item">
                        <span class="label">Package:</span>
                        <span class="value"><?php echo $data['preproject']->package_name ?? 'Not specified'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">System Capacity:</span>
                        <span class="value"><?php echo $data['project']->system_capacity ?? 'Not specified'; ?> kW</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Est. Generation:</span>
                        <span class="value"><?php echo $data['project']->estimated_generation ?? 'Not specified'; ?> kWh/month</span>
                    </div>
                </div>
            </div>

            <!-- Timeline Progress -->
            <div class="progress-timeline">
                <h2>Project Timeline</h2>

                <!-- Pre-Project Timeline -->
                <div class="timeline-section">
                    <h3>Pre-Project Phases</h3>
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <div class="timeline-icon">📄</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h4>Quotation</h4>
                                <p>Initial project quotation completed</p>
                                <span class="timeline-status">Completed</span>
                            </div>
                        </div>

                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <div class="timeline-icon">🏠</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h4>Site Visit</h4>
                                <p>On-site assessment completed</p>
                                <span class="timeline-status">Completed</span>
                            </div>
                        </div>

                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <div class="timeline-icon">📋</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h4>Agreement</h4>
                                <p>Project agreement finalized</p>
                                <span class="timeline-status">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Timeline -->
                <div class="timeline-section">
                    <h3>Project Implementation</h3>
                    <div class="timeline">
                        <?php
                        $projectPhases = [
                            'document_submission' => [
                                'icon' => '📑',
                                'title' => 'Document Submission',
                                'description' => 'Submission of necessary documentation',
                                'url' => URLROOT . "/operationsCoordinator/documentSubmission/{$data['project']->project_id}"
                            ],
                            'first_payment' => [
                                'icon' => '💰',
                                'title' => 'First Payment',
                                'description' => 'Initial payment collection',
                                'url' => URLROOT . "/operationsCoordinator/firstPayment/{$data['project']->project_id}"
                            ],
                            'installation' => [
                                'icon' => '🔧',
                                'title' => 'Installation',
                                'description' => 'Physical installation of the system',
                                'url' => URLROOT . "/operationsCoordinator/installation/{$data['project']->project_id}"
                            ],
                            'final_payment' => [
                                'icon' => '💵',
                                'title' => 'Final Payment',
                                'description' => 'Final payment collection',
                                'url' => URLROOT . "/operationsCoordinator/finalPayment/{$data['project']->project_id}"
                            ],
                            'engineer_approval' => [
                                'icon' => '✅',
                                'title' => 'Engineer Approval',
                                'description' => 'Final technical approval',
                                'url' => URLROOT . "/operationsCoordinator/engineerApproval/{$data['project']->project_id}"
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

                            // Determine if this is the last item to remove the trailing line
                            $isLastItem = ($phaseIndex === count($phaseOrder) - 1) ? 'last-item' : '';
                        ?>
                            <div class="timeline-item <?php echo $status . ' ' . $isLastItem; ?>" onclick="window.location.href='<?php echo $info['url']; ?>'">
                                <div class="timeline-marker">
                                    <div class="timeline-icon"><?php echo $info['icon']; ?></div>
                                    <?php if (!$isLastItem): ?>
                                        <div class="timeline-line"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-content">
                                    <h4><?php echo $info['title']; ?></h4>
                                    <p><?php echo $info['description']; ?></p>

                                    <?php if ($phase === 'document_submission' && $status === 'completed'): ?>
                                        <p class="timeline-detail">Submitted on <?php echo date('M d, Y', strtotime($data['project']->documents_submitted ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'first_payment' && $status === 'completed'): ?>
                                        <p class="timeline-detail">Rs. <?php echo number_format($data['project']->first_payment_amount ?? 0); ?> on <?php echo date('M d, Y', strtotime($data['project']->first_payment_date ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'final_payment' && $status === 'completed'): ?>
                                        <p class="timeline-detail">Rs. <?php echo number_format($data['project']->final_payment_amount ?? 0); ?> on <?php echo date('M d, Y', strtotime($data['project']->final_payment_date ?? $data['project']->updated_at)); ?></p>
                                    <?php elseif ($phase === 'engineer_approval' && $status === 'completed'): ?>
                                        <p class="timeline-detail">Approved on <?php echo date('M d, Y', strtotime($data['project']->engineer_approval_date ?? $data['project']->updated_at)); ?></p>
                                    <?php endif; ?>

                                    <span class="timeline-status"><?php echo $statusText; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>
    <script>
        // Project Tracker JavaScript

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar functionality
            window.toggleSidebar = function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');

                sidebar.classList.toggle('collapsed');

                if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = '70px';
                } else {
                    mainContent.style.marginLeft = '240px';
                }
            };

            // Initialize all timeline items to be clickable
            const timelineItems = document.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Get the URL from the element
                    const url = this.getAttribute('data-url') || this.onclick?.toString().match(/window\.location\.href='([^']+)'/)?.[1];

                    if (url) {
                        window.location.href = url;
                    }
                });
            });

            // Highlight the current phase in the timeline
            highlightCurrentPhase();
        });

        // Function to highlight current phase
        function highlightCurrentPhase() {
            const currentPhase = document.querySelector('.phase-chip').classList[1];
            const phaseElement = document.querySelector(`.timeline-item.active`);

            if (phaseElement) {
                // Scroll to the current phase with a smooth animation
                setTimeout(() => {
                    phaseElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 500);
            }
        }

        // Handle responsive design
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '0';
            } else if (!sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '240px';
            }
        });
    </script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/projectTracker.js"></script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>