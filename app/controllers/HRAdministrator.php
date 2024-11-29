<?php

class HRAdministrator extends Controller
{



    public function __construct()
    {
        // $this->clientModel = $this->model('M_Client');
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hRAdministrator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
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
                if ($this->tasksModel->create($data)) {
                    flash('task_msg', 'Task added successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('hr', $data);
            }
        } else {
            $data = [
                'title' => '',
                'start_date' => '',
                'end_date' => '',
                'description' => '',
                'project_id' => '',
                'employee_id' => '',
                'status' => '',
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            $this->view('', $data);
        }
    }


    public function attendance()
    {

        $data = [];
        $this->view('hRAdministrator/v_attendance', $data);
    }

    public function employees()
    {

        $data = [];
        $this->view('hRAdministrator/v_employees', $data);
    }

    public function holiday()
    {

        $data = [];
        $this->view('hRAdministrator/v_holiday', $data);
    }
}
