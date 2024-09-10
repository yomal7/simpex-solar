<?php
    class Pages extends Controller {
        private $pagesModel;

        public function __construct() {
            // Initialize the M_Pages model
            $this->pagesModel = $this->model("M_Pages");
        }

        public function index() {
            // You can add logic here for the index method
        }

        public function about() {
            // Correct method call on $pagesModel
            $users = $this->pagesModel->getUsers();
            
            $data = [
                'users' => $users
            ];

            $this->view('v_about', $data);
        }
    }
?>
