<?php

class OperationsCoordinator extends Controller {

    private $clientModel;
    private $tasksModel;

    public function __construct() {
        // $this->clientModel = $this->model('M_Client');
        $this->tasksModel = $this->model('M_Tasks');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function projects() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function manageAproject() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function managePackages() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function tasks() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_tasks', $data);
    }

    // public function addTask() {
    //     // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //     $data = [];
    //     $this->view('operationsCoordinator/v_addTask', $data);
    // }

    // Add New Task
    public function addTask(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']), 
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'description' => trim($_POST['description']), 
                'title_err' => '', 
                'start_time_err' => '', 
                'end_time_err' => '',
                'description_err' => ''
            ];

            // Validation
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            if(empty($data['start_time'])){
                $data['start_time_err'] = 'Please enter start time';
            }

            if(empty($data['end_time'])){
                $data['end_time_err'] = 'Please enter end time';
            }

            if(empty($data['description'])){
                $data['description_err'] = 'Please enter description';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['start_time_err']) && empty($data['end_time_err']) && empty($data['description_err'])){
                // Validated
                if($this->tasksModel->create($data)){
                    flash('task_msg', 'Task added successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_addTask', $data);
            }
        }
        else{
            $data = [
                'title' => '',
                'start_time' => '',
                'end_time' => '',
                'description' => '',
                'title_err' => '',
                'start_time_err' => '',
                'end_time_err' => '',
                'description_err' => ''
            ];
            
            $this->view('operationsCoordinator/v_addTask', $data);
        }
    }

}
?>