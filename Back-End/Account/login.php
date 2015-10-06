<?php
//header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (!empty($_GET['username']) && !empty($_GET['password'])){
        include "../../private/openDB.php";
        
        $found = 0;
        
        $username = $connection->real_escape_string($_GET['username']);
        $password = $connection->real_escape_string($_GET['password']);
            
        $_COOKIE['id'] = 'abc234';
        
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $connection->query($query);

        if ($result) {
            $count=mysqli_num_rows($result);

            if ($count == 1){
                $row = mysqli_fetch_assoc($result);                
                if ($row['password'] === sha1($password)){
                    $_SESSION['logged_in'] = $row['username'];
                    $_SESSION['id'] = $row['user_id'];
                    $found = 1;
                } else {
                    $found = 0;   
                    include "../../private/closeDB.php";
                }
            }
        } else {
            $found = 0;   
            include "../../private/closeDB.php";
        }
    } else {
        $found = 0;   
    }
    echo $found;
    //session_destroy();
?>