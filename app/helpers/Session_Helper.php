<?php 
    session_start();

    function flash($name = '', $message = '', $class = 'alert alert-success') {
        if (!empty($name)) {
            if (!empty($message) && empty($_SESSION[$name])) {
                if (!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
    
                if (!empty($_SESSION[$name . '_class'])) {
                    unset($_SESSION[$name . '_class']);
                }
    
                $_SESSION[$name] = $message;
                $_SESSION[$name . '_class'] = $class;
            } elseif (empty($message) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                echo '<div id="msg-flash" class="' . $class . '" style="display: none;">' . $_SESSION[$name] . '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }
        }
    }

    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
?>