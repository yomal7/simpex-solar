<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/paymentsReport.css">
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Payment Status Report</h1>
            <p class="report-date">Generated on: <?php echo date('F d, Y h:i A', strtotime($data['report_date'])); ?></p>
        </div>
        
        <div class="report-filters">
            <p>
                <strong>Time Period:</strong> 
                <?php 
                    switch($data['filters']['timeframe']) {
                        case 'today':
                            echo 'Today';
                            break;
                        case 'week':
                            echo 'This Week';
                            break;
                        case 'month':
                            echo 'This Month';
                            break;
                        case 'year':
                            echo 'This Year';
                            break;
                        default:
                            echo 'All Time';
                    }
                ?>
            </p>
            <p>
                <strong>Payment Type:</strong> 
                <?php 
                    if($data['filters']['payment_type'] == 'all') {
                        echo 'All Types';
                    } else {
                        echo ucwords($data['filters']['payment_type']);
                    }
                ?>
            </p>
        </div>
        
        <div class="report-summary">
            <?php
                // Initialize counters
                $totalPayments = 0;
                $totalAmount = 0;
                $projectFirstPayments = 0;
                $projectFinalPayments = 0;
                $storePayments = 0;
                
                // Calculate totals from type stats
                foreach($data['stats']['type_stats'] as $stat) {
                    $totalPayments += $stat->count;
                    $totalAmount += $stat->total_amount;
                    
                    if($stat->payment_type == 'project first payment') {
                        $projectFirstPayments = $stat->count;
                    } elseif($stat->payment_type == 'project final payment') {
                        $projectFinalPayments = $stat->count;
                    } elseif($stat->payment_type == 'store payment') {
                        $storePayments = $stat->count;
                    }
                }
            ?>
            
            <div class="summary-item">
                <h3>Total Payments</h3>
                <p><?php echo $totalPayments; ?></p>
            </div>
            <div class="summary-item">
                <h3>Total Amount</h3>
                <p>$<?php echo number_format($totalAmount, 2); ?></p>
            </div>
            <div class="summary-item">
                <h3>Project First Payments</h3>
                <p><?php echo $projectFirstPayments; ?></p>
            </div>
            <div class="summary-item">
                <h3>Project Final Payments</h3>
                <p><?php echo $projectFinalPayments; ?></p>
            </div>
        </div>
        
        <h2>Payment Data Table</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Related Project/Order</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['payments'] as $payment): ?>
                <tr>
                    <td><?php echo $payment->id; ?></td>
                    <td><?php echo $payment->customer_name; ?></td>
                    <td>$<?php echo number_format($payment->amount, 2); ?></td>
                    <td><span class="payment-type <?php echo str_replace(' ', '-', $payment->payment_type); ?>"><?php echo ucwords($payment->payment_type); ?></span></td>
                    <td>
                        <?php if($payment->payment_type == 'store payment'): ?>
                            Order #<?php echo $payment->order_id; ?>
                        <?php else: ?>
                            Project #<?php echo $payment->project_id; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($payment->created_at)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Data Analysis</h2>
        
        <h3>Payments by Type</h3>
        <table>
            <thead>
                <tr>
                    <th>Payment Type</th>
                    <th>Count</th>
                    <th>Total Amount</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['type_stats'] as $stat): ?>
                <tr>
                    <td><?php echo ucwords($stat->payment_type); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td>$<?php echo number_format($stat->total_amount, 2); ?></td>
                    <td><?php echo $totalPayments > 0 ? round(($stat->count / $totalPayments) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if(count($data['stats']['monthly_stats']) > 0): ?>
        <h3>Monthly Payments (This Year)</h3>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Payment Count</th>
                    <th>Total Amount</th>
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
                    <td>$<?php echo number_format($stat->total_amount, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        
        <?php if(count($data['stats']['daily_stats']) > 0): ?>
        <h3>Recent Daily Payments (Last 7 Days)</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Payment Count</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['daily_stats'] as $stat): ?>
                <tr>
                    <td><?php echo date('F d, Y', strtotime($stat->date)); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td>$<?php echo number_format($stat->total_amount, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        
        <div class="footer">
            <p>This report is confidential and intended for internal use only.</p>
            <p>© <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
            <p class="no-print">
                <button onclick="window.print()" style="background-color: #00695c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px; font-weight: bold;">
                    <i class="fas fa-print" style="margin-right: 5px;"></i> Print Report
                </button>
                <a href="<?php echo URLROOT ?>/chiefCoordinator/payments?timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" style="background-color: #555; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; display: inline-block; font-weight: bold;">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to Payments
                </a>
            </p>
        </div>
    </div>
</body>
</html>