<?php
    if (!empty($_POST['username'] && $_POST['password'] && $_POST['firstName'] && $_POST['lastName'])) {
        include "../../private/openDB.php";

        if (!mysqli_connect_errno()) {
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $sql = "SELECT * FROM users where username = '$username'";
            $result =  mysqli_query($connection, $sql);
                
            if (mysqli_num_rows($result) === 0){
                $password = mysqli_real_escape_string($connection, sha1($_POST['password']));
                $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
                $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);

                $sql_insert = "INSERT INTO users (`username`, `password`, `first_name`, `last_name`) VALUES ('$username', '$password', '$firstName', '$lastName')";
                if (mysqli_query($connection, $sql_insert)) {
                    echo "Account created";
                } else {
                    echo "Error";
                }   
            } else {
                echo "Username already exists";
            }  
        } else {
            echo "umeafa";
        }
    } else {
        echo "Fill out all fields";
    }
?>