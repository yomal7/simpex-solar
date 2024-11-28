<?php

    function getNavbarData() {
        $isLoggedIn = isset($_SESSION['user_id']);
        $profilePicture = $isLoggedIn && isset($_SESSION['profile_picture']) 
            ? URLROOT . '/uploads/profile_pictures/' . $_SESSION['profile_picture'] 
            : URLROOT . '/assets/profile.png';

        return [
            'isLoggedIn' => $isLoggedIn,
            'profile_picture' => $profilePicture
        ];
    }

?>
