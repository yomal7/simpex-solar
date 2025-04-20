<?php

class engineer extends Controller
{
    private $engineerModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'engineer') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->engineerModel = $this->model('M_Engineer');
        $this->projectModel = $this->model('M_CustomerProject');
    }

    public function index()
    {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function dashboard()
    {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function requestHoliday()
    {
        //$holidayRecords = $this->engineerModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
            //            'holidayRecords' => $holidayRecords
        ];
        $this->view('engineer/v_engineerRequestHoliday', $data);
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

    public function settings()
    {
        $data = [];
        $this->view('engineer/v_engineerSettings', $data);
    }

    public function projects()
    {
        // Get the engineer's employee ID from the session user ID
        $employee = $this->projectModel->getEngineerId($_SESSION['user_id']);

        if (!$employee) {
            // Handle case where employee record not found
            $data = [
                'title' => 'My Projects',
                'activeProjects' => [],
                'initialProjects' => [],
                'completedProjects' => [],
                'currentFilter' => 'active',
                'message' => 'No engineer profile found'
            ];
            $this->view('engineer/v_projects', $data);
            return;
        }

        $engineerId = $employee->employee_id;

        // Get assigned installations for this engineer
        $assignedInstallations = $this->projectModel->getAssignedInstallations($engineerId);

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
            $this->view('engineer/v_projects', $data);
            return;
        }

        // Get projects with their installation details
        $projects = $this->projectModel->getEngineerProjects($installationIds);

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

        $this->view('engineer/v_projects', $data);
    }

    public function viewInstallation($installationId)
    {
        // Get installation details
        $installation = $this->projectModel->getInstallationById($installationId);

        if (!$installation) {
            flash('installation_message', 'Installation not found', 'alert alert-danger');
            redirect('engineer/projects');
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
                'phone' => 'Not available',
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

        // Get team members
        $teamMembers = $this->projectModel->getInstallationTeamMembers($installationId) ?? [];

        $data = [
            'installation' => $installation,
            'project' => $project,
            'schedule' => $schedule,
            'engineer' => $engineer,
            'team_members' => $teamMembers
        ];

        $this->view('engineer/v_projectInstallation', $data);
    }

    public function startInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $result = $this->projectModel->updateInstallationStatus($data->installation_id, 'active');

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update installation status']);
        }
    }

    public function completeInstallationStep()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id) || !isset($data->step)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $result = $this->projectModel->completeInstallationStep($data->installation_id, $data->step);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to complete installation step']);
        }
    }

    public function saveInstallationNotes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id) || !isset($data->notes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        // Validate engineer has access to this installation
        $engineerId = $this->projectModel->getEngineerId($_SESSION['user_id'])->employee_id;
        $assignedInstallations = $this->projectModel->getAssignedInstallations($engineerId);

        $hasAccess = false;
        foreach ($assignedInstallations as $installation) {
            if ($installation->installation_id == $data->installation_id) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            echo json_encode(['success' => false, 'message' => 'You do not have access to this installation']);
            return;
        }

        $result = $this->projectModel->saveInstallationNotes($data->installation_id, $data->notes);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save notes']);
        }
    }
}
