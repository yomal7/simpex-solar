<?php require APPROOT.'/views/client/header.php';?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/project.css">
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
            <li ><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li  class="active"><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li ><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->
         
        <main>
        <div class="progress-container container">
            <!-- Pre-project Phases -->
            <?php 
            $preProjectPhases = [
                'quotation' => [
                    'title' => 'Quotation Phase',
                    'description' => 'Review and accept project quotation'
                ],
                'site_visit' => [
                    'title' => 'Site Visit Phase',
                    'description' => 'Schedule a date for site visit'
                ],
                'agreement' => [
                    'title' => 'Agreement Phase',
                    'description' => 'Agreement approving and signing'
                ]
            ];

            $projectPhases = [
                'document_submission' => [
                    'title' => 'Document Submission',
                    'description' => 'Submit required documents'
                ],
                'first_payment' => [
                    'title' => 'First Payment Phase',
                    'description' => 'Pay the 25% of total project cost'
                ],
                'installation' => [
                    'title' => 'Installation Phase',
                    'description' => 'Schedule the date for installation'
                ],
                'final_payment' => [
                    'title' => 'Final Payment Phase',
                    'description' => 'Pay the remaining 75% of total project cost'
                ]
            ];

            $currentPhase = $data['progress']['project'] ? 
                $data['progress']['project']->current_phase : 
                $data['progress']['pre_project']->current_phase;
            ?>

            <div class="progress-list">
                <?php foreach($preProjectPhases as $phase => $info): ?>
                    <?php
                    $status = 'locked';
                    if ($phase === $currentPhase) {
                        $status = 'active';
                    } elseif ($data['progress']['project'] || 
                            array_search($phase, array_keys($preProjectPhases)) < 
                            array_search($currentPhase, array_keys($preProjectPhases))) {
                        $status = 'completed';
                    }
                    ?>
                    <div class="progress-item <?php echo $status; ?>">
                        <div class="progress-dot"></div>
                        <div class="progress-info">
                            <h3><?php echo $info['title']; ?></h3>
                            <p><?php echo $info['description']; ?></p>
                            <?php if($status === 'active'): ?>
                                <?php if($phase === 'site_visit'): ?>
                                    <a href="<?php echo URLROOT . '/client/siteVisit/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                        Proceed Now
                                    </a>
                                <?php elseif($phase === 'agreement'): ?>
                                    <a href="<?php echo URLROOT . '/client/agreement/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                        Proceed Now
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo URLROOT . '/client/project/' . $data['pre_project_id'] . '/' . $phase; ?>" class="btn-proceed">
                                        Proceed Now
                                    </a>
                                <?php endif; ?>
                            <?php elseif($status === 'completed'): ?>
                                <?php if($phase === 'site_visit'): ?>
                                    <a href="<?php echo URLROOT . '/client/siteVisit/' . $data['pre_project_id']; ?>" class="btn-view">
                                        View Details
                                    </a>
                                <?php elseif($phase === 'agreement'): ?>
                                    <a href="<?php echo URLROOT . '/client/agreement/' . $data['pre_project_id']; ?>" class="btn-view">
                                        View Details
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo URLROOT . '/client/project/' . $data['pre_project_id'] . '/' . $phase; ?>" class="btn-view">
                                        View Details
                                    </a>
                                <?php endif; ?>
                                <span class="check-mark">✓</span>
                            <?php else: ?>
                                <span class="lock-icon">🔒</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if($data['progress']['project']): ?>
                    <?php foreach($projectPhases as $phase => $info): ?>
                        <?php
                        $status = 'locked';
                        if ($phase === $currentPhase) {
                            $status = 'active';
                        } elseif (array_search($phase, array_keys($projectPhases)) < 
                                array_search($currentPhase, array_keys($projectPhases))) {
                            $status = 'completed';
                        }
                        ?>
                        <div class="progress-item <?php echo $status; ?>">
                            <div class="progress-dot"></div>
                            <div class="progress-info">
                                <h3><?php echo $info['title']; ?></h3>
                                <p><?php echo $info['description']; ?></p>
                                <?php if($status === 'active'): ?>
                                    <a href="<?php echo URLROOT; ?>/client/project/<?php echo $phase; ?>" 
                                    class="btn-proceed">
                                        Proceed Now
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        </main>
    <script src="<?php echo URLROOT; ?>/js/client/project.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>