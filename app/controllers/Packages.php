<?php
    class Packages extends Controller {
        private $packagesModel;

        public function __construct() {
            
            $this->packagesModel = $this->model("M_Packages");
        }

        public function index() {
            $data = [];
            $this->view('packages/v_packageShowcase', $data);
        }

        public function packageDetails() {
            $data = [];
            $this->view('packages/v_packageDetails', $data);
        }

        public function packageConformation() {
            $data = [];
            $this->view('packages/v_packageConformation', $data);
        }


    
    }
?>