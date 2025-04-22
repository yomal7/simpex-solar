<?php

class technician extends Controller {
    private $technicianModel;
    private $projectModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->technicianModel = $this->model('M_Technician');
        $this->projectModel = $this->model('M_CustomerProject');
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

    public function projects()
    {
        // Get the technician's employee ID from the session user ID
        $employee = $this->projectModel->getTechnicianId($_SESSION['user_id']);

        if (!$employee) {
            // Handle case where employee record not found
            $data = [
                'title' => 'My Projects',
                'activeProjects' => [],
                'initialProjects' => [],
                'completedProjects' => [],
                'currentFilter' => 'active',
                'message' => 'No technician profile found'
            ];
            $this->view('technician/v_technicianProject', $data);
            return;
        }

        $technicianId = $employee->employee_id;

        // Get assigned installations for this technician
        $assignedInstallations = $this->projectModel->getAssignedInstallationsByTechnician($technicianId);

        $installationIds = [];
        foreach ($assignedInstallations as $installation) {
            $installationIds[] = $installation->installation_id;
        }

        // If no installations are assigned, return empty result
        if (empty($installationIds)) {
            $data = [
                'title' => 'My Projects',
                'activeProjects' => [],
                'initialProjects' => [],
                'completedProjects' => [],
                'currentFilter' => 'active',
                'message' => 'No projects are currently assigned to you'
            ];
            $this->view('technician/v_technicianProject', $data);
            return;
        }

        // Get projects with their installation details
        $projects = $this->projectModel->getTechnicianProjects($installationIds);

        // Separate projects by status
        $activeProjects = [];
        $initialProjects = [];
        $completedProjects = [];

        foreach ($projects as $project) {
            if ($project->installation_status === 'active') {
                $activeProjects[] = $project;
            } elseif ($project->installation_status === 'initial') {
                $initialProjects[] = $project;
            } elseif ($project->installation_status === 'completed') {
                $completedProjects[] = $project;
            }
        }

        $data = [
            'title' => 'My Projects',
            'activeProjects' => $activeProjects,
            'initialProjects' => $initialProjects,
            'completedProjects' => $completedProjects,
            'currentFilter' => 'active'
        ];

        $this->view('technician/v_technicianProject', $data);
    }

    public function viewInstallation($installationId) {
        $installation = $this->projectModel->getInstallationById($installationId);

        if (!$installation) {
            // Handle case where installation not found
            flash('installation_message', 'Installation not found', 'alert alert-danger');
            redirect('technician/projects');
            return;
        }

        // Get project details with customer information
        $project = $this->projectModel->getProjectWithCustomerInfo($installation->project_id);

        if (!$project) {
            // Create a default project object with minimal information
            $project = (object)[
                'project_id' => $installation->project_id,
                'customer_name' => 'Not available',
                'location' => 'No location data',
                'system_capacity' => 'N/A',
                'estimated_generation' => 'N/A'
            ];
        }

        // Get schedule details
        $schedule = $this->projectModel->getInstallationSchedule($installationId);
        if (!$schedule) {
            $schedule = (object)[
                'start_date' => date('Y-m-d'),
                'start_time' => '09:00:00',
                'end_date' => date('Y-m-d', strtotime('+1 day'))
            ];
        }

        // Get engineer data
        $engineer = $this->projectModel->getAssignedEngineer($installationId);
        if (!$engineer) {
            $engineer = (object)[
                'name' => $_SESSION['user_name'] ?? 'Current Engineer'
            ];
        }

        $data = [
            'installation' => $installation,
            'project' => $project,
            'schedule' => $schedule,
            'engineer' => $engineer
        ];

        $this->view('engineer/v_projectInstallation', $data);
    }

    public function settings() {
        $data = [];
        $this->view('technician/v_technicianSettings', $data);  
    }
}   



?>