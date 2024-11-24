<?php

class SupplierCoordinator extends Controller {

    private $supplierModel;

    public function __construct() {
        // $this->clientModel = $this->model('M_Client');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function suppliers() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_suppliers', $data);
    }

    public function manageAproject() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_manageAproject', $data);
    }

}
?>