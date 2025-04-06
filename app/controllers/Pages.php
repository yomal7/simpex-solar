<?php
    class Pages extends Controller {
        private $pagesModel;

        public function __construct() {
            // Initialize the M_Pages model
            $this->pagesModel = $this->model("M_Pages");
        }

        public function index() {
            $data = [];
            $this->view('pages/v_index', $data); // Correct view path
        }
    
        public function about() {
            $data = []; // Initialize an empty array for data
            $this->view('pages/v_about', $data);
        }
    }
?>
