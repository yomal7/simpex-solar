<?php

class HRAdministrator extends Controller
{
    private $employeeModel;
    private $userModel;
    private $attendanceModel;
    private $payrollModel;

    public function __construct()
    {
        // $this->clientModel = $this->model('M_Client');
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hRAdministrator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->userModel = $this->model("M_Users");
        $this->attendanceModel = $this->model('M_Attendance');
        $this->payrollModel = $this->model('M_Payroll');
    }

    public function index()
    {

        $data = [];
        $this->view('hRAdministrator/v_dashboard', $data);
    }

    public function dashboard()
    {

        $data = [];
        $this->view('hRAdministrator/v_dashboard', $data);
    }

    public function employees()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $employees = $this->employeeModel->getAllEmployees();
        $data = [
            'employees' => $employees
        ];
        $this->view('hRAdministrator/v_employees', $data);
    }

    public function viewEmployee($employeeId)
    {
        // Fetch the employee from the model
        $employee = $this->employeeModel->getEmployeeById($employeeId);

        // Check if employee exists
        if ($employee) {
            $data = [
                'employee' => $employee
            ];
            // Load the view with employee data
            $this->view('hRAdministrator/v_viewEmployee', $data);
        } else {
            // Employee not found, redirect to employees list
            flash('employee_msg', 'Employee not found', 'alert alert-danger');
            redirect('hRAdministrator/employees');
        }
    }

    // Create a new employee
    public function addEmployee()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'role' => trim($_POST['role']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'profile_image' => $_FILES['profile_image']['name'] ?? '',
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_image_err' => ''
            ];

            // Handle profile image
            $file = $_FILES['profile_image'] ?? null;
            // $profile_image_tmp = $_FILES['profile_image']['tmp_name'] ?? null;
            $profile_image_name = null;
            
            if($file['tmp_name'] && $file['error'] === UPLOAD_ERR_OK) {
                // Check file size (2MB max)
                $maxSize = 2 * 1024 * 1024;
                if($file['size'] > $maxSize) {
                    $data['profile_image_err'] = 'Image too large (max 2MB)';
                }
                
                // Check file type
                $allowed_types = ['image/jpeg', 'image/png'];
                if(!in_array($file['type'], $allowed_types)) {
                    $data['profile_image_err'] = 'Invalid image format (JPEG, PNG)';
                }
                
                // Generate unique filename
                if(empty($data['profile_image_err'])) {
                    $profile_image_name = uniqid() . '_' . basename($file['name']);
                }
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email is already registered or not
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter Name';
            }

            // Validate role
            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            // Validate phone
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Validate address
            if(empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }
            
            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['role_err']) && 
                empty($data['phone_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && 
                empty($data['address_err']) && empty($data['profile_image_err'])) {
                
                // Handle image upload
                if($profile_image_name) {
                    $upload_dir = APPROOT . '/../public/uploads/profile_pictures/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $upload_path = $upload_dir . $profile_image_name;
                    move_uploaded_file($file["tmp_name"], $upload_path);
                    $data['profile_image'] = $profile_image_name;
                }
                
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->employeeModel->create($data)) {
                    flash('employee_msg', 'Employee added successfully');
                    redirect('hRAdministrator/employees');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('hRAdministrator/v_addEmployee', $data);
            }
        } else {
            $data = [
                'name' => '',
                'role' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
                'password' => '',
                'confirm_password' => '',
                'profile_image' => '',
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_image_err' => ''
            ];

            $this->view('hRAdministrator/v_addEmployee', $data);
        }
    }

    // Edit an employee
    public function editEmployee($employeeId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'employee_id' => $employeeId,
                'user_id' => $this->employeeModel->getEmployeeById($employeeId)->user_id,
                'name' => trim($_POST['name']),
                'role' => trim($_POST['role']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email is already registered or not
                if ($this->userModel->findUserByEmail($data['email']) && $data['email'] != $this->userModel->getUserByEmail($data['email'])->email) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter Name';
            }

            // Validate role
            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            // Validate phone
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone';
            }

            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['role_err']) && empty($data['phone_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                if ($this->employeeModel->edit($data)) {
                    flash('employee_msg', 'Employee updated successfully');
                    redirect('hRAdministrator/employees');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('hRAdministrator/v_editEmployee', $data);
            }
        } else {
            // Get existing employee from model
            $employee = $this->employeeModel->getEmployeeById($employeeId);
            
            $data = [
                'employee_id' => $employeeId,
                'user_id' => $employee->user_id,
                'name' => $employee->name,
                'role' => $employee->role,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
            ];

            $this->view('hRAdministrator/v_editEmployee', $data);
        }
    }

    // Delete an employee
    public function deleteEmployee($employeeId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing employee from model
            $employee = $this->employeeModel->getEmployeeById($employeeId);

            if ($this->employeeModel->delete($employeeId)) {
                flash('employee_msg', 'Employee removed successfully');
                redirect('hRAdministrator/employees');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('hRAdministrator/employees');
        }
    }

    // view attendance records by date
    public function attendance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $date = trim($_POST['attendance_date']);
        } else {
            // Default to today's date when first accessing the page
            $date = date('Y-m-d'); // Current date in YYYY-MM-DD format
        }

        // Fetch attendance records for the selected date or today's date
        $attendanceRecords = $this->attendanceModel->getAttendanceByDate($date);
        
        // Add status to each record based on time_in
        foreach ($attendanceRecords as $record) {
            $record->status = !empty($record->time_in) ? 'Present' : 'Absent';
        }
        
        $data = [
            'attendanceRecords' => $attendanceRecords,
            'date' => $date
        ];
        $this->view('hRAdministrator/v_attendance', $data);
    }

    // view monthly attendance records by employee id
    public function viewAttendance($employeeId) {
        // Get the employee details
        $employee = $this->employeeModel->getEmployeeById($employeeId);

        if (!$employee) {
            flash('attendance_msg', 'Employee not found', 'alert alert-danger');
            redirect('hRAdministrator/attendance');
        }
        
        // Get month and year from query params or default to current month/year
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        echo $month;
        echo $year;
        
        // Get the first and last day of the selected month
        $firstDay = date('Y-m-d', strtotime("$year-$month-01"));
        $lastDay = date('Y-m-t', strtotime("$year-$month-01"));

        echo $firstDay;
        echo $lastDay;
        
        // Fetch attendance records for the employee for the selected month
        $attendanceRecords = $this->attendanceModel->getMonthlyAttendanceByEmployeeId($employeeId, $firstDay, $lastDay);

        echo "<pre>";
        print_r($attendanceRecords);
        echo "</pre>";
        
        // Initialize attendance summary
        $summary = (object)[
            'present' => 0,
            'absent' => 0,
            'leave' => 0,
            'late' => 0
        ];
        
        // Process attendance records to add additional data and calculate summary
        foreach ($attendanceRecords as $record) {
            // Calculate status
            if (!empty($record->leave_type)) {
                $record->status = 'Leave';
                $summary->leave++;
            } elseif (empty($record->clock_in)) {
                $record->status = 'Absent';
                $summary->absent++;
            } else {
                $record->status = 'Present';
                $summary->present++;
                
                // Check if late
                $scheduleStart = strtotime('09:00:00'); // Assuming work starts at 9 AM
                $actualStart = strtotime($record->clock_in);
                if ($actualStart > $scheduleStart) {
                    $record->remarks = 'Late Arrival';
                    $summary->late++;
                }
                
                // Calculate working hours if both clock in and clock out exist
                if (!empty($record->clock_in) && !empty($record->clock_out)) {
                    $start = strtotime($record->clock_in);
                    $end = strtotime($record->clock_out);
                    $hours = round(($end - $start) / 3600, 2);
                    $record->working_hours = $hours;
                } else {
                    $record->working_hours = null;
                }
            }
        }
        
        $data = [
            'employee' => $employee,
            'attendance' => $attendanceRecords,
            'summary' => $summary,
            'month' => $month,
            'year' => $year
        ];
        
        $this->view('hRAdministrator/v_viewEmpAttendance', $data);
    }

    // View payroll page
    public function payroll() {
        // Get month and year from query params or default to current month/year
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        // Get all payrolls for this month/year
        $payrolls = $this->payrollModel->getMonthlyPayrolls($month, $year);
        
        // Get all employees (for dropdown)
        $employees = $this->employeeModel->getAllEmployees();
        
        $data = [
            'payrolls' => $payrolls,
            'employees' => $employees,
            'month' => $month,
            'year' => $year
        ];
        
        $this->view('hRAdministrator/v_payroll', $data);
    }
    
    // Generate payroll for an employee
    public function generatePayroll() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $employeeId = trim($_POST['employee_id']);
            $month = trim($_POST['month']);
            $year = trim($_POST['year']);
            
            // Calculate payroll
            $payroll = $this->payrollModel->calculateMonthlyPayroll($employeeId, $month, $year);
            
            if ($payroll) {
                // Save payroll
                if ($this->payrollModel->savePayroll($payroll)) {
                    flash('payroll_msg', 'Payroll generated successfully');
                } else {
                    flash('payroll_msg', 'Failed to save payroll', 'alert alert-danger');
                }
            } else {
                flash('payroll_msg', 'Failed to calculate payroll. Ensure salary details are set.', 'alert alert-danger');
            }
            
            redirect('hRAdministrator/payroll?month=' . $month . '&year=' . $year);
        } else {
            redirect('hRAdministrator/payroll');
        }
    }
    
    // View payslip for an employee
    public function viewPayslip($employeeId, $month, $year) {
        // Get employee details
        $employee = $this->employeeModel->getEmployeeById($employeeId);
        
        if (!$employee) {
            flash('payroll_msg', 'Employee not found', 'alert alert-danger');
            redirect('hRAdministrator/payroll');
        }
        
        // Get payroll details
        $payroll = $this->payrollModel->getPayroll($employeeId, $month, $year);
        
        if (!$payroll) {
            // Generate payroll if not already generated
            $payroll = $this->payrollModel->calculateMonthlyPayroll($employeeId, $month, $year);
            
            if (!$payroll) {
                flash('payroll_msg', 'Could not generate payslip. Ensure salary details are set.', 'alert alert-danger');
                redirect('hRAdministrator/payroll');
            }
        }
        
        $data = [
            'employee' => $employee,
            'payroll' => $payroll,
            'month' => $month,
            'year' => $year
        ];
        
        $this->view('hRAdministrator/v_payslip', $data);
    }
    
    // Manage employee salary
    public function manageSalary($employeeId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'employee_id' => trim($_POST['employee_id']),
                'basic_salary' => trim($_POST['basic_salary']),
                'allowances' => trim($_POST['allowances']),
                'deductions' => trim($_POST['deductions']),
                'basic_salary_err' => '',
                'allowances_err' => '',
                'deductions_err' => ''
            ];
            
            // Validate inputs
            if (empty($data['basic_salary']) || !is_numeric($data['basic_salary'])) {
                $data['basic_salary_err'] = 'Please enter a valid basic salary';
            }
            
            if (!is_numeric($data['allowances'])) {
                $data['allowances_err'] = 'Please enter a valid allowance amount';
            }
            
            if (!is_numeric($data['deductions'])) {
                $data['deductions_err'] = 'Please enter a valid deduction amount';
            }
            
            // If no errors, update salary
            if (empty($data['basic_salary_err']) && empty($data['allowances_err']) && empty($data['deductions_err'])) {
                if ($this->payrollModel->updateSalary($data)) {
                    flash('salary_msg', 'Salary updated successfully');
                    redirect('hRAdministrator/employees');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $employee = $this->employeeModel->getEmployeeById($data['employee_id']);
                $data['employee'] = $employee;
                $this->view('hRAdministrator/v_manageSalary', $data);
            }
        } else {
            // Get employee
            $employee = $this->employeeModel->getEmployeeById($employeeId);
            
            if (!$employee) {
                flash('employee_msg', 'Employee not found', 'alert alert-danger');
                redirect('hRAdministrator/employees');
            }
            
            // Get salary details
            $salary = $this->payrollModel->getEmployeeSalary($employeeId);
            
            $data = [
                'employee' => $employee,
                'employee_id' => $employeeId,
                'basic_salary' => $salary ? $salary->basic_salary : '',
                'allowances' => $salary ? $salary->allowances : '',
                'deductions' => $salary ? $salary->deductions : '',
                'basic_salary_err' => '',
                'allowances_err' => '',
                'deductions_err' => ''
            ];
            
            $this->view('hRAdministrator/v_manageSalary', $data);
        }
    }

    public function holiday()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            // if (!$employee) {
            //     flash('error_msg', 'Employee not found');
            //     redirect('users/login');
            // }

            $data = [
                'employee_id' => '',
                'holidayRecords' => $this->employeeModel->getHolidayRecords($limit, $offset),
                'totalRecords' => $this->employeeModel->getTotalHolidayRecords(),
                'currentPage' => $page,
                'totalPages' => ceil($this->employeeModel->getTotalHolidayRecords() / $limit),
                'start_date' => '',
                'end_date' => '',
                'number_of_days' => '',
                'reason' => '',
                'status' => '',
                'comment' => '',
                'type' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'days_err' => '',
                'reason_err' => '',
                'type_err' => '',
                'comment_err' => ''
            ];

            $this->view('hRAdministrator/v_holiday', $data);
        } 
        
    }

    public function details($recordId = null) {
        // Validate recordId
        if (!$recordId) {
            redirect('hRAdministrator/holiday');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $recordDetails = $this->employeeModel->getHolidayRecordById($recordId);
            
            if (!$recordDetails) {
                flash('error_msg', 'Record not found');
                redirect('hRAdministrator/holiday');
            }

            $data = [
                'record' => $recordDetails,
                'employeeDetails' => $this->employeeModel->getEmployeeById($recordDetails->employee_id),
                'assignedTasksDetails' => $this->employeeModel->getAssignedTasksDetails($recordDetails->employee_id, $recordDetails->start_date, $recordDetails->end_date)
            ];
            
            $this->view('hRAdministrator/v_holidayDetails', $data);

        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle status update
        if (isset($_POST['recordId']) && isset($_POST['status'])) {
            $status = $_POST['status'];
            
            if ($this->employeeModel->updateStatus($_POST['recordId'], $status)) {
                flash('success_msg', 'Leave request ' . strtolower($status));
                redirect('hRAdministrator/details/' . $_POST['recordId']);
            } else {
                flash('error_msg', 'Failed to update status');
                redirect('hRAdministrator/details/' . $_POST['recordId']);
            }
        }
        
        // Handle comment update
        if (isset($_POST['comment'])) {
            header('Content-Type: application/json');
                $comment = trim($_POST['comment']);
    
                if (empty($comment)) {
                    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
                    return;
                }
    
                if ($this->employeeModel->updateComment($recordId, $comment)) {
                    echo json_encode(['success' => true, 'message' => 'Comment added/updated successfully']);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update comment']);
                    return;
                }
            }
    
            // Handle comment deletion
            if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
                $result = $this->employeeModel->deleteComment($recordId);
    
                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
            }
        }
        
    }

}
