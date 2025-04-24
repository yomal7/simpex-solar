<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/storeReport.css">
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Store Status Report</h1>
            <p class="report-date">Generated on: <?php echo date('F d, Y h:i A', strtotime($data['report_date'])); ?></p>
            <p class="timeframe">
                Timeframe: 
                <?php 
                    switch($data['timeframe']) {
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
        </div>
        
        <div class="report-summary">
            <div class="summary-item">
                <h3>Total Orders</h3>
                <p><?php 
                    $totalOrders = 0;
                    foreach($data['stats']['order_status_stats'] as $stat) {
                        $totalOrders += $stat->count;
                    }
                    echo $totalOrders;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Total Income</h3>
                <p>Rs. <?php echo number_format($data['stats']['income_stats']->total_income, 2); ?></p>
            </div>
            <div class="summary-item">
                <h3>Delivered Orders</h3>
                <p><?php 
                    $deliveredOrders = 0;
                    foreach($data['stats']['order_status_stats'] as $stat) {
                        if($stat->status == 'delivered') {
                            $deliveredOrders = $stat->count;
                            break;
                        }
                    }
                    echo $deliveredOrders;
                ?></p>
            </div>
            <div class="summary-item">
                <h3>Pending Orders</h3>
                <p><?php 
                    $pendingOrders = 0;
                    foreach($data['stats']['order_status_stats'] as $stat) {
                        if($stat->status == 'pending') {
                            $pendingOrders = $stat->count;
                            break;
                        }
                    }
                    echo $pendingOrders;
                ?></p>
            </div>
        </div>
        
        <h2>Orders Data Table</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['orders'] as $order): ?>
                <tr>
                    <td><?php echo $order->id; ?></td>
                    <td><?php echo $order->customer_name; ?></td>
                    <td><?php echo $order->product_name; ?></td>
                    <td><?php echo $order->quantity; ?></td>
                    <td>Rs. <?php echo number_format(($order->price * $order->quantity) + $order->delivery_fee - ($order->discount ?? 0), 2); ?></td>
                    <td><span class="status-pill status-<?php echo str_replace(' ', '-', $order->status); ?>"><?php echo ucwords($order->status); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Data Analysis</h2>
        
        <h3>Orders by Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['stats']['order_status_stats'] as $stat): ?>
                <tr>
                    <td><?php echo ucwords(str_replace('_', ' ', $stat->status)); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td><?php echo $totalOrders > 0 ? round(($stat->count / $totalOrders) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        
        <h3>Payment Methods</h3>
        <table>
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalPayments = 0;
                foreach($data['stats']['payment_method_stats'] as $stat) {
                    $totalPayments += $stat->count;
                }
                foreach($data['stats']['payment_method_stats'] as $stat): 
                ?>
                <tr>
                    <td><?php echo ucwords(str_replace('_', ' ', $stat->payment_method)); ?></td>
                    <td><?php echo $stat->count; ?></td>
                    <td><?php echo $totalPayments > 0 ? round(($stat->count / $totalPayments) * 100, 1) : 0; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Monthly Orders (This Year)</h3>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Orders</th>
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
        
        <div class="footer">
            <p>This report is confidential and intended for internal use only.</p>
            <p>© <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
            <p class="no-print">
                <button onclick="window.print()" style="background-color: #2E7D32; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px; font-weight: bold;">
                    <i class="material-icons-sharp">print</i> Print Report
                </button>
                <a href="<?php echo URLROOT ?>/chiefCoordinator/store" style="background-color: #555; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; display: inline-block; font-weight: bold;">
                    <i class="material-icons-sharp">arrow_back</i> Back to Store
                </a>
            </p>
        </div>
    </div>
</body>
</html>