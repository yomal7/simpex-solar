<?php

class Client extends Controller {
    private $clientModel;

    public function __construct() {
        // $this->clientModel = $this->model('M_Client');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientDashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientDashboard', $data);
    }

    public function project() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientProject', $data);
    }

    public function agreement() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientAgreement', $data);
    }

    public function sitevisit() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientsitevisit', $data);
    }

    public function firstPayment() {
        $data = [];
        $this->view('client/v_clientFirstPayment', $data);  
    }
    
    public function finalPayment() { 
        $data = [];
        $this->view('client/v_clientFinalPayment', $data); 
    }
    
    public function installation() {
        $data = [];
        $this->view('client/v_clientInstallation', $data);  
    }

}
?>

    <!-- // public function __construct() {
        // if (!isLoggedIn()) {
        //     redirect('users/index');
        // }
        // $this->clientModel = $this->model('M_Clients');
        // $this->userModel = $this->model('M_Users');
        // $this->clientModel = $this->model("M_Client");
    // }

    // public function indexs() {
        // Get client data for the dashboard
        // $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        
        // if (!$clientData) {
        //     redirect('clients/setup');
        // }

        // // Get client's recent projects/orders
        // $recentProjects = $this->clientModel->getRecentProjects($clientData->id);
        // // Get client's notifications
        // $notifications = $this->clientModel->getNotifications($clientData->id);
        // // Get progress of current projects
        // $projectProgress = $this->clientModel->getProjectProgress($clientData->id);
        
        // $data = [
            // 'client' => $clientData,
            // 'recent_projects' => $recentProjects,
            // 'notifications' => $notifications,
            // 'project_progress' => $projectProgress,
            // 'title' => 'Client Dashboard'
        // ];

        // $this->view('clients/dashboard', $data);
        // $this->view('client/v_clientDashboard', $data);
    }


    // public function profile() {
        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //     // Sanitize POST data
        //     $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        //     $data = [
        //         'client_id' => $_POST['client_id'],
        //         'company_name' => trim($_POST['company_name']),
        //         'address' => trim($_POST['address']),
        //         'phone' => trim($_POST['phone']),
        //         'contact_person' => trim($_POST['contact_person']),
        //         'company_name_err' => '',
        //         'address_err' => '',
        //         'phone_err' => '',
        //         'contact_person_err' => ''
        //     ];

        //     // Validate data
        //     if (empty($data['company_name'])) {
        //         $data['company_name_err'] = 'Please enter company name';
        //     }
        //     if (empty($data['address'])) {
        //         $data['address_err'] = 'Please enter address';
        //     }
        //     if (empty($data['phone'])) {
        //         $data['phone_err'] = 'Please enter phone number';
        //     }
        //     if (empty($data['contact_person'])) {
        //         $data['contact_person_err'] = 'Please enter contact person';
        //     }

        //     // Make sure errors are empty
        //     if (empty($data['company_name_err']) && empty($data['address_err']) && 
        //         empty($data['phone_err']) && empty($data['contact_person_err'])) {
                
        //         // Update client profile
        //         if ($this->clientModel->updateProfile($data)) {
        //             flash('client_message', 'Profile Updated Successfully');
        //             redirect('clients/profile');
        //         } else {
        //             die('Something went wrong');
        //         }
        //     } else {
        //         // Load view with errors
        //         $this->view('clients/profile', $data);
        //     }
        // } else {
        //     // Get client's current profile data
        //     $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            
        //     $data = [
        //         'client_id' => $clientData->id,
        //         'company_name' => $clientData->company_name,
        //         'address' => $clientData->address,
        //         'phone' => $clientData->phone,
        //         'contact_person' => $clientData->contact_person,
        //         'company_name_err' => '',
        //         'address_err' => '',
        //         'phone_err' => '',
        //         'contact_person_err' => ''
        //     ];

        // $data=[];
            // $this->view('client/v_clientProfile', $data);
        // }
// }

    // public function projects() {
    //     // $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //     // $projects = $this->clientModel->getAllProjects($clientData->id);
        
    //     $data = [
    //         // 'projects' => $projects,
    //         // 'title' => 'My Projects'
    //     ];


    //     $this->view('clients/projects', $data);
    // }

    // public function project($id = null) {
    //     if ($id === null) {
    //         redirect('clients/projects');
    //     }

    //     $project = $this->clientModel->getProjectById($id);
    //     $milestones = $this->clientModel->getProjectMilestones($id);
    //     $documents = $this->clientModel->getProjectDocuments($id);
        
    //     $data = [
    //         'project' => $project,
    //         'milestones' => $milestones,
    //         'documents' => $documents,
    //         'title' => $project->name
    //     ];

    //     $this->view('clients/project_details', $data);
    // }

    // public function invoices() {
    //     $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //     $invoices = $this->clientModel->getInvoices($clientData->id);
        
    //     $data = [
    //         'invoices' => $invoices,
    //         'title' => 'My Invoices'
    //     ];

    //     $this->view('clients/invoices', $data);
    // }

    // public function support() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Handle support ticket submission
    //         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    //         $data = [
    //             'subject' => trim($_POST['subject']),
    //             'message' => trim($_POST['message']),
    //             'priority' => trim($_POST['priority']),
    //             'client_id' => $_SESSION['user_id'],
    //             'subject_err' => '',
    //             'message_err' => ''
    //         ];

    //         // Validate
    //         if (empty($data['subject'])) {
    //             $data['subject_err'] = 'Please enter subject';
    //         }
    //         if (empty($data['message'])) {
    //             $data['message_err'] = 'Please enter message';
    //         }

    //         if (empty($data['subject_err']) && empty($data['message_err'])) {
    //             if ($this->clientModel->createSupportTicket($data)) {
    //                 flash('ticket_message', 'Support ticket created successfully');
    //                 redirect('clients/support');
    //             }
    //         } else {
    //             $this->view('clients/support', $data);
    //         }
    //     } else {
    //         $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //         $tickets = $this->clientModel->getSupportTickets($clientData->id);
            
    //         $data = [
    //             'tickets' => $tickets,
    //             'title' => 'Support Center',
    //             'subject' => '',
    //             'message' => '',
    //             'priority' => 'medium',
    //             'subject_err' => '',
    //             'message_err' => ''
    //         ];

    //         $this->view('clients/support', $data);
    //     }
    // }

    // public function messages() {
    //     $clientData = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //     $messages = $this->clientModel->getMessages($clientData->id);
        
    //     $data = [
    //         'messages' => $messages,
    //         'title' => 'Messages'
    //     ];

    //     $this->view('clients/messages', $data);
    // } -->



