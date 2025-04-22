<?php
class Errors extends Controller {
    public function __construct() {
        // Constructor - can be used for initialization if needed
    }
    
    // Method to display 404 page
    public function pageNotFound() {
        $data = [
            'title' => '404 - Page Not Found',
            'error_code' => 404,
            'message' => 'Oops! The page you are looking for does not exist.'
        ];
        
        $this->view('errors/v_404', $data);
    }
}
?>