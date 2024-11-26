<?php
    function redirect($page) {
        header('Location: ' . URLROOT . '/' . $page);
        exit(); // Add exit after redirect to ensure the script stops executing
    }
?>