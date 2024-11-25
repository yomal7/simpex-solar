<?php
class Calculators extends Controller
{
    private $calcModel;

    public function __construct()
    {
        // Initialize the M_Pages model
        $this->calcModel = $this->model("M_Calculators");
    }

    public function index()
    {
        $data = [];
        $this->view('calculator/v_calculator', $data); // Correct view path
    }

    // public function about()
    // {
    //     // Correct method call on $pagesModel
    //     //$users = $this->pagesModel->getUsers();

    //     //$data = [
    //         'users' => $users
    //     ];

    //     $this->view('pages/v_about', $data);
    // }
}
