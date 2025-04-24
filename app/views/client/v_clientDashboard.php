<?php require APPROOT . '/views/client/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/dashboard.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
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
            <li class="active"><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/operationDashboard"><i class='bx bx-analyse'></i>Quotations and Projects</a></li>
            <li><a href="<?php echo URLROOT; ?>/store/orders"><i class='bx bx-store-alt'></i>My Orders</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->

        <div class="container dashboard">

            <!-- Navigation Menu -->
            <div class="top-menu">

                <div class="user-actions">
                    <div class="notification">
                        <i class='bx bx-bell'></i>
                        <span class="count"><?php echo isset($data['notification_count']) ? $data['notification_count'] : '0'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="dashboard-content">
                <!-- Status Cards -->
                <div class="status-cards">
                    <div class="status-card">
                        <div class="icon quotations">
                            <i class='bx bx-file'></i>
                        </div>
                        <div class="info">
                            <h3><?php echo isset($data['stats']['pending_quotations']) ? $data['stats']['pending_quotations'] : '0'; ?></h3>
                            <p>Pending Quotations</p>
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="icon projects">
                            <i class='bx bx-bulb'></i>
                        </div>
                        <div class="info">
                            <h3><?php echo isset($data['stats']['active_projects']) ? $data['stats']['active_projects'] : '0'; ?></h3>
                            <p>Active Projects</p>
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="icon notifications">
                            <i class='bx bx-bell'></i>
                        </div>
                        <div class="info">
                            <h3><?php echo isset($data['notification_count']) ? $data['notification_count'] : '0'; ?></h3>
                            <p>Notifications</p>
                        </div>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="welcome-card">
                    <img src="<?php echo !empty($data['customer']->profile_picture) ? URLROOT . '/public/uploads/profile_pictures/' . $data['customer']->profile_picture : URLROOT . '/public/assets/profile.png'; ?>" alt="Profile Image" class="profile-image">
                    <div class="greeting">
                        <h1 id="greeting-text">Good Morning, <?php echo isset($data['customer']->name) ? explode(' ', $data['customer']->name)[0] : 'Customer'; ?>!</h1>
                        <p>Welcome to your SimplEx Solar dashboard. Here's a summary of your projects and orders.</p>
                        <div class="actions">
                            <a href="<?php echo URLROOT; ?>/client/operationDashboard" class="btn">View My Projects</a>
                            <a href="<?php echo URLROOT; ?>/packages" class="btn outline">Get a Quote</a>
                        </div>
                    </div>
                </div>

                <!-- Projects Section -->
                <div class="projects-section">
                    <div class="section-header">
                        <h2><i class='bx bx-bulb'></i>My Projects</h2>
                        <a href="<?php echo URLROOT; ?>/client/operationDashboard" class="view-all">View All</a>
                    </div>
                    <div class="project-cards">
                        <?php if (isset($data['ongoingProjects']) && !empty($data['ongoingProjects'])): ?>
                            <?php foreach (array_slice($data['ongoingProjects'], 0, 2) as $project): ?>
                                <div class="project-card">
                                    <div class="project-header">
                                        <div class="project-title">
                                            <div class="project-icon solar">
                                                <i class='bx bx-sun'></i>
                                            </div>
                                            <div class="project-info">
                                                <h3><?php echo $project->package_name ?? 'Solar Installation'; ?></h3>
                                                <p><?php echo $project->package_type ?? 'On-Grid System'; ?></p>
                                            </div>
                                        </div>
                                        <div class="project-status active">Active</div>
                                    </div>
                                    <div class="project-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Current Phase:</span>
                                            <span class="detail-value"><?php echo ucfirst($project->current_phase ?? 'Installation'); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Start Date:</span>
                                            <span class="detail-value"><?php echo date('M d, Y', strtotime($project->created_at ?? 'now')); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Progress:</span>
                                            <span class="detail-value">
                                                <?php
                                                $progress = 0;
                                                // Check if the current phase is from pre-project or project
                                                if (isset($project->current_phase)) {
                                                    // Determine if this is a main project phase
                                                    $projectPhases = [
                                                        'document_submission',
                                                        'first_payment',
                                                        'installation',
                                                        'final_payment',
                                                        'engineer_approval',
                                                        'grid_connection',
                                                        'completed'
                                                    ];

                                                    // Pre-project phases (0-40%)
                                                    if (!in_array($project->current_phase, $projectPhases)) {
                                                        switch ($project->current_phase) {
                                                            case 'quotation':
                                                                $progress = 10;
                                                                break;
                                                            case 'site_visit':
                                                                $progress = 25;
                                                                break;
                                                            case 'agreement':
                                                                $progress = 40;
                                                                break;
                                                            default:
                                                                $progress = 10;
                                                                break;
                                                        }
                                                    }
                                                    // Main project phases (40-100%)
                                                    else {
                                                        switch ($project->current_phase) {
                                                            case 'document_submission':
                                                                $progress = 45;
                                                                break;
                                                            case 'first_payment':
                                                                $progress = 55;
                                                                break;
                                                            case 'installation':
                                                                $progress = 70;
                                                                break;
                                                            case 'final_payment':
                                                                $progress = 80;
                                                                break;
                                                            case 'engineer_approval':
                                                                $progress = 90;
                                                                break;
                                                            case 'grid_connection':
                                                                $progress = 95;
                                                                break;
                                                            case 'completed':
                                                                $progress = 100;
                                                                break;
                                                            default:
                                                                $progress = 50;
                                                                break;
                                                        }
                                                    }

                                                    // Use the calculated progress from the model if available
                                                    if (isset($project->progress_percentage)) {
                                                        $progress = $project->progress_percentage;
                                                    }
                                                }
                                                echo $progress . '%';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
                                    </div>
                                    <div class="project-actions">
                                        <a href="<?php echo URLROOT; ?>/client/project/<?php echo $project->pre_project_id ?? '#'; ?>" class="btn">View Details</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif (isset($data['quotations']) && !empty($data['quotations'])): ?>
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">
                                        <div class="project-icon solar">
                                            <i class='bx bx-file'></i>
                                        </div>
                                        <div class="project-info">
                                            <h3>Quotation Pending</h3>
                                            <p><?php echo $data['quotations'][0]->package_type ?? 'Solar System'; ?></p>
                                        </div>
                                    </div>
                                    <div class="project-status pending">Pending</div>
                                </div>
                                <div class="project-details">
                                    <div class="detail-row">
                                        <span class="detail-label">Requested:</span>
                                        <span class="detail-value"><?php echo date('M d, Y', strtotime($data['quotations'][0]->created_at ?? 'now')); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Location:</span>
                                        <span class="detail-value"><?php echo $data['quotations'][0]->nearest_city ?? 'N/A'; ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Status:</span>
                                        <span class="detail-value"><?php echo ucfirst($data['quotations'][0]->status ?? 'pending'); ?></span>
                                    </div>
                                </div>
                                <div class="project-actions">
                                    <a href="<?php echo URLROOT; ?>/client/viewQuotation/<?php echo $data['quotations'][0]->quotation_id ?? '#'; ?>" class="btn">View Quotation</a>
                                </div>
                            </div>
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">
                                        <div class="project-icon solar">
                                            <i class='bx bx-plus-circle'></i>
                                        </div>
                                        <div class="project-info">
                                            <h3>Start New Project</h3>
                                            <p>Get a custom solar solution</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-details" style="text-align: center; padding: 20px 0;">
                                    <p>Ready to start your solar journey? Get a quote for your custom solar solution.</p>
                                </div>
                                <div class="project-actions" style="justify-content: center;">
                                    <a href="<?php echo URLROOT; ?>/packages" class="btn">Browse Packages</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">
                                        <div class="project-icon solar">
                                            <i class='bx bx-plus-circle'></i>
                                        </div>
                                        <div class="project-info">
                                            <h3>Start New Project</h3>
                                            <p>Get a custom solar solution</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-details" style="text-align: center; padding: 20px 0;">
                                    <p>Ready to start your solar journey? Get a quote for your custom solar solution.</p>
                                </div>
                                <div class="project-actions" style="justify-content: center;">
                                    <a href="<?php echo URLROOT; ?>/packages" class="btn">Browse Packages</a>
                                </div>
                            </div>
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">
                                        <div class="project-icon solar">
                                            <i class='bx bx-help-circle'></i>
                                        </div>
                                        <div class="project-info">
                                            <h3>Need Help?</h3>
                                            <p>Learn about solar options</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-details" style="text-align: center; padding: 20px 0;">
                                    <p>Unsure about which solar system suits your needs? Our team can help you decide.</p>
                                </div>
                                <div class="project-actions" style="justify-content: center;">
                                    <a href="#" class="btn">Contact Support</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                <div class="orders-section">
                    <div class="section-header">
                        <h2><i class='bx bx-cart'></i>Recent Orders</h2>
                        <a href="<?php echo URLROOT; ?>/client/shop" class="view-all">View All</a>
                    </div>

                    <?php if (isset($data['recentOrders']) && !empty($data['recentOrders'])): ?>
                        <div class="orders-table-container">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($data['recentOrders'], 0, 5) as $order): ?>
                                        <tr>
                                            <td>#<?php echo str_pad($order->id, 4, '0', STR_PAD_LEFT); ?></td>
                                            <td>
                                                <div class="product-info">
                                                    <img src="<?php echo !empty($order->image1) ? URLROOT . '/public/' . $order->image1 : URLROOT . '/public/assets/product_placeholder.jpg'; ?>" alt="Product Image">
                                                    <div class="product-details">
                                                        <h4><?php echo $order->product_name ?? 'Solar Product'; ?></h4>
                                                        <p>Qty: <?php echo $order->quantity ?? '1'; ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($order->created_at)) ?></td>
                                            <td>Rs. <?php echo number_format($order->price * $order->quantity + $order->delivery_fee, 2); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'pending';
                                                if ($order->status === 'delivered' || $order->status === 'ready for pickup') {
                                                    $statusClass = 'delivered';
                                                } elseif ($order->status === 'processing' || $order->status === 'approved') {
                                                    $statusClass = 'processing';
                                                }
                                                ?>
                                                <span class="order-status <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($order->status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/client/confirmOrder/<?php echo $order->id; ?>" class="btn-sm">Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 30px 0;">
                            <p>You don't have any orders yet.</p>
                            <a href="<?php echo URLROOT; ?>/store" class="btn" style="display: inline-block; margin-top: 15px;">Browse Shop</a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>

        <script>
            // Set greeting based on time of day
            function setGreeting() {
                const hour = new Date().getHours();
                let greeting;

                if (hour < 12) {
                    greeting = 'Good Morning';
                } else if (hour < 18) {
                    greeting = 'Good Afternoon';
                } else {
                    greeting = 'Good Evening';
                }

                const greetingElement = document.getElementById('greeting-text');
                if (greetingElement) {
                    const customerName = '<?php echo isset($data['customer']->name) ? explode(' ', $data['customer']->name)[0] : 'Customer'; ?>';
                    greetingElement.textContent = `${greeting}, ${customerName}!`;
                }
            }

            // Toggle profile dropdown
            document.addEventListener('DOMContentLoaded', function() {
                // Set greeting on page load
                setGreeting();


                // FAQ toggles
                const faqQuestions = document.querySelectorAll('.faq-question');
                if (faqQuestions.length > 0) {
                    faqQuestions.forEach(question => {
                        question.addEventListener('click', function() {
                            this.classList.toggle('active');
                            const answer = this.nextElementSibling;
                            answer.classList.toggle('active');
                        });
                    });
                }
            });
        </script>
        <script src="<?php echo URLROOT; ?>/js/client/dashboard.js"></script>
        <?php require APPROOT . '/views/client/footer.php'; ?>