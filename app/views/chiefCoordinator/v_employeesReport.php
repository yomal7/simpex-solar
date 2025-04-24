<!-- File: app/views/chiefCoordinator/v_employeesReport.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/employeeReport.css">
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Employee Status Report</h1>
            <p class="report-date">Generated on: <?php echo date('F d, Y h:i A', strtotime($data['report_date'])); ?></p>
        </div>
        
        <div class="report-summary">
            <div class="summary-item">
                <h3>Total Employees</h3>
                <p><?php 
                    $totalEmployees = 0;
                    foreach($data['stats']['role_stats'] as $stat) {
                        $totalEmployees += $stat->count;
                    }
                    echo $totalEmployees;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Present Today</h3>
                <p><?php 
                    $presentToday = 0;
                    foreach($data['stats']['attendance_stats'] as $stat) {
                        if(date('Y-m-d', strtotime($stat->date)) == date('Y-m-d')) {
                            $presentToday = $stat->present_count;
                            break;
                        }
                    }
                    echo $presentToday;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>On Leave</h3>
                <p><?php 
                    $totalLeaves = 0;
                    foreach($data['stats']['leave_stats'] as $stat) {
                        $totalLeaves += $stat->count;
                    }
                    echo $totalLeaves;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Attendance Rate</h3>
                <p><?php 
                    // Calculate attendance rate
                    $rate = ($totalEmployees > 0) ? round(($presentToday / $totalEmployees) * 100) : 0;
                    echo $rate . '%';
                ?></p>
            </div>
        </div>
        
        <h2>Employee Directory</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Attendance</th>
                    <th>Leaves</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['employees'] as $employee): ?>
                <tr>
                    <td><?php echo $employee->employee_id; ?></td>
                    <td><?php echo $employee->name; ?></td>
                    <td><span class="role-pill role-<?php echo $employee->role; ?>"><?php echo ucfirst($employee->role); ?></span></td>
                    <td><?php echo $employee->email; ?></td>
                    <td><?php echo $employee->contact_no; ?></td>
                    <td><?php echo $employee->attendance_count; ?> days</td>
                    <td><?php echo $employee->leave_count; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Data Analysis</h2>
        
        <h3>Employees by Role</h3>
        <table>
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['role_stats'] as $stat): ?>
                <tr>
                    <td><?php echo ucfirst($stat->role); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td><?php echo round(($stat->count / $totalEmployees) * 100, 1); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Recent Daily Attendance</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Present Count</th>
                    <th>Attendance Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['attendance_stats'] as $stat): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($stat->date)); ?></td>
                    <td><?php echo $stat->present_count; ?></td>
                    <td><?php echo ($totalEmployees > 0) ? round(($stat->present_count / $totalEmployees) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Leave Types Distribution</h3>
        <table>
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($data['stats']['leave_stats'] as $stat): 
                ?>
                <tr>
                    <td><?php echo ucfirst($stat->leave_type); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td><?php echo $totalLeaves > 0 ? round(($stat->count / $totalLeaves) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <p>This report is confidential and intended for internal use only.</p>
            <p>© <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
            <p class="no-print">
                <button onclick="window.print()" style="background-color: #7bb13c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px; font-weight: bold;">
                    <i class="fas fa-print" style="margin-right: 5px;"></i> Print Report
                </button>
                <a href="<?php echo URLROOT ?>/chiefCoordinator/employees" style="background-color: #555; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; display: inline-block; font-weight: bold;">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to Employees
                </a>
            </p>
        </div>
    </div>
</body>
</html>