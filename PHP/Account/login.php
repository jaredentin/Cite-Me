<?php
    if (!empty($_POST['username'] && $_POST['password'])){
        include "../../private/openDB.php";

        if (!mysqli_connect_errno()) {
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $password = mysqli_real_escape_string($connection, sha1($_POST['password']));

            $sql = "SELECT * FROM users where username = '$username' and password = '$password'";
            $result =  mysqli_query($connection, $sql);
            if (mysqli_num_rows($result) === 1) {
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = mysqli_fetch_row($result)[0];
                echo "Logged in";
            } else {
                echo "Incorrect username or password";   
            }
        } else {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    } else {
        echo "Fill out all fields";
    }
?>