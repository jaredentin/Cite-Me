<?php
    session_start();
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        echo "1";
    } else {
        echo "0";
        session_destroy();
    }
?>
