<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['username'] && $_SESSION['user_id'])){
        include "../../private/openDB.php";
    
        if (!mysqli_connect_errno()) {
            $username = $_SESSION['username'];
            $userID = $_SESSION['user_id'];
            
            $sqlDelete = "DELETE from users_access where user_id = '$userID'";
            mysqli_query($connection, $sqlDelete);
            
            $sqlDelete1 = "DELETE from users_groups where user_id = '$userID'";
            mysqli_query($connection, $sqlDelete1);
            
            $sqlDelete2 = "DELETE from group_invites_queue where sender_id = '$userID'";
            mysqli_query($connection, $sqlDelete2);
            
            $sqlDelete3 = "DELETE from group_requests_queue where user_id = '$userID'";
            mysqli_query($connection, $sqlDelete3);
            
            $sqlDelete4 = "DELETE from quote_requests_queue where user_id = '$userID'";
            mysqli_query($connection, $sqlDelete4);
            
            $sqlDelete5 = "DELETE from users where user_id = '$userID'";
            if (mysqli_query($connection, $sqlDelete5)) {
                echo "Account deleted";
            } else {
                echo "Error";
            }
        } else {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    } else {
        echo "Not logged in";   
    }
?>