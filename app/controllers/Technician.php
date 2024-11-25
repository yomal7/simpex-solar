<?php

class techncian extends Controller {
    private $technicianModel;

    public function __construct() {
        $this->technicianModel = $this->model('M_Technician');
    }

    public function index() {
        //$techncian = $this->techncianModel->getTechncianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('techncian/v_techncianDashboard', $data);
    }

    public function dashboard() {
        //$techncian = $this->techncianModel->getTechncianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('techncian/v_techncianDashboard', $data);
    }

    public function requestHoliday() {
        //$holidayRecords = $this->techncianModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('techncian/v_techncianRequestHoliday', $data);
    }

    public function tasks() {
        //$technician = $this->technicianModel->getTechncianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('techncian/v_techncianTasks', $data);
    }

    public function settings() {
        $data = [];
        $this->view('technician/v_technicianSettings', $data);  
    }
}   



?>

