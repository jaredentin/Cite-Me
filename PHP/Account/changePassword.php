<?php
    if (!empty($_POST['newPassword'] && $_POST['newPasswordConfirm'] && $_POST['oldPassword'])) {
        if ($_POST['newPassword'] === $_POST['newPasswordConfirm']) {
            include "../../private/openDB.php";
            session_start();
            if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])){
                if (!mysqli_connect_errno()) {
                    $username = $_SESSION['username'];
                    $oldPassword = mysqli_real_escape_string($connection, sha1($_POST['oldPassword']));
                    $newPassword = mysqli_real_escape_string($connection, sha1($_POST['newPassword']));
                    
                    $sql = "SELECT * FROM users where username = '$username' and password = '$oldPassword'";
                    $result =  mysqli_query($connection, $sql);
                    
                    if (mysqli_num_rows($result) == 1) {
                        $sqlUpdate = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
                        
                        if (mysqli_query($connection, $sqlUpdate)) {
                            echo "Password updated";
                        } else {
                            echo "Error";
                        }
                    } else {
                        echo "That's not your password";   
                    }
                } else {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
            }            
        } else {
            echo "Passwords don't match";
        }        
    } else {
        echo "Field(s) empty";   
    }
?>