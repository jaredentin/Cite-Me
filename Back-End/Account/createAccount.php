<?php
    $isConnected = false;

//$ telnet 52.6.142.255 3306

    include "../../private/openDB.php";

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $isConnected = true;   
    }

    if ($isConnected) {
        if (!empty($_GET['username'])){
            $uname = $connection->real_escape_string($_GET['username']);
            $sql = "SELECT username FROM users";
            $result =  $connection->query($sql);
            $found = 0;
            
            while ($db_field = mysqli_fetch_assoc($result)) {
                if ($db_field["username"] === $uname) {
                    $found = 1;
                    break;
                }
            }
            
            if ($found === 0) {
                $uname = mysqli_real_escape_string($connection, $_GET['username']);
                $pword = mysqli_real_escape_string($connection, $_GET['password']);
                $fname = mysqli_real_escape_string($connection, $_GET['firstName']);
                $lname = mysqli_real_escape_string($connection, $_GET['lastName']);
		$pwdHash = sha1($pword);

                $sql_insert = "INSERT INTO users (`username`, `password`, `first_name`, `last_name`) VALUES ('$uname', '$pwdHash', '$fname', '$lname')";
                
                if ($connection->query($sql_insert) === FALSE) {
                    echo "error";
                }
                
                echo "Account Created";
            } else {
                echo "Username already exists"; 
            }
        } else {
            echo "Field(s) Empty";   
        }
    }
?>
