<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/payroll.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/salary.css">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="active">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/payroll">
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
            <div class="salary-header">
                <h1>Manage Employee Salary</h1>
                <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="btn back-btn">Back to Employees</a>
            </div>

            <?php flash('salary_msg'); ?>
            
            <div class="employee-card">
                <div class="employee-info">
                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Employee Photo" class="employee-photo">
                    <div class="employee-details">
                        <h2><?php echo $data['employee']->name; ?></h2>
                        <p><span class="detail-label">Employee ID:</span> <?php echo $data['employee']->employee_id; ?></p>
                        <p><span class="detail-label">Role:</span> <?php echo $data['employee']->role; ?></p>
                        <p><span class="detail-label">Email:</span> <?php echo $data['employee']->email; ?></p>
                        <p><span class="detail-label">Phone:</span> <?php echo $data['employee']->phone; ?></p>
                    </div>
                </div>
            </div>

            <div class="salary-form-container">
                <h2>Salary Information</h2>
                <form action="<?php echo URLROOT; ?>/hRAdministrator/manageSalary" method="POST" class="salary-form">
                    <input type="hidden" name="employee_id" value="<?php echo $data['employee_id']; ?>">
                    
                    <div class="form-group">
                        <label for="basic_salary">Basic Salary (Rs.):</label>
                        <input 
                            type="number" 
                            name="basic_salary" 
                            id="basic_salary" 
                            step="0.01" 
                            min="0" 
                            value="<?php echo $data['basic_salary']; ?>" 
                            required
                            class="<?php echo (!empty($data['basic_salary_err'])) ? 'is-invalid' : ''; ?>"
                        >
                        <span class="invalid-feedback"><?php echo $data['basic_salary_err']; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="allowances">Allowances (Rs.):</label>
                        <input 
                            type="number" 
                            name="allowances" 
                            id="allowances" 
                            step="0.01" 
                            min="0" 
                            value="<?php echo $data['allowances']; ?>" 
                            required
                            class="<?php echo (!empty($data['allowances_err'])) ? 'is-invalid' : ''; ?>"
                        >
                        <span class="invalid-feedback"><?php echo $data['allowances_err']; ?></span>
                        <p class="help-text">Include housing, transport, and other allowances</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="deductions">Deductions (Rs.):</label>
                        <input 
                            type="number" 
                            name="deductions" 
                            id="deductions" 
                            step="0.01" 
                            min="0" 
                            value="<?php echo $data['deductions']; ?>" 
                            required
                            class="<?php echo (!empty($data['deductions_err'])) ? 'is-invalid' : ''; ?>"
                        >
                        <span class="invalid-feedback"><?php echo $data['deductions_err']; ?></span>
                        <p class="help-text">Include EPF and income tax</p>
                    </div>
                    
                    <div class="calculator-section">
                        <h3>Salary Calculator</h3>
                        <div class="calculator-grid">
                            <div class="calc-item">
                                <span class="calc-label">Basic Salary:</span>
                                <span class="calc-value" id="calc-basic">Rs. 0.00</span>
                            </div>
                            <div class="calc-item">
                                <span class="calc-label">Allowances:</span>
                                <span class="calc-value" id="calc-allowances">Rs. 0.00</span>
                            </div>
                            <div class="calc-item">
                                <span class="calc-label">Gross Salary:</span>
                                <span class="calc-value" id="calc-gross">Rs. 0.00</span>
                            </div>
                            <div class="calc-item">
                                <span class="calc-label">Deductions:</span>
                                <span class="calc-value" id="calc-deductions">Rs. 0.00</span>
                            </div>
                            <div class="calc-item total">
                                <span class="calc-label">Estimated Net Salary:</span>
                                <span class="calc-value" id="calc-net">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn primary-btn">Save Salary Information</button>
                        <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="btn cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Salary calculator functionality
        document.addEventListener('DOMContentLoaded', function() {
            const basicSalaryInput = document.getElementById('basic_salary');
            const allowancesInput = document.getElementById('allowances');
            const deductionsInput = document.getElementById('deductions');
            
            const calcBasic = document.getElementById('calc-basic');
            const calcAllowances = document.getElementById('calc-allowances');
            const calcGross = document.getElementById('calc-gross');
            const calcDeductions = document.getElementById('calc-deductions');
            const calcNet = document.getElementById('calc-net');
            
            function calculateSalary() {
                const basicSalary = parseFloat(basicSalaryInput.value) || 0;
                const allowances = parseFloat(allowancesInput.value) || 0;
                const deductions = parseFloat(deductionsInput.value) || 0;
                
                const grossSalary = basicSalary + allowances;
                const netSalary = grossSalary - deductions;
                
                calcBasic.textContent = 'Rs. ' + basicSalary.toFixed(2);
                calcAllowances.textContent = 'Rs. ' + allowances.toFixed(2);
                calcGross.textContent = 'Rs. ' + grossSalary.toFixed(2);
                calcDeductions.textContent = 'Rs. ' + deductions.toFixed(2);
                calcNet.textContent = 'Rs. ' + netSalary.toFixed(2);
            }
            
            // Calculate on page load
            calculateSalary();
            
            // Calculate on input change
            basicSalaryInput.addEventListener('input', calculateSalary);
            allowancesInput.addEventListener('input', calculateSalary);
            deductionsInput.addEventListener('input', calculateSalary);
        });
    </script>

    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>
