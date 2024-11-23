<?php

class operationsCoordinator extends Controller {

    private $clientModel;

    public function __construct() {
        // $this->clientModel = $this->model('M_Client');
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

}
?>