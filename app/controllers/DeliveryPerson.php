<?php

class DeliveryPerson extends Controller {
    private $deliveryPersonModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'deliveryPerson') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->deliveryPersonModel = $this->model('M_DeliveryPerson');
    }

    public function index() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function dashboard() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function requestHoliday() {
        //$holidayRecords = $this->deliveryPersonModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
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

            if ($this->deliveryPersonModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/DeliveryPerson');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function tasks() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonTasks', $data);
    }

    public function settings() {
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonSettings', $data);  
    }
}   



?>