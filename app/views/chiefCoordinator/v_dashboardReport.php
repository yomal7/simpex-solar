<?php
// Setting header for PDF or printable report
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Report - <?php echo date('Y-m-d'); ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/dashboardReport.css">
</head>
<body>
    <div class="report-header">
        <div class="report-title">Dashboard Summary Report</div>
        <div class="report-subtitle">
            <?php 
                switch ($data['timeframe']) {
                    case 'today':
                        echo 'Today\'s Report (' . date('F j, Y') . ')';
                        break;
                    case 'week':
                        echo 'Weekly Report (Week of ' . date('F j, Y', strtotime('last sunday')) . ')';
                        break;
                    case 'month':
                        echo 'Monthly Report (' . date('F Y') . ')';
                        break;
                    case 'year':
                        echo 'Yearly Report (' . date('Y') . ')';
                        break;
                    default:
                        echo 'All Time Report';
                }
            ?>
        </div>
        <div class="report-meta">
            Generated on: <?php echo date('F j, Y, g:i a', strtotime($data['report_date'])); ?>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Executive Summary</div>
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Active Projects</h3>
                <p>
                    <?php 
                        $activeProjects = 0;
                        foreach($data['project_stats']['status_stats'] as $stat) {
                            if($stat->status == 'active') {
                                $activeProjects = $stat->count;
                                break;
                            }
                        }
                        echo $activeProjects;
                    ?>
                </p>
            </div>
            
            <div class="summary-card">
                <h3>Monthly Revenue</h3>
                <p>
                    <?php 
                        $totalMonthlyRevenue = 0;
                        foreach($data['payment_stats']['monthly_stats'] as $stat) {
                            if($stat->month == date('n')) { // Current month
                                $totalMonthlyRevenue = '$' . number_format($stat->total_amount);
                                break;
                            }
                        }
                        echo $totalMonthlyRevenue;
                    ?>
                </p>
            </div>
            
            <div class="summary-card">
                <h3>Store Orders</h3>
                <p>
                    <?php 
                        $totalOrders = 0;
                        foreach($data['store_stats']['order_status_stats'] as $stat) {
                            $totalOrders += $stat->count;
                        }
                        echo $totalOrders;
                    ?>
                </p>
            </div>
            
            <div class="summary-card">
                <h3>Attendance Rate</h3>
                <p><?php echo $data['attendance_rate']; ?>%</p>
            </div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Project Overview</div>
        
        <table>
            <tr>
                <th>Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
            <?php 
                $totalProjects = 0;
                foreach($data['project_stats']['status_stats'] as $stat) {
                    $totalProjects += $stat->count;
                }
                
                foreach($data['project_stats']['status_stats'] as $stat): 
                    $percentage = $totalProjects > 0 ? round(($stat->count / $totalProjects) * 100) : 0;
            ?>
            <tr>
                <td><?php echo ucfirst($stat->status); ?></td>
                <td><?php echo $stat->count; ?></td>
                <td><?php echo $percentage; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <p><strong>Project Phases Breakdown:</strong></p>
        <table>
            <tr>
                <th>Phase</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
            <?php 
                $totalActiveProjects = 0;
                foreach($data['project_stats']['phase_stats'] as $stat) {
                    $totalActiveProjects += $stat->count;
                }
                
                foreach($data['project_stats']['phase_stats'] as $stat): 
                    $percentage = $totalActiveProjects > 0 ? round(($stat->count / $totalActiveProjects) * 100) : 0;
            ?>
            <tr>
                <td><?php echo ucfirst($stat->current_phase); ?></td>
                <td><?php echo $stat->count; ?></td>
                <td><?php echo $percentage; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <p><strong>Recent Projects:</strong></p>
        <table>
            <tr>
                <th>Project</th>
                <th>Customer</th>
                <th>Package</th>
                <th>Phase</th>
                <th>Status</th>
            </tr>
            <?php foreach($data['projects'] as $project): ?>
            <tr>
                <td><?php echo $project->title ?? 'Untitled Project'; ?></td>
                <td><?php echo $project->customer_name; ?></td>
                <td><?php echo $project->package_name; ?></td>
                <td><?php echo ucfirst($project->current_phase ?? 'N/A'); ?></td>
                <td>
                    <span class="status-badge status-<?php echo strtolower($project->status); ?>">
                        <?php echo ucfirst($project->status); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="section page-break">
        <div class="section-title">Financial Overview</div>
        
        <table>
            <tr>
                <th>Payment Type</th>
                <th>Count</th>
                <th>Total Amount</th>
            </tr>
            <?php foreach($data['payment_stats']['type_stats'] as $stat): ?>
            <tr>
                <td><?php echo ucfirst($stat->payment_type); ?></td>
                <td><?php echo $stat->count; ?></td>
                <td>$<?php echo number_format($stat->total_amount, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <p><strong>Monthly Revenue Breakdown:</strong></p>
        <table>
            <tr>
                <th>Month</th>
                <th>Transactions</th>
                <th>Total Amount</th>
            </tr>
            <?php 
                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                foreach($data['payment_stats']['monthly_stats'] as $stat): 
            ?>
            <tr>
                <td><?php echo $months[$stat->month - 1]; ?></td>
                <td><?php echo $stat->count; ?></td>
                <td>$<?php echo number_format($stat->total_amount, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <p><strong>Recent Payments:</strong></p>
        <table>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Payment Type</th>
                <th>Amount</th>
            </tr>
            <?php foreach($data['payments'] as $payment): ?>
            <tr>
                <td><?php echo date('M d, Y', strtotime($payment->created_at)); ?></td>
                <td><?php echo $payment->customer_name ?? 'N/A'; ?></td>
                <td><?php echo ucfirst($payment->payment_type); ?></td>
                <td>$<?php echo number_format($payment->amount, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="section page-break">
        <div class="section-title">Store Performance</div>
        
        <table>
            <tr>
                <th>Order Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
            <?php 
                $totalStoreOrders = 0;
                foreach($data['store_stats']['order_status_stats'] as $stat) {
                    $totalStoreOrders += $stat->count;
                }
                
                foreach($data['store_stats']['order_status_stats'] as $stat): 
                    $percentage = $totalStoreOrders > 0 ? round(($stat->count / $totalStoreOrders) * 100) : 0;
            ?>
            <tr>
                <td><?php echo ucfirst($stat->status); ?></td>
                <td><?php echo $stat->count; ?></td>
                <td><?php echo $percentage; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <p><strong>Income Overview:</strong></p>
        <table>
            <tr>
                <th>Total Income</th>
                <th>Average Order Value</th>
            </tr>
            <tr>
                <td>$<?php echo number_format($data['store_stats']['income_stats']->total_income, 2); ?></td>
                <td>$<?php echo $totalStoreOrders > 0 ? number_format($data['store_stats']['income_stats']->total_income / $totalStoreOrders, 2) : 0; ?></td>
            </tr>
        </table>
        
        <p><strong>Recent Orders:</strong></p>
        <table>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            <?php foreach($data['store_orders'] as $order): ?>
            <tr>
                <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                <td><?php echo $order->customer_name; ?></td>
                <td><?php echo $order->product_name; ?></td>
                <td><?php echo $order->quantity; ?></td>
                <td>$<?php echo number_format(($order->price * $order->quantity) + $order->delivery_fee - ($order->discount ?? 0), 2); ?></td>
                <td>
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order->status)); ?>">
                        <?php echo ucfirst($order->status); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="section page-break">
        <div class="section-title">Employee Summary</div>
        
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Total Employees</h3>
                <p><?php echo $data['total_employees']; ?></p>
            </div>
            
            <div class="summary-card">
                <h3>Present Today</h3>
                <p><?php echo $data['present_today']; ?></p>
            </div>
            
            <div class="summary-card">
                <h3>Attendance Rate</h3>
                <p><?php echo $data['attendance_rate']; ?>%</p>
            </div>
            
            <div class="summary-card">
                <h3>On Leave</h3>
                <p>
                    <?php 
                        $totalLeaves = 0;
                        foreach($data['employee_stats']['leave_stats'] as $stat) {
                            $totalLeaves += $stat->count;
                        }
                        echo $totalLeaves;
                    ?>
                </p>
            </div>
        </div>
        
        <p><strong>Employee Roles Breakdown:</strong></p>
        <table>
            <tr>
                <th>Role</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
            <?php 
                foreach($data['employee_stats']['role_stats'] as $stat): 
                    $percentage = $data['total_employees'] > 0 ? round(($stat->count / $data['total_employees']) * 100) : 0;
            ?>
            <tr>
                <td><?php echo ucfirst($stat->role); ?></td>
                <td><?php echo $stat->count; ?></td>
                <td><?php echo $percentage; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="footer">
        <p>This report is confidential and intended only for authorized personnel.</p>
        <p>Generated from the Chief Coordinator Dashboard on <?php echo date('F j, Y', strtotime($data['report_date'])); ?></p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Report
        </button>
        <a href="<?php echo URLROOT; ?>/chiefCoordinator/index" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
            Back to Dashboard
        </a>
    </div>
</body>
</html>