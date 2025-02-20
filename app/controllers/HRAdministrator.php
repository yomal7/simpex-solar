<?php

class HRAdministrator extends Controller
{
    private $employeeModel;
    private $userModel;


    public function __construct()
    {
        // $this->clientModel = $this->model('M_Client');
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hRAdministrator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->userModel = $this->model("M_Users");
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
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

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


            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['role_err']) && empty($data['phone_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
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
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'role_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
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

    public function attendance()
    {

        $data = [];
        $this->view('hRAdministrator/v_attendance', $data);
    }

    public function holiday()
    {

        $data = [];
        $this->view('hRAdministrator/v_holiday', $data);
    }
}
