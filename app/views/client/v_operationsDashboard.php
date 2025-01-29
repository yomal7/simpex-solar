<?php require APPROOT.'/views/client/header.php';?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/operationsDashboard.css">
</head>

<body>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
            </li>
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
                                        <span class="card-info-label">System Type:</span>
                                        <span><?php echo ucfirst($quotation->package_type); ?></span>
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
                    <h2>Ongoing Projects</h2>
                    <div class="project-grid">
                        <?php foreach ($data['ongoingProjects'] as $project): ?>
                            <div class="project-card" 
                                onclick="window.location.href='<?php echo URLROOT; ?>/client/project/<?php echo $project->pre_project_id; ?>'">
                                <div class="card-header">
                                    <span class="project-id">#<?php echo $project->pre_project_id; ?></span>
                                    <span class="phase-badge">Active</span>
                                </div>
                                <div class="card-content">
                                    <div class="project-info">
                                        <p><strong>System Type:</strong> <?php echo ucfirst($project->package_type); ?></p>
                                        <p><strong>Location:</strong> <?php echo $project->nearest_city; ?></p>
                                    </div>
                                    <p class="date">Started: <?php echo date('M d, Y', strtotime($project->created_at)); ?></p>
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

