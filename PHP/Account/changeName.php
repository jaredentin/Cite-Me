<?php
    if (!empty($_POST['firstName'] && $_POST['lastName'])) {
        include "../../private/openDB.php";
        session_start();
        if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])){
            if (!mysqli_connect_errno()) { 
                $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
                $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
                  
                $sqlUpdate = "UPDATE users SET first_name = '$firstName', last_name = '$lastName' WHERE user_id = '$_SESSION[user_id]'";
                        
                if (mysqli_query($connection, $sqlUpdate)) {
                    echo "Name updated";
                } else {
                    echo "Error";
                }
            } else {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
        } else {
            echo "not logged in";
        }   
    }
?>