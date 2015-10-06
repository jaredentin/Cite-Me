<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        echo $_SESSION['logged_in'];
    } else {
        echo "Not logged in";   
    }
?>