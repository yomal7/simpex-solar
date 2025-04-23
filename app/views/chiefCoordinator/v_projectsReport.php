<!-- File: app/views/chiefCoordinator/v_projectsReport.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/projectReport.css">
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="SimplEx Solar Solutions Logo" class="logo">
            <div class="header-text">
                <h1>Solar Projects Status Report</h1>
                <p class="report-date">Generated on: <?php echo date('F d, Y h:i A', strtotime($data['report_date'])); ?></p>
                <?php if($data['timeframe'] != 'all'): ?>
                    <p class="report-filter">Timeframe: <?php echo ucfirst($data['timeframe']); ?></p>
                <?php endif; ?>
                <?php if($data['phase_filter'] != 'all'): ?>
                    <p class="report-filter">Phase: <?php echo str_replace('_', ' ', ucfirst($data['phase_filter'])); ?></p>
                <?php endif; ?>
                <?php if($data['status_filter'] != 'all'): ?>
                    <p class="report-filter">Status: <?php echo ucfirst($data['status_filter']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="report-summary">
            <div class="summary-item">
                <h3>Total Projects</h3>
                <p><?php 
                    $totalProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        $totalProjects += $stat->count;
                    }
                    echo $totalProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Active Projects</h3>
                <p><?php 
                    $activeProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        if($stat->status == 'active') {
                            $activeProjects = $stat->count;
                            break;
                        }
                    }
                    echo $activeProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Completed Projects</h3>
                <p><?php 
                    $completedProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        if($stat->status == 'completed') {
                            $completedProjects = $stat->count;
                            break;
                        }
                    }
                    echo $completedProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Equipment Released</h3>
                <p><?php 
                    $equipmentReleased = 0;
                    foreach($data['stats']['equipment_stats'] as $stat) {
                        if($stat->equipment_released == 1) {
                            $equipmentReleased = $stat->count;
                            break;
                        }
                    }
                    echo $equipmentReleased;
                ?></p>
            </div>
        </div>
        
        <h2>Projects List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Current Phase</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Equipment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['projects'] as $project): ?>
                <tr>
                    <td><?php echo $project->project_id; ?></td>
                    <td><?php echo $project->customer_name; ?></td>
                    <td><?php echo $project->package_name; ?></td>
                    <td><span class="status-pill phase-<?php echo $project->current_phase; ?>"><?php echo str_replace('_', ' ', ucfirst($project->current_phase)); ?></span></td>
                    <td><span class="status-pill status-<?php echo $project->status; ?>"><?php echo ucfirst($project->status); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($project->created_at)); ?></td>
                    <td>
                        <?php if($project->equipment_released == 1): ?>
                            <span class="status-pill status-active">Released</span>
                        <?php else: ?>
                            <span class="status-pill status-cancelled">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Data Analysis</h2>
        
        <div class="analysis-section">
            <h3>Projects by Status</h3>
            <table class="analysis-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['stats']['status_stats'] as $stat): ?>
                    <tr>
                        <td><?php echo ucfirst($stat->status); ?></td>
                        <td><?php echo $stat->count; ?></td>
                        <td><?php echo $totalProjects > 0 ? round(($stat->count / $totalProjects) * 100, 1) : 0; ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="analysis-section">
            <h3>Active Projects by Phase</h3>
            <table class="analysis-table">
                <thead>
                    <tr>
                        <th>Phase</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalActiveProjects = 0;
                    foreach($data['stats']['phase_stats'] as $stat) {
                        $totalActiveProjects += $stat->count;
                    }
                    foreach($data['stats']['phase_stats'] as $stat): 
                    ?>
                    <tr>
                        <td><?php echo str_replace('_', ' ', ucfirst($stat->current_phase)); ?></td>
                        <td><?php echo $stat->count; ?></td>
                        <td><?php echo $totalActiveProjects > 0 ? round(($stat->count / $totalActiveProjects) * 100, 1) : 0; ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="analysis-section">
            <h3>Average Days in Each Phase</h3>
            <table class="analysis-table">
                <thead>
                    <tr>
                        <th>Phase</th>
                        <th>Average Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['stats']['time_stats'] as $stat): ?>
                    <tr>
                        <td><?php echo str_replace('_', ' ', ucfirst($stat->current_phase)); ?></td>
                        <td><?php echo round($stat->avg_days, 1); ?> days</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="analysis-section">
            <h3>Equipment Release Status</h3>
            <table class="analysis-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalEquipmentProjects = 0;
                    foreach($data['stats']['equipment_stats'] as $stat) {
                        $totalEquipmentProjects += $stat->count;
                    }
                    foreach($data['stats']['equipment_stats'] as $stat): 
                    ?>
                    <tr>
                        <td><?php echo $stat->equipment_released == 1 ? 'Released' : 'Pending'; ?></td>
                        <td><?php echo $stat->count; ?></td>
                        <td><?php echo $totalEquipmentProjects > 0 ? round(($stat->count / $totalEquipmentProjects) * 100, 1) : 0; ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="analysis-section">
            <h3>Monthly Projects Distribution (This Year)</h3>
            <table class="analysis-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    foreach($data['stats']['monthly_stats'] as $stat): 
                    ?>
                    <tr>
                        <td><?php echo $monthNames[$stat->month - 1]; ?></td>
                        <td><?php echo $stat->count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>This report is confidential and intended for internal use only.</p>
            <p>© <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
            <p class="no-print">
                <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
                <button onclick="window.print()" class="print-btn">
                    <span class="material-icons-sharp">print</span> Print Report
                </button>
                <a href="<?php echo URLROOT ?>/chiefCoordinator/projects" class="back-btn">
                    <span class="material-icons-sharp">arrow_back</span> Back to Projects
                </a>
            </p>
        </div>
    </div>
</body>
</html>