<?php

class OperationsManager extends Controller {

    private $clientModel;

    public function __construct() {
        // $this->clientModel = $this->model('M_Client');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_dashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_dashboard', $data);
    }

    public function projects() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_projects', $data);
    }

    public function manageAproject() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_manageAproject', $data);
    }

}
?>