<?php
    class Core{
        //URL format -> /controller/method/params
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct() {
            $url = $this-> getURL();
            
            if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                // If the controller exists, then load it
                $this->currentController = ucwords($url[0]);
    
                // Unset the controller in the URL
                unset($url[0]);
            }
            //call the controller
            require_once '../app/controllers/'.$this->currentController.'.php';

            //instentiate the controller
            $this->currentController = new $this->currentController;

            //check whether the method exists in the controller
            if (isset($url[1])) {
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }

            //get the parameter list
            $this->params = $url ? array_values($url) : [];

            //call method and the pass the parameter list
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        }

        public function getURL() {
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/'); //trim the url
                $url = filter_var($url, FILTER_SANITIZE_URL);//filter the url(get rid of unnessary symbols)
                $url = explode('/', $url);

                return $url;
            }
        }   
    }
?>