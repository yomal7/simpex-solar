<?php

class technician extends Controller {
    private $technicianModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->technicianModel = $this->model('M_Technician');
    }

    public function index() {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianDashboard', $data);
    }

    public function dashboard() {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianDashboard', $data);
    }

    public function requestHoliday() {
        //$holidayRecords = $this->technicianModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('technician/v_technicianRequestHoliday', $data);
    }

    public function addRequests() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'employee_id' => $_SESSION['employee_id'],
                'start_date' => $_POST['startDate'],
                'end_date' => $_POST['endDate'],
                'number_of_days' => $_POST['numberOfDays'],
                'reason' => $_POST['reason'],
                'leave_type' => $_POST['leaveType']
            ];

            if ($this->technicianModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/Technician');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function tasks() {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianTasks', $data);
    }

    public function settings() {
        $data = [];
        $this->view('technician/v_technicianSettings', $data);  
    }
}   



?>