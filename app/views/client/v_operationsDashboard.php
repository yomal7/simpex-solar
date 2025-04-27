<?php require APPROOT.'/views/client/header.php';?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/operationsDashboard.css">
</head>

<body data-user-id="<?php echo $_SESSION['user_id']; ?>" data-user-role="customer" data-urlroot="<?php echo URLROOT; ?>">
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <!-- <ul class="side-menu">
            <li>
                <a href="<php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
            </li>
        </ul> -->

        <ul class="side-menu">
            <li  ><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="active" ><a href="<?php echo URLROOT; ?>/client/operationDashboard"><i class='bx bx-analyse'></i>Quotations and Projects</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li>
                <a href="<?php echo URLROOT; ?>/client/chat">
                    <i class='bx bx-message-square-dots'></i>Chat
                    <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
                </a>
            </li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li ><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <div class="content">
            <!-- Navbar -->
            <nav>
                <i class='bx bx-menu'></i>
            </nav>

            <!-- End of Navbar -->
            
            <div class="container">
                <div class="dashboard-header">
                    <div class="dashboard-title">
                        <h1>My Solar Solutions</h1>
                        <p>Track your solar journey with us</p>
                    </div>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $data['stats']['active_projects']; ?></div>
                        <div class="stat-label">Active Projects</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $data['stats']['pending_quotations']; ?></div>
                        <div class="stat-label">Pending Quotations</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $data['stats']['total_projects']; ?></div>
                        <div class="stat-label">Total Solutions</div>
                    </div>
                </div>

                <!-- Active Quotation Section -->
                <?php if (!empty($data['quotations'])): ?>
                    <h2 class="section-title">My Quotations</h2>
                    <div class="quotations-grid">
                        <?php foreach($data['quotations'] as $quotation): ?>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <?php echo $quotation->package_type === 'premade' ? 'Package Quotation' : 'Custom Solution'; ?>
                                    </div>
                                    <span class="status-pill status-<?php echo $quotation->status; ?>">
                                        <?php echo ucfirst($quotation->status); ?>
                                    </span>
                                </div>
                                <div class="card-content">
                                    <div class="card-info">
                                        <span class="card-info-label">Quotation ID:</span>
                                        <span>QT<?php echo str_pad($quotation->quotation_id, 4, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div class="card-info">
                                        <span class="card-info-label">Submitted:</span>
                                        <span><?php echo date('M d, Y', strtotime($quotation->created_at)); ?></span>
                                    </div>
                                    <div class="card-info">
                                        <span class="card-info-label">Monthly consumption:</span>
                                        <span><?php echo ucfirst($quotation->monthly_consumption); ?> kW</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="<?php echo URLROOT; ?>/client/viewQuotation/<?php echo $quotation->quotation_id; ?>" 
                                    class="btn btn-primary">Review Quotation</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Projects Section -->
                <section class="ongoing-projects">
                    <h2 class="section-title">Ongoing Projects</h2>
                    <div class="project-grid">
                        <?php foreach ($data['ongoingProjects'] as $project): ?>
                            <?php 
                                // Map phase to CSS class
                                $phaseMapping = [
                                    'quotation' => 'quotation',
                                    'site_visit' => 'site-visit',
                                    'agreement' => 'agreement',
                                    'document_submission' => 'document-submission',
                                    'first_payment' => 'first-payment',
                                    'installation' => 'installation',
                                    'final_payment' => 'final-payment',
                                    'engineer_approval' => 'engineer-approval',
                                    'grid_connection' => 'grid-connection',
                                    'completed' => 'completed'
                                ];
                                
                                $phaseClass = isset($phaseMapping[$project->current_phase]) ? 
                                    $phaseMapping[$project->current_phase] : '';
                                
                                // Determine progress class based on percentage
                                $progressClass = '';
                                if ($project->progress_percentage < 30) {
                                    $progressClass = 'low';
                                } elseif ($project->progress_percentage < 70) {
                                    $progressClass = 'medium';
                                } else {
                                    $progressClass = 'high';
                                }
                                
                                // Get human-readable phase name
                                $phaseName = ucwords(str_replace('_', ' ', $project->current_phase));
                            ?>
                            <div class="project-card">
                                <div class="card-header">
                                    <span class="project-id">Project #<?php echo str_pad($project->pre_project_id, 4, '0', STR_PAD_LEFT); ?></span>
                                    <span class="phase-badge <?php echo $phaseClass; ?>"><?php echo $phaseName; ?></span>
                                </div>
                                <div class="card-content">
                                    <div class="project-info">
                                        <p><strong>Solution:</strong> <span><?php echo $project->package_name ?? 'Custom Solution'; ?></span></p>
                                        <p><strong>Location:</strong> <span><?php echo $project->nearest_city; ?></span></p>
                                        <p><strong>Current Phase:</strong> <span><?php echo $phaseName; ?></span></p>
                                    </div>
                                    <div class="project-progress">
                                        <div class="progress-label">
                                            <span>Progress</span>
                                            <span><?php echo $project->progress_percentage; ?>%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill <?php echo $progressClass; ?>" style="width: <?php echo $project->progress_percentage; ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="project-timeline">
                                        <div class="timeline-title">Project Timeline</div>
                                        <div class="timeline-steps">
                                            <div class="timeline-line"></div>
                                            <?php
                                            // Define all project phases in order
                                            $allPhases = [
                                                'quotation' => 'Quotation',
                                                'site_visit' => 'Site Visit',
                                                'agreement' => 'Agreement',
                                                'document_submission' => 'Documents',
                                                'first_payment' => 'First Payment',
                                                'installation' => 'Installation',
                                                'final_payment' => 'Final Payment',
                                                'engineer_approval' => 'Engineer Approval',
                                                'grid_connection' => 'Grid Connection',
                                                'completed' => 'Completed'
                                            ];
                                            
                                            // Get current phase index
                                            $currentPhaseIndex = array_search($project->current_phase, array_keys($allPhases));
                                            
                                            // Calculate how far along the timeline the progress should go (in percentage)
                                            $timelineProgress = (($currentPhaseIndex + 1) / count($allPhases)) * 100;
                                            
                                            // Display timeline progress
                                            echo '<div class="timeline-progress" style="width: ' . $timelineProgress . '%"></div>';
                                            
                                            // Display each phase dot
                                            $phaseIndex = 0;
                                            foreach ($allPhases as $phaseKey => $phaseName) {
                                                $stepClass = '';
                                                if ($phaseIndex < $currentPhaseIndex) {
                                                    $stepClass = 'completed';
                                                } elseif ($phaseIndex == $currentPhaseIndex) {
                                                    $stepClass = 'current';
                                                }
                                                
                                                echo '<div class="timeline-step ' . $stepClass . '">';
                                                echo '<div class="timeline-tooltip">' . $phaseName . '</div>';
                                                echo '</div>';
                                                
                                                $phaseIndex++;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <p class="date"><i class="fa-regular fa-calendar"></i> Started: <?php echo date('M d, Y', strtotime($project->created_at)); ?></p>
                                    <?php if(isset($project->updated_at) && $project->updated_at): ?>
                                    <p class="date"><i class="fa-regular fa-clock"></i> Updated: <?php echo date('M d, Y', strtotime($project->updated_at)); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <a href="<?php echo URLROOT; ?>/client/project/<?php echo $project->pre_project_id; ?>" class="btn btn-primary">View Details <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
            </div>
    </div>   

    <div class="overlay" id="overlay"></div>
    <div id="toastContainer" class="toast-container"></div>

    <script>
        function navigateToPhase(phase, quotationId) {
            const baseUrl = '<?php echo URLROOT; ?>/client/';
            let url;
            
            switch(phase) {
                case 'quotation':
                    url = `${baseUrl}viewQuotation/${quotationId}`;
                    break;
                case 'siteVisit':
                    url = `${baseUrl}siteVisit/${quotationId}`;
                    break;
                case 'agreement':
                    url = `${baseUrl}agreement/${quotationId}`;
                    break;
                default:
                    return;
            }
            
            window.location.href = url;
        }
    </script>
    <script src="<?php echo URLROOT; ?>/js/client/operationsDashboard.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>

