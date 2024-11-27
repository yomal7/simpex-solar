<?php

class OperationsCoordinator extends Controller {

    private $clientModel;
    private $tasksModel;
    private $packageModel;
    private $inventoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operationsCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        // $this->clientModel = $this->model('M_Client');
        $this->tasksModel = $this->model('M_Tasks');
        $this->packageModel = $this->model('M_Packages');
        $this->inventoryModel = $this->model('M_Inventory');
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

    public function managePackages() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [
            'packages' => $this->packageModel->getAllPackages(),
            'inventoryItems' => $this->inventoryModel->getAllItems()
        ];
        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function packages() {
        $inventoryItems = $this->inventoryModel->getAllItems();
        
        $data = [
            'title' => '',
            'description' => '',
            'warranty_years' => '',
            'type' => 'onGrid', 
            'service_charge' => '',
            'inventory_items' => $inventoryItems,
            'errors' => []
        ];

        $this->view('operationsCoordinator/v_createPackage', $data);
    }


    public function createPackage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Handle file upload
            $image = null;
            if(isset($_FILES['package_image']) && $_FILES['package_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $file = $_FILES['package_image'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if(in_array($file_ext, $allowed)) {
                    $file_name = uniqid('package_') . '.' . $file_ext;
                    $file_destination = APPROOT . '/../public/uploads/packages/' . $file_name;

                    if(move_uploaded_file($file['tmp_name'], $file_destination)) {
                        $image = 'uploads/packages/' . $file_name;
                    }
                }
            }

            // Process equipment items
            $equipment = [];
            if(isset($_POST['item_id'])) {
                foreach ($_POST['item_id'] as $key => $item_id) {
                    if (!empty($item_id) && !empty($_POST['quantity'][$key])) {
                        $equipment[] = [
                            'item_id' => $item_id,
                            'quantity' => $_POST['quantity'][$key]
                        ];
                    }
                }
            }

            // Process features
            $features = [];
            if(isset($_POST['feature_name'])) {
                foreach ($_POST['feature_name'] as $key => $name) {
                    if (!empty($name)) {
                        $features[] = [
                            'name' => $name,
                            'description' => $_POST['feature_description'][$key]
                        ];
                    }
                }
            }

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'warranty_years' => intval($_POST['warranty_years']),
                'type' => trim($_POST['type']),
                'service_charge' => floatval($_POST['service_charge']) ?: 0.00,
                'equipment' => $equipment,
                'features' => $features,
                'image' => $image,
                'errors' => []
            ];

            // Validate input
            if (empty($data['title'])) {
                $data['errors']['title'] = 'Please enter package title';
            }
            if (empty($data['type']) || !in_array($data['type'], ['on-grid', 'off-grid', 'hybrid'])) {
                $data['errors']['type'] = 'Please select a valid package type';
            }
            if (empty($equipment)) {
                $data['errors']['equipment'] = 'Please add at least one equipment item';
            }

            if (empty($data['errors'])) {
                // Calculate prices
                $data['price'] = $this->packageModel->calculateFinalPrice($equipment, 0); // Base price without service charge
                $data['final_price'] = $data['price'] + $data['service_charge'];

                if ($this->packageModel->createPackage($data)) {
                    flash('package_message', 'Package created successfully');
                    redirect('operationsCoordinator/packages');
                } else {
                    flash('package_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('operationsCoordinator/v_createPackage', $data);
                }
            } else {
                $this->view('operationsCoordinator/v_createPackage', $data);
            }
        } else {
            $inventoryItems = $this->inventoryModel->getAllItems();
            $data = [
                'title' => '',
                'description' => '',
                'warranty_years' => '',
                'type' => 'onGrid',
                'service_charge' => '',
                'inventory_items' => $inventoryItems,
                'errors' => []
            ];
            $this->view('operationsCoordinator/v_createPackage', $data);
        }
    }


}
?>