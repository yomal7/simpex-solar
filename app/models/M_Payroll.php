<?php
class M_Payroll
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all employees for payroll
    public function getAllEmployeesForPayroll() {
        $this->db->query('SELECT e.*, u.name, s.basic_salary, s.allowances, s.deductions 
                          FROM employees e 
                          INNER JOIN users u ON e.user_id = u.user_id 
                          LEFT JOIN salary s ON e.employee_id = s.employee_id
                          ORDER BY u.name');
        return $this->db->resultSet();
    }

    // Get employee salary details
    public function getEmployeeSalary($employeeId) {
        $this->db->query('SELECT * FROM salary WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->single();
    }

    // Update or insert employee salary
    public function updateSalary($data) {
        // Check if salary record exists
        $this->db->query('SELECT * FROM salary WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $data['employee_id']);
        $exists = $this->db->single();

        if ($exists) {
            // Update existing record
            $this->db->query('UPDATE salary SET basic_salary = :basic_salary, 
                             allowances = :allowances, deductions = :deductions 
                             WHERE employee_id = :employee_id');
        } else {
            // Insert new record
            $this->db->query('INSERT INTO salary (employee_id, basic_salary, allowances, deductions) 
                             VALUES (:employee_id, :basic_salary, :allowances, :deductions)');
        }

        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':basic_salary', $data['basic_salary']);
        $this->db->bind(':allowances', $data['allowances']);
        $this->db->bind(':deductions', $data['deductions']);

        return $this->db->execute();
    }

    // Calculate monthly payroll for an employee
    public function calculateMonthlyPayroll($employeeId, $month, $year) {
        // Get employee salary details
        $salary = $this->getEmployeeSalary($employeeId);
        if (!$salary) {
            return false;
        }

        // Get attendance records for the month
        $firstDay = date('Y-m-d', strtotime("$year-$month-01"));
        $lastDay = date('Y-m-t', strtotime("$year-$month-01"));
        
        $this->db->query('SELECT COUNT(*) AS present_days FROM attendance 
                          WHERE employee_id = :employee_id 
                          AND date BETWEEN :start_date AND :end_date 
                          AND time_in IS NOT NULL');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':start_date', $firstDay);
        $this->db->bind(':end_date', $lastDay);
        $presentDays = $this->db->single()->present_days;
        
        // Get total working days in the month (excluding weekends)
        $totalWorkingDays = $this->getWorkingDaysInMonth($month, $year);
        
        // Calculate leave days
        $this->db->query('SELECT COUNT(*) AS leave_days FROM holidayrecords 
                          WHERE employee_id = :employee_id 
                          AND leave_type != "unpaid" 
                          AND start_date BETWEEN :start_date AND :end_date');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':start_date', $firstDay);
        $this->db->bind(':end_date', $lastDay);
        $leaveDays = $this->db->single()->leave_days;
        
        // Calculate unpaid leave days
        $this->db->query('SELECT COUNT(*) AS unpaid_leave_days FROM holidayrecords 
                          WHERE employee_id = :employee_id 
                          AND leave_type = "unpaid" 
                          AND start_date BETWEEN :start_date AND :end_date');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':start_date', $firstDay);
        $this->db->bind(':end_date', $lastDay);
        $unpaidLeaveDays = $this->db->single()->unpaid_leave_days;
        
        // Calculate absent days (excluding leave days)
        $absentDays = $totalWorkingDays - $presentDays - $leaveDays - $unpaidLeaveDays;
        if ($absentDays < 0) $absentDays = 0;
        
        // Calculate late days (arrival after 9:00 AM)
        $this->db->query('SELECT COUNT(*) AS late_days FROM attendance 
                          WHERE employee_id = :employee_id 
                          AND date BETWEEN :start_date AND :end_date 
                          AND TIME(time_in) > "09:00:00"');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':start_date', $firstDay);
        $this->db->bind(':end_date', $lastDay);
        $lateDays = $this->db->single()->late_days;
        
        // Calculate overtime hours
        $this->db->query('SELECT SUM(TIMESTAMPDIFF(HOUR, "17:00:00", time_out)) AS overtime_hours 
                          FROM attendance 
                          WHERE employee_id = :employee_id 
                          AND date BETWEEN :start_date AND :end_date 
                          AND TIME(time_out) > "17:00:00"');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':start_date', $firstDay);
        $this->db->bind(':end_date', $lastDay);
        $overtimeHours = $this->db->single()->overtime_hours ?? 0;
        
        // Calculate daily rate
        $dailyRate = $salary->basic_salary / $totalWorkingDays;
        
        // Calculate basic pay (accounting for absences and unpaid leaves)
        $basicPay = $salary->basic_salary - (($absentDays + $unpaidLeaveDays) * $dailyRate);
        
        // Calculate overtime pay (1.5x hourly rate)
        $hourlyRate = $dailyRate / 8; // Assuming 8 working hours per day
        $overtimePay = $overtimeHours * ($hourlyRate * 1.5);
        
        // Calculate late penalties (if 3 or more late days, deduct half day salary)
        $latePenalty = ($lateDays >= 3) ? ($dailyRate * 0.5) : 0;
        
        // Calculate gross pay
        $grossPay = $basicPay + $salary->allowances + $overtimePay;
        
        // Calculate net pay
        $netPay = $grossPay - $salary->deductions - $latePenalty;
        
        // Create payroll record
        $payroll = (object)[
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year,
            'basic_salary' => $salary->basic_salary,
            'allowances' => $salary->allowances,
            'basic_pay' => $basicPay,
            'overtime_hours' => $overtimeHours,
            'overtime_pay' => $overtimePay,
            'late_days' => $lateDays,
            'late_penalty' => $latePenalty,
            'gross_pay' => $grossPay,
            'deductions' => $salary->deductions,
            'net_pay' => $netPay,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'total_working_days' => $totalWorkingDays
        ];
        
        return $payroll;
    }
    
    // Calculate working days in a month (excluding weekends)
    private function getWorkingDaysInMonth($month, $year) {
        $totalDays = date('t', strtotime("$year-$month-01"));
        $workingDays = 0;
        
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = date('Y-m-d', strtotime("$year-$month-$day"));
            $dayOfWeek = date('N', strtotime($date));
            
            // If not weekend (not 6 or 7, which are Saturday and Sunday)
            if ($dayOfWeek < 6) {
                $workingDays++;
            }
        }
        
        return $workingDays;
    }
    
    // Save payroll record to database
    public function savePayroll($payroll) {
        // Check if a record already exists for this month/year/employee
        $this->db->query('SELECT * FROM payroll WHERE employee_id = :employee_id AND month = :month AND year = :year');
        $this->db->bind(':employee_id', $payroll->employee_id);
        $this->db->bind(':month', $payroll->month);
        $this->db->bind(':year', $payroll->year);
        $exists = $this->db->single();
        
        if ($exists) {
            // Update existing record
            $this->db->query('UPDATE payroll SET basic_salary = :basic_salary, allowances = :allowances, 
                             basic_pay = :basic_pay, overtime_hours = :overtime_hours, overtime_pay = :overtime_pay,
                             late_days = :late_days, late_penalty = :late_penalty, gross_pay = :gross_pay,
                             deductions = :deductions, net_pay = :net_pay, pay_date = NOW() 
                             WHERE employee_id = :employee_id AND month = :month AND year = :year');
        } else {
            // Insert new record
            $this->db->query('INSERT INTO payroll (employee_id, month, year, basic_salary, allowances, basic_pay,
                             overtime_hours, overtime_pay, late_days, late_penalty, gross_pay, deductions,
                             net_pay, pay_date) VALUES (:employee_id, :month, :year, :basic_salary, :allowances,
                             :basic_pay, :overtime_hours, :overtime_pay, :late_days, :late_penalty, :gross_pay,
                             :deductions, :net_pay, NOW())');
        }
        
        $this->db->bind(':employee_id', $payroll->employee_id);
        $this->db->bind(':month', $payroll->month);
        $this->db->bind(':year', $payroll->year);
        $this->db->bind(':basic_salary', $payroll->basic_salary);
        $this->db->bind(':allowances', $payroll->allowances);
        $this->db->bind(':basic_pay', $payroll->basic_pay);
        $this->db->bind(':overtime_hours', $payroll->overtime_hours);
        $this->db->bind(':overtime_pay', $payroll->overtime_pay);
        $this->db->bind(':late_days', $payroll->late_days);
        $this->db->bind(':late_penalty', $payroll->late_penalty);
        $this->db->bind(':gross_pay', $payroll->gross_pay);
        $this->db->bind(':deductions', $payroll->deductions);
        $this->db->bind(':net_pay', $payroll->net_pay);
        
        return $this->db->execute();
    }
    
    // Get saved payroll record
    public function getPayroll($employeeId, $month, $year) {
        $this->db->query('SELECT * FROM payroll WHERE employee_id = :employee_id AND month = :month AND year = :year');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->single();
    }
    
    // Get all payrolls for a specific month/year
    public function getMonthlyPayrolls($month, $year) {
        $this->db->query('SELECT p.*, u.name as employee_name 
                          FROM payroll p 
                          INNER JOIN employees e ON p.employee_id = e.employee_id 
                          INNER JOIN users u ON e.user_id = u.user_id 
                          WHERE p.month = :month AND p.year = :year 
                          ORDER BY u.name');
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }
}