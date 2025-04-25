<!-- File: app/views/chiefCoordinator/v_preProjectsReport.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Projects Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/preProjectReport.css">
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Pre-Projects Status Report</h1>
            <p class="report-date">Generated on: <?php echo date('F d, Y h:i A', strtotime($data['report_date'])); ?></p>
        </div>
        
        <div class="report-summary">
            <div class="summary-item">
                <h3>Total Pre-Projects</h3>
                <p><?php 
                    $totalPreProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        $totalPreProjects += $stat->count;
                    }
                    echo $totalPreProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Active Pre-Projects</h3>
                <p><?php 
                    $activePreProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        if($stat->status == 'active') {
                            $activePreProjects = $stat->count;
                            break;
                        }
                    }
                    echo $activePreProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Completed Pre-Projects</h3>
                <p><?php 
                    $completedPreProjects = 0;
                    foreach($data['stats']['status_stats'] as $stat) {
                        if($stat->status == 'completed') {
                            $completedPreProjects = $stat->count;
                            break;
                        }
                    }
                    echo $completedPreProjects;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Accepted Quotations</h3>
                <p><?php 
                    $acceptedQuotations = 0;
                    foreach($data['stats']['quotation_stats'] as $stat) {
                        if($stat->status == 'accepted_by_customer') {
                            $acceptedQuotations = $stat->count;
                            break;
                        }
                    }
                    echo $acceptedQuotations;
                ?></p>
            </div>
        </div>
        
        <h2>Pre-Projects Data Table</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Current Phase</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Quotations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['pre_projects'] as $project): ?>
                <tr>
                    <td><?php echo $project->pre_project_id; ?></td>
                    <td><?php echo $project->customer_name; ?></td>
                    <td><span class="status-pill phase-<?php echo $project->current_phase; ?>"><?php echo ucfirst($project->current_phase); ?></span></td>
                    <td><span class="status-pill status-<?php echo $project->status; ?>"><?php echo ucfirst($project->status); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($project->created_at)); ?></td>
                    <td><?php echo $project->quotation_count; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Data Analysis</h2>
        
        <h3>Pre-Projects by Status</h3>
        <table>
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
                    <td><?php echo round(($stat->count / $totalPreProjects) * 100, 1); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Active Pre-Projects by Phase</h3>
        <table>
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
                    <td><?php echo ucfirst($stat->current_phase); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td><?php echo $totalActiveProjects > 0 ? round(($stat->count / $totalActiveProjects) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Quotations by Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['quotation_stats'] as $stat): ?>
                <tr>
                    <td><?php echo str_replace('_', ' ', ucfirst($stat->status)); ?></td>
                    <td><?php echo $stat->count; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <p>This report is confidential and intended for internal use only.</p>
            <p>© <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
            <p class="no-print">
                <button onclick="window.print()" style="background-color: #2E7D32; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px; font-weight: bold;">
                    <i class="fas fa-print" style="margin-right: 5px;"></i> Print Report
                </button>
                <a href="<?php echo URLROOT ?>/chiefCoordinator/preProjects" style="background-color: #555; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; display: inline-block; font-weight: bold;">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to Pre-Projects
                </a>
            </p>
        </div>
    </div>
</body>
</html>