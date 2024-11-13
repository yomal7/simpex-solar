<?php
    class Pages extends Controller{

        public function __construct(){
            $this->pagesModel = $this->model('m_pages');
        }

        public function index($id){
            echo 'this is the page id '.$id;
        }

        public function about(){
            $users = $this->pagesModel->getUsers();
            $data = [
                'users' =>$users
            ];
            $this->view('v_about', $data);
        }
    }
?>