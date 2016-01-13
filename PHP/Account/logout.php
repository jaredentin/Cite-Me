<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        session_destroy();
        echo "Logged out";
    }
?>