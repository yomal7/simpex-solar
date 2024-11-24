<?php
class Core{
    // URL format: /controller/method/params

    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        // Get URL and break it into parts
        $url = $this->getUrl();

        // Check if the controller exists
        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // If the controller exists, then load it
            $this->currentController = ucwords($url[0]);

            // Unset the controller in the URL
            unset($url[0]);
        }

        // Require the controller file
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate the controller class
        $this->currentController = new $this->currentController;

        //Check wethere the method exist in the cotroller or not

        if(isset($url[1])){
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod= $url[1];

                unset($url[1]);
            }
        }
        
        //Get parameter List

        $this->params= $url ? array_values($url) : [];

        //Call method and pass the parameter list

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if (isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }

    public function loadService($service) {
        // Require service file
        require_once 'app/services/' . $service . '.php';
        // Instantiate service
        return new $service();
    }
}
?>
