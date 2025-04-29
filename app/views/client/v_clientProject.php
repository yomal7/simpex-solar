<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/project.css">
</head>

<body>

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/operationDashboard" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a>
            </li>
            </li>
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

            <div class="project-header">
                <h1>Project Details</h1>
                <h2>Project ID: <?php echo $data['pre_project_id']; ?></h2>
            </div>

            <?php
            // Check if project is completed
            $isProjectCompleted = false;
            if (isset($data['progress']['project']) && 
                is_object($data['progress']['project']) && 
                isset($data['progress']['project']->current_phase) &&
                $data['progress']['project']->current_phase === 'completed') {
                $isProjectCompleted = true;
            }
            
            if ($isProjectCompleted): 
            ?>
            <!-- Project Completed Summary View -->
            <div class="project-summary container">
                <i class='bx bx-check-circle completed-icon'></i>
                <h2>Project Successfully Completed!</h2>
                <span class="completion-badge">Completed</span>
                
                <div class="detail-row">
                    <strong>Project ID:</strong>
                    <span>PR-<?php echo str_pad($data['pre_project_id'], 5, '0', STR_PAD_LEFT); ?></span>
                </div>
                
                <?php if (isset($data['progress']['project']->completion_date)): ?>
                <div class="detail-row">
                    <strong>Completion Date:</strong>
                    <span><?php echo date('F j, Y', strtotime($data['progress']['project']->completion_date)); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($data['progress']['project']->total_capacity)): ?>
                <div class="detail-row">
                    <strong>System Capacity:</strong>
                    <span><?php echo $data['progress']['project']->total_capacity; ?> kW</span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($data['progress']['engineer_approval_date'])): ?>
                <div class="detail-row">
                    <strong>Engineer Approval Date:</strong>
                    <span><?php echo date('F j, Y', strtotime($data['progress']['engineer_approval_date'])); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($data['progress']['grid_connection_date'])): ?>
                <div class="detail-row">
                    <strong>Grid Connection Date:</strong>
                    <span><?php echo date('F j, Y', strtotime($data['progress']['grid_connection_date'])); ?></span>
                </div>
                <?php endif; ?>
                
                <p>Thank you for choosing us for your solar energy project. Your system is now fully operational.</p>
                
                <?php if (isset($data['progress']['project']->warranty_period)): ?>
                <div class="certificate-info">
                    <strong>Warranty Information:</strong>
                    <p>Your system is covered by a <?php echo $data['progress']['project']->warranty_period; ?> year warranty.</p>
                </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <!-- Quotation Download Button -->
                    <a href="<?php 
                        if (isset($data['quotation_id']) && !empty($data['quotation_id'])) {
                            echo URLROOT . '/client/downloadQuotation/' . $data['quotation_id'];
                        } elseif (isset($data['progress']['pre_project']->quotation_id) && !empty($data['progress']['pre_project']->quotation_id)) {
                            echo URLROOT . '/client/downloadQuotation/' . $data['progress']['pre_project']->quotation_id;
                        }
                    ?>" class="btn-download" target="_blank">
                        <i class='bx bx-download'></i> Download Quotation
                    </a>
                    
                    <!-- Agreement Download Button -->
                    <a href="<?php 
                        if (isset($data['quotation_id']) && !empty($data['quotation_id'])) {
                            echo URLROOT . '/client/downloadAgreement/' . $data['quotation_id'];
                        } elseif (isset($data['progress']['pre_project']->quotation_id) && !empty($data['progress']['pre_project']->quotation_id)) {
                            echo URLROOT . '/client/downloadAgreement/' . $data['progress']['pre_project']->quotation_id;
                        }
                    ?>" class="btn-download" target="_blank">
                        <i class='bx bx-download'></i> Download Agreement
                    </a>
                    
                    <!-- Certificate Download Button (if available) -->
                    <?php if (isset($data['certificate']) && $data['certificate']): ?>
                    <a href="<?php echo URLROOT; ?>/client/downloadCertificate/<?php echo $data['certificate']->certificate_id; ?>" 
                       class="btn-download" target="_blank">
                        <i class='bx bx-certification'></i> Download Certificate
                    </a>
                    <?php endif; ?>
                    
                    <!-- After Sales Service Button -->
                    <a href="<?php echo URLROOT; ?>/client/services" class="btn-service">
                        <i class='bx bx-support'></i> After Sales Support
                    </a>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Regular Project Progress View -->
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
                    ],
                    'engineer_approval' => [
                        'title' => 'Engineer Approval Phase',
                        'description' => 'Engineer approval and signing'
                    ]
                ];

                $currentPhase = '';
                if (isset($data['progress']['project']) && is_object($data['progress']['project']) && isset($data['progress']['project']->current_phase)) {
                    $currentPhase = $data['progress']['project']->current_phase;
                } elseif (isset($data['progress']['pre_project']) && is_object($data['progress']['pre_project']) && isset($data['progress']['pre_project']->current_phase)) {
                    $currentPhase = $data['progress']['pre_project']->current_phase;
                }
                ?>

                <div class="progress-list">
                    <?php foreach ($preProjectPhases as $phase => $info): ?>
                        <?php
                        $status = 'locked';
                        if ($phase === $currentPhase) {
                            $status = 'active';
                        } elseif (
                            $data['progress']['project'] ||
                            array_search($phase, array_keys($preProjectPhases)) <
                            array_search($currentPhase, array_keys($preProjectPhases))
                        ) {
                            $status = 'completed';
                        }
                        ?>
                        <div class="progress-item <?php echo $status; ?>">
                            <div class="progress-dot"></div>
                            <div class="progress-info">
                                <h3><?php echo $info['title']; ?></h3>
                                <p><?php echo $info['description']; ?></p>
                                <?php if ($status === 'active'): ?>
                                    <?php if ($phase === 'quotation'): ?>
                                        <a href="<?php
                                                    // Check if quotation_id exists in data array
                                                    if (isset($data['quotation_id'])) {
                                                        echo URLROOT . '/client/viewQuotation/' . $data['quotation_id'];
                                                    } else {
                                                        // If no quotation_id, try to get it from the progress data
                                                        echo URLROOT . '/client/viewQuotation/' . ($data['progress']['pre_project']->quotation_id ?? '');
                                                    }
                                                    ?>" class="btn-proceed">
                                            Proceed Now
                                        </a>
                                    <?php elseif ($phase === 'site_visit'): ?>
                                        <a href="<?php echo URLROOT . '/client/siteVisit/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                            Proceed Now
                                        </a>
                                    <?php elseif ($phase === 'agreement'): ?>
                                        <a href="<?php echo URLROOT . '/client/agreement/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                            Proceed Now
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo URLROOT . '/client/project/' . $data['pre_project_id'] . '/' . $phase; ?>" class="btn-proceed">
                                            Proceed Now
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($status === 'completed'): ?>
                                    <?php if ($phase === 'quotation'): ?>
                                        <div class="button-group">
                                            <a href="<?php 
                                                // Check if quotation_id exists in data array
                                                if (isset($data['quotation_id']) && !empty($data['quotation_id'])) {
                                                    echo URLROOT . '/client/viewQuotation/' . $data['quotation_id'];
                                                } elseif (isset($data['progress']['pre_project']->quotation_id) && !empty($data['progress']['pre_project']->quotation_id)) {
                                                    // If no quotation_id directly in data, try to get it from the progress data
                                                    echo URLROOT . '/client/viewQuotation/' . $data['progress']['pre_project']->quotation_id;
                                                } else {
                                                    // Fallback to pre_project_id if no quotation_id is available
                                                    echo URLROOT . '/client/quotation/' . $data['pre_project_id'];
                                                }
                                            ?>" class="btn-view">
                                                View Details
                                            </a>
                                            <a href="<?php 
                                                if (isset($data['quotation_id']) && !empty($data['quotation_id'])) {
                                                    echo URLROOT . '/client/downloadQuotation/' . $data['quotation_id'];
                                                } elseif (isset($data['progress']['pre_project']->quotation_id) && !empty($data['progress']['pre_project']->quotation_id)) {
                                                    echo URLROOT . '/client/downloadQuotation/' . $data['progress']['pre_project']->quotation_id;
                                                }
                                            ?>" class="btn-download" target="_blank" onClick="event.preventDefault(); window.location.href=this.href;">
                                                <i class='bx bx-download'></i> Download
                                            </a>
                                        </div>
                                    <?php elseif ($phase === 'site_visit'): ?>
                                        <a href="<?php echo URLROOT . '/client/siteVisit/' . $data['pre_project_id']; ?>" class="btn-view">
                                            View Details
                                        </a>
                                    <?php elseif ($phase === 'agreement'): ?>
                                        <div class="button-group">
                                            <a href="<?php 
                                                if (isset($data['quotation_id']) && !empty($data['quotation_id'])) {
                                                    echo URLROOT . '/client/downloadAgreement/' . $data['quotation_id'];
                                                } elseif (isset($data['progress']['pre_project']->quotation_id) && !empty($data['progress']['pre_project']->quotation_id)) {
                                                    echo URLROOT . '/client/downloadAgreement/' . $data['progress']['pre_project']->quotation_id;
                                                }
                                            ?>" class="btn-download" target="_blank" onClick="event.preventDefault(); window.location.href=this.href;">
                                                <i class='bx bx-download'></i> Download
                                            </a>
                                            <?php 
                                            // Check if agreement exists and is completed
                                            if (isset($data['agreement_id']) && !empty($data['agreement_id'])) : ?>
                                                <a href="<?php echo URLROOT . '/client/downloadAgreement/' . $data['agreement_id']; ?>" class="btn-download">
                                                    <i class='bx bx-download'></i> Download
                                                </a>
                                            <?php endif; ?>
                                        </div>
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
                    <?php if (
                        $data['progress']['project'] &&
                        $data['progress']['project']->current_phase === 'document_submission' &&
                        !isset($_SESSION['overlay_shown_' . $data['pre_project_id']])
                    ): ?>
                        <!-- Congratulations Overlay -->
                        <div class="congrats-overlay" id="congratsOverlay">
                            <div class="congrats-modal">
                                <div class="congrats-content">
                                    <i class='bx bx-medal success-icon'></i>
                                    <h2>Congratulations!</h2>
                                    <p>You've successfully completed the initial phases of your solar journey.</p>
                                    <p>You're halfway there! Let's continue with the First payment process.</p>
                                    <button onclick="closeCongratsOverlay()" class="btn-proceed">Let's Continue</button>
                                </div>
                            </div>
                        </div>

                        <?php $_SESSION['overlay_shown_' . $data['pre_project_id']] = true; ?>
                    <?php endif; ?>

                    <?php if ($data['progress']['project']): ?>
                        <?php foreach ($projectPhases as $phase => $info): ?>
                            <?php
                            $status = 'locked';
                            if ($phase === $currentPhase) {
                                $status = 'active';
                            } elseif (
                                array_search($phase, array_keys($projectPhases)) <
                                array_search($currentPhase, array_keys($projectPhases))
                            ) {
                                $status = 'completed';
                            }
                            ?>
                            <div class="progress-item <?php echo $status; ?>">
                                <div class="progress-dot"></div>
                                <div class="progress-info">
                                    <h3><?php echo $info['title']; ?></h3>
                                    <p><?php echo $info['description']; ?></p>
                                    <?php if ($status === 'active'): ?>
                                        <?php if ($phase === 'document_submission'): ?>
                                            <a href="<?php echo URLROOT . '/client/documents/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php elseif ($phase === 'first_payment'): ?>
                                            <a href="<?php echo URLROOT . '/client/firstPayment/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php elseif ($phase === 'installation'): ?>
                                            <a href="<?php echo URLROOT . '/client/installation/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php elseif ($phase === 'final_payment'): ?>
                                            <a href="<?php echo URLROOT . '/client/finalPayment/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php elseif ($phase === 'engineer_approval'): ?>
                                            <a href="<?php echo URLROOT . '/client/engineerApproval/' . $data['pre_project_id']; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo URLROOT . '/client/project/' . $data['pre_project_id'] . '/' . $phase; ?>" class="btn-proceed">
                                                Proceed Now
                                            </a>
                                        <?php endif; ?>
                                    <?php elseif ($status === 'completed'): ?>
                                        <a href="<?php echo URLROOT . '/client/project/' . $data['pre_project_id'] . '/' . $phase; ?>" class="btn-view">
                                            View Details
                                        </a>
                                        <span class="check-mark">✓</span>
                                    <?php else: ?>
                                        <span class="lock-icon">🔒</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
        <script src="<?php echo URLROOT; ?>/js/client/project.js"></script>
        <?php require APPROOT . '/views/client/footer.php'; ?>