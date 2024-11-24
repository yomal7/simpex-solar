<?php

class Admin extends Controller {
    private $adminModel;

        public function __construct() {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                redirect('users/login'); 
            }
        }

        public function index() {
            $data = [];
            $this->view('admin/v_index', $data);
        }
}

?>