<?php

class OperationsCoordinator extends Controller
{

    private $clientModel;
    private $tasksModel;
    private $packageModel;
    private $inventoryModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operationsCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        // $this->clientModel = $this->model('M_Client');
        $this->tasksModel = $this->model('M_Tasks');
        $this->packageModel = $this->model('M_Packages');
        $this->inventoryModel = $this->model('M_Inventory');
    }

    public function index()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function dashboard()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function projects()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function manageAproject()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function managePackages()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [
            'packages' => $this->packageModel->getAllPackages(),
            'inventoryItems' => $this->inventoryModel->getAllItems()
        ];
        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function tasks()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $tasks = $this->tasksModel->getAllTasks();
        $data = [
            'tasks' => $tasks
        ];
        $this->view('operationsCoordinator/v_tasks', $data);
    }

    // View aTask
    public function viewTask($taskId)
    {
        $task = $this->tasksModel->getTaskByID($taskId);
        $data = [
            'id' => $taskId
            ];
        $this->view('operationsCoordinator/v_viewTask', $data);
    }

    // Add Task
    public function addTask()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description']),
                'project_id' => trim($_POST['project_id']),
                'employee_id' => trim($_POST['employee_id']),
                'status' => trim($_POST['status']),
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter start time';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter end time';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (empty($data['project_id'])) {
                $data['project_id_err'] = 'Please select project';
            }

            if (empty($data['employee_id'])) {
                $data['employee_id_err'] = 'Please select employee';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Please select status';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['description_err']) && empty($data['project_id_err']) && empty($data['employee_id_err']) && empty($data['status_err'])) {
                // Validated
                if ($this->tasksModel->create($data)) {
                    flash('task_msg', 'Task added successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_addTask', $data);
            }
        } else {
            $data = [
                'title' => '',
                'start_date' => '',
                'end_date' => '',
                'description' => '',
                'project_id' => '',
                'employee_id' => '',
                'status' => '',
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            $this->view('operationsCoordinator/v_addTask', $data);
        }
    }

    // Edit Task
    public function editTask($taskId)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $taskId,
                'title' => trim($_POST['title']),   
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description']),
                'project_id' => trim($_POST['project_id']),
                'employee_id' => trim($_POST['employee_id']),
                'status' => trim($_POST['status']),
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter start time';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter end time';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (empty($data['project_id'])) {
                $data['project_id_err'] = 'Please select project';
            }

            if (empty($data['employee_id'])) {
                $data['employee_id_err'] = 'Please select employee';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Please select status';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['description_err']) && empty($data['project_id_err']) && empty($data['employee_id_err']) && empty($data['status_err'])){
                // Validated
                if($this->tasksModel->edit($data)){
                    flash('task_msg', 'Task updated successfully'); 
                    redirect('operationsCoordinator/tasks');
                }else{
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_editTask', $data);
            }
        }else{
            $task = $this->tasksModel->getTaskById($taskId);

            $data = [
                'id' => $taskId,
                'title' => $task->title,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'description' => $task->description,
                'project_id' => $task->project_id,
                'employee_id' => $task->employee_id,
                'status' => $task->status,
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            $this->view('operationsCoordinator/v_editTask', $data);

        }
        
    }
 
    // Delete TaskA
    public function deleteTask($taskId)
    {
        $task = $this->tasksModel->getTaskByID($taskId);
        
        if($this->tasksModel->delete($taskId)){
            flash('task_msg', 'Task removed successfully');
            redirect('operationsCoordinator/tasks');
        }else{
            die('Something went wrong');
        }
    }

    public function getInventoryItems()
    {
        $items = $this->inventoryModel->getAllItems();
        echo json_encode($items);
    }

    public function createPackage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $packageData = [
                'title' => $_POST['title'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'service_charge' => $_POST['service_charge'],
                'warranty_years' => $_POST['warranty_years']
            ];

            // Handle package image upload
            $imagePath = $this->uploadPackageImage();
            if ($imagePath) {
                $packageData['image_path'] = $imagePath;
            }

            $packageId = $this->packageModel->createPackage($packageData);

            // Add features
            if (isset($_POST['features'])) {
                foreach ($_POST['features'] as $feature) {
                    $this->packageModel->addFeature($packageId, $feature);
                }
            }

            // Add equipment
            if (isset($_POST['equipment'])) {
                foreach ($_POST['equipment'] as $equipment) {
                    $this->packageModel->addEquipment($packageId, $equipment['item_id'], $equipment['quantity']);
                }
            }

            echo json_encode(['success' => true, 'package_id' => $packageId]);
        }
    }

    public function updatePackage($packageId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Similar logic to createPackage, but for updating
            $packageData = [
                'title' => $_POST['title'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'service_charge' => $_POST['service_charge'],
                'warranty_years' => $_POST['warranty_years']
            ];

            $this->packageModel->updatePackage($packageId, $packageData);

            // Update logic for features and equipment similar to createPackage
        }
    }

    private function uploadPackageImage()
    {
        if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] == 0) {
            $uploadDir = 'uploads/packages/';
            $fileName = uniqid() . '_' . basename($_FILES['package_image']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['package_image']['tmp_name'], $uploadPath)) {
                return $uploadPath;
            }
        }
        return null;
    }
}
