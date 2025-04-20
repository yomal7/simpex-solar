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
}
