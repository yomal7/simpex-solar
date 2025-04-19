<?php

class engineer extends Controller {
    private $engineerModel;
    private $preProjectModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'engineer') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->engineerModel = $this->model('M_Engineer');
        $this->preProjectModel = $this->model('M_CustomerPreProject');
    }

    public function index() {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function dashboard() {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function requestHoliday() {
        //$holidayRecords = $this->engineerModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('engineer/v_engineerRequestHoliday', $data);
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

            if ($this->engineerModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/engineer');
            } else {
                die('Something went wrong');
            }
        }
    }

    // public function tasks() {
    //     //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
    //     $data = [];
    //     $this->view('engineer/v_engineerTasks', $data);
    // }

    public function settings() {
        $data = [];
        $this->view('engineer/v_engineerSettings', $data);  
    }

    public function siteVisits() {
        $pendingSiteVisits = $this->engineerModel->getPendingSiteVisits();
        $data = [
            'pendingVisits' => $pendingSiteVisits
        ];
        $this->view('engineer/v_siteVisits', $data);
    }
    
    public function manageSiteVisit($visitId) {
        $siteVisit = $this->engineerModel->getSiteVisitById($visitId);
        
        if (!$siteVisit) {
            flash('site_visit_message', 'Site visit not found', 'error');
            redirect('engineer/siteVisits');
        }
        
        $project = $this->preProjectModel->getPreProjectById($siteVisit->pre_project_id);
        
        if (!$project) {
            flash('site_visit_message', 'Project not found', 'error');
            redirect('engineer/siteVisits');
        }
        
        // Get package information if available
        $packageEquipment = [];
        $packageFeatures = [];
        if ($siteVisit->package_id) {
            $packageEquipment = $this->engineerModel->getPackageEquipment($siteVisit->package_id);
            $packageFeatures = $this->engineerModel->getPackageFeatures($siteVisit->package_id);
        }
        
        $data = [
            'project' => $project,
            'site_visit' => $siteVisit,
            'package_equipment' => $packageEquipment,
            'package_features' => $packageFeatures,
            'title' => 'Manage Site Visit'
        ];
        
        $this->view('engineer/v_manageSiteVisit', $data);
    }
    
    public function completeSiteVisit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('engineer/siteVisits');
            return;
        }
        
        $preProjectId = $_POST['pre_project_id'];
        $visitId = $_POST['visit_id'];
        $notes = $_POST['site_notes'];
        
        if ($this->engineerModel->completeSiteVisit($preProjectId, $notes)) {
            flash('site_visit_message', 'Site visit completed successfully', 'success');
        } else {
            flash('site_visit_message', 'Failed to complete site visit', 'error');
        }
        
        redirect('engineer/siteVisits');
    }
}   



?>