<?php

class technician extends Controller
{
    private $technicianModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->technicianModel = $this->model('M_Technician');
    }

    public function index()
    {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianDashboard', $data);
    }

    public function dashboard()
    {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianDashboard', $data);
    }

    public function requestHoliday()
    {
        //$holidayRecords = $this->technicianModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
            //            'holidayRecords' => $holidayRecords
        ];
        $this->view('technician/v_technicianRequestHoliday', $data);
    }

    public function addRequests()
    {
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

    public function tasks()
    {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianTasks', $data);
    }

    public function projects()
    {
        // Get the technician's employee ID from the session user ID
        $employee = $this->technicianModel->getTechnicianId($_SESSION['user_id']);
        
        if (!$employee) {
            // Handle case where employee record not found
            $data = [
                'projects' => [],
                'message' => 'No technician profile found'
            ];
            $this->view('technician/v_technicianProject', $data);
            return;
        }

        $technicianId = $employee->employee_id;

        // Get assigned installations for this technician
        $assignedInstallations = $this->technicianModel->getAssignedInstallations($technicianId);

        if (empty($assignedInstallations)) {
            $data = [
                'projects' => [],
                'message' => 'No projects are currently assigned to you'
            ];
            $this->view('technician/v_technicianProject', $data);
            return;
        }

        // Collect all projects for each installation
        $projectsWithCustomers = [];
        foreach ($assignedInstallations as $installation) {
            // Get projects for this specific installation ID
            $projects = $this->technicianModel->getTechnicianProjects($installation->installation_id);

            if (!empty($projects)) {
                foreach ($projects as $project) {
                    // Get customer for this specific project
                    $customer = $this->technicianModel->getCustomerByProjectId($project->project_id);

                    // Add customer info to project object
                    $project->customer = $customer;
                    $project->installation_id = $installation->installation_id; // Add installation ID to project object
                    $projectsWithCustomers[] = $project;
                }
            }
        }

        $data = [
            'projects' => $projectsWithCustomers
        ];

        $this->view('technician/v_technicianProject', $data);
    }

    public function viewInstallation($installation_id = null)
    {
        // Check if installation_id is provided
        if (!$installation_id) {
            flash('project_error', 'No installation selected');
            redirect('technician/projects');
        }

        // Get installation details
        $installation = $this->technicianModel->getInstallationByID($installation_id);

        if (!$installation) {
            flash('project_error', 'Installation not found');
            redirect('technician/projects');
        }

        // Get project details using project_id from installation
        $project = $this->technicianModel->getProjectDetails($installation->project_id);

        // Get customer details
        $customer = $this->technicianModel->getCustomerByProjectId($installation->project_id);

        // Get assigned engineers
        $engineers = $this->technicianModel->getAssignedEngineer($installation_id);

        $members = $this->technicianModel->getInstallationTeamMembers($installation_id);

        $data = [
            'project' => $project,
            'customer' => $customer,
            'engineers' => $engineers,
            'installation' => $installation,
            'members' => $members
        ];

        $this->view('technician/v_projectAssigned', $data);
    }

    public function settings()
    {
        $data = [];
        $this->view('technician/v_technicianSettings', $data);
    }
}
