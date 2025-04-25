<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/payroll.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/payslip.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
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
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="payslip-header">
                <h1>Employee Payslip</h1>
                <div>
                    <a href="<?php echo URLROOT ?>/hRAdministrator/payroll?month=<?php echo $data['month']; ?>&year=<?php echo $data['year']; ?>" class="btn back-btn">Back to Payroll</a>
                    <button class="btn primary-btn" onclick="printPayslip()">Print Payslip</button>
                </div>
            </div>
            
            <div class="payslip-container" id="payslip">
                <div class="payslip-header-section">
                    <div class="company-info">
                        <img src="<?php echo URLROOT; ?>/public/assets/logo.png" alt="Company Logo" class="company-logo">
                        <div class="company-details">
                            <h2>Simpex Solar</h2>
                            <p>123 Solar Street, Kuala Lumpur, Malaysia</p>
                            <p>Phone: +601-234-5678 | Email: hr@simpexsolar.com</p>
                        </div>
                    </div>
                    <div class="payslip-title">
                        <h1>PAYSLIP</h1>
                        <p>For the month of <?php echo date('F Y', strtotime($data['year'] . '-' . $data['month'] . '-01')); ?></p>
                    </div>
                </div>
                
                <div class="employee-details-section">
                    <div class="employee-info">
                        <h3>Employee Details</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Name:</span>
                                <span class="value"><?php echo $data['employee']->name; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Employee ID:</span>
                                <span class="value"><?php echo $data['employee']->employee_id; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Position:</span>
                                <span class="value"><?php echo $data['employee']->role; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Department:</span>
                                <span class="value">IT Department</span>
                            </div>
                        </div>
                    </div>
                    <div class="payment-info">
                        <h3>Payment Details</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Pay Period:</span>
                                <span class="value"><?php echo date('d M', strtotime($data['year'] . '-' . $data['month'] . '-01')); ?> - <?php echo date('d M Y', strtotime($data['year'] . '-' . $data['month'] . '-' . date('t', strtotime($data['year'] . '-' . $data['month'] . '-01')))); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Pay Date:</span>
                                <span class="value"><?php echo date('d M Y', strtotime($data['year'] . '-' . $data['month'] . '-25')); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Payment Method:</span>
                                <span class="value">Bank Transfer</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="salary-details-section">
                    <div class="earnings">
                        <h3>Earnings</h3>
                        <table class="payslip-table">
                            <tr>
                                <td>Basic Salary</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->basic_salary, 2); ?></td>
                            </tr>
                            <tr>
                                <td>Allowances</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->allowances, 2); ?></td>
                            </tr>
                            <tr>
                                <td>Overtime (<?php echo $data['payroll']->overtime_hours; ?> hours)</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->overtime_pay, 2); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td>Gross Pay</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->gross_pay, 2); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="deductions">
                        <h3>Deductions</h3>
                        <table class="payslip-table">
                            <tr>
                                <td>EPF (Employee Contribution)</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->deductions * 0.7, 2); ?></td>
                            </tr>
                            <tr>
                                <td>SOCSO</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->deductions * 0.2, 2); ?></td>
                            </tr>
                            <tr>
                                <td>Income Tax (PCB)</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->deductions * 0.1, 2); ?></td>
                            </tr>
                            <?php if($data['payroll']->late_penalty > 0): ?>
                            <tr>
                                <td>Late Penalty (<?php echo $data['payroll']->late_days; ?> days)</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->late_penalty, 2); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="total-row">
                                <td>Total Deductions</td>
                                <td class="amount">Rs. <?php echo number_format($data['payroll']->deductions + $data['payroll']->late_penalty, 2); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="net-pay-section">
                    <div class="net-pay-box">
                        <span class="label">Net Pay</span>
                        <span class="value">Rs. <?php echo number_format($data['payroll']->net_pay, 2); ?></span>
                    </div>
                </div>
                
                <div class="attendance-summary">
                    <h3>Attendance Summary</h3>
                    <div class="attendance-grid">
                        <div class="attendance-item">
                            <span class="label">Working Days</span>
                            <span class="value"><?php echo $data['payroll']->total_working_days; ?></span>
                        </div>
                        <div class="attendance-item">
                            <span class="label">Present Days</span>
                            <span class="value"><?php echo $data['payroll']->present_days; ?></span>
                        </div>
                        <div class="attendance-item">
                            <span class="label">Absent Days</span>
                            <span class="value"><?php echo $data['payroll']->absent_days; ?></span>
                        </div>
                        <div class="attendance-item">
                            <span class="label">Leave Days</span>
                            <span class="value"><?php echo $data['payroll']->leave_days; ?></span>
                        </div>
                        <div class="attendance-item">
                            <span class="label">Late Days</span>
                            <span class="value"><?php echo $data['payroll']->late_days; ?></span>
                        </div>
                        <div class="attendance-item">
                            <span class="label">Overtime Hours</span>
                            <span class="value"><?php echo $data['payroll']->overtime_hours; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="payslip-footer">
                    <p>This is a computer-generated payslip and does not require a signature.</p>
                    <p>For any queries regarding this payslip, please contact the HR department.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printPayslip() {
            window.print();
        }
    </script>

    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>
