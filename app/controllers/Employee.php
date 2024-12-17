<?php
class Employee extends Controller {
    private $employeeModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');

    }

    public function index() {
        $employee = $this->employeeModel->getEmployeeType();

        $_SESSION['role'] = $employee->role;
        
        // Redirect based on employee role
        switch($employee->role) {
            case 'technician':
                redirect('technician/index');
                break;
                
            case 'engineer':
                redirect('engineer/index');
                break;
                
            case 'deliveryPerson':
                redirect('deliveryPerson/index');
                break;
                
            default:
                // If no valid employee type, redirect to login page
                redirect('users/login');
                break;
        }
    }
}