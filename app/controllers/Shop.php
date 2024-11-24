<?php
    class Shop extends Controller {
        private $pagesModel;

        public function __construct() {
            // Initialize the M_Pages model
           // $this->pagesModel = $this->model("M_Pages");
        }

        public function index() {
            // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [];
            $this->view('shop/v_shopLanding', $data);
        }
    
    }
?>