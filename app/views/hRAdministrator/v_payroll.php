<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/payroll.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/payroll" class="active">
                <span class="material-icons-sharp">money</span>
                <h3>Payroll</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="payroll-header">
                <h1>Monthly Payroll</h1>
                <div class="payroll-actions">
                    <button id="generateAllBtn" class="btn primary-btn">Generate All Payrolls</button>
                </div>
            </div>

            <?php flash('payroll_msg'); ?>
            
            <div class="date-filter-container">
                <form action="" method="GET" class="month-selector-form">
                    <div class="form-group">
                        <label for="month">Month:</label>
                        <select name="month" id="month" onchange="this.form.submit()">
                            <?php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            for ($i = 1; $i <= 12; $i++) {
                                $selected = ($i == $data['month']) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>{$months[$i-1]}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <select name="year" id="year" onchange="this.form.submit()">
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
                                $selected = ($i == $data['year']) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
                
                <div class="generate-payroll-form">
                    <form action="<?php echo URLROOT; ?>/hRAdministrator/generatePayroll" method="POST">
                        <input type="hidden" name="month" value="<?php echo $data['month']; ?>">
                        <input type="hidden" name="year" value="<?php echo $data['year']; ?>">
                        <div class="form-group">
                            <label for="employee_id">Generate payroll for:</label>
                            <select name="employee_id" id="employee_id" required>
                                <option value="">Select Employee</option>
                                <?php foreach($data['employees'] as $employee) : ?>
                                    <option value="<?php echo $employee->employee_id; ?>"><?php echo $employee->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn primary-btn">Generate</button>
                    </form>
                </div>
            </div>
            
            <div class="payroll-summary">
                <div class="summary-card">
                    <div class="card-value"><?php echo count($data['payrolls']); ?></div>
                    <div class="card-label">Payrolls Generated</div>
                </div>
                <div class="summary-card">
                    <div class="card-value">
                        <?php 
                            $totalNet = 0;
                            foreach($data['payrolls'] as $payroll) {
                                $totalNet += $payroll->net_pay;
                            }
                            echo 'Rs. ' . number_format($totalNet, 2);
                        ?>
                    </div>
                    <div class="card-label">Total Payout</div>
                </div>
                <div class="summary-card">
                    <div class="card-value">
                        <?php echo date('d M Y', strtotime($data['year'] . '-' . $data['month'] . '-25')); ?>
                    </div>
                    <div class="card-label">Pay Date</div>
                </div>
            </div>
            
            <div class="payroll-table-container">
                <table class="payroll-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Present Days</th>
                            <th>Absent Days</th>
                            <th>Overtime</th>
                            <th>Net Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['payrolls'])) : ?>
                            <?php foreach($data['payrolls'] as $payroll) : ?>
                                <tr>
                                    <td><?php echo $payroll->employee_name; ?></td>
                                    <td>Rs. <?php echo number_format($payroll->basic_salary, 2); ?></td>
                                    <td>Rs. <?php echo number_format($payroll->allowances, 2); ?></td>
                                    <td>Rs. <?php echo number_format($payroll->deductions, 2); ?></td>
                                    <td><?php echo $payroll->present_days; ?></td>
                                    <td><?php echo $payroll->absent_days; ?></td>
                                    <td><?php echo $payroll->overtime_hours; ?> hrs</td>
                                    <td class="net-pay">Rs. <?php echo number_format($payroll->net_pay, 2); ?></td>
                                    <td class="action-buttons">
                                        <a href="<?php echo URLROOT; ?>/hRAdministrator/viewPayslip/<?php echo $payroll->employee_id; ?>/<?php echo $payroll->month; ?>/<?php echo $payroll->year; ?>" class="btn view-btn">View Slip</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="no-data">No payroll data available for the selected month. Generate payrolls using the form above.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('generateAllBtn').addEventListener('click', function() {
            if (confirm('Generate payroll for all employees for the selected month?')) {
                // Here you would typically submit a form or make an AJAX request
                // For simplicity, we'll just show an alert for now
                alert('This would generate payroll for all employees. Implementation pending.');
                // In a real implementation, you might redirect to a controller method that handles bulk generation
            }
        });
    </script>

    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>