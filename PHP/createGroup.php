<?php
    if (!empty($_POST['groupName'])){
        session_start();
        if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
            include "../private/openDB.php";
            
            $username = $_SESSION['username'];
            $userID = $_SESSION['user_id'];
            $groupName = mysqli_real_escape_string($connection, $_POST['groupName']);
                    
            $sqlCheck = "SELECT * FROM groups WHERE group_name = '$groupName'";
            $result = mysqli_query($connection, $sqlCheck);
               
            if (mysqli_num_rows($result) === 0) {
                $sqlInsert = "INSERT INTO groups (`group_name`) VALUES ('$groupName')";
                 
                if (mysqli_query($connection, $sqlInsert)) {
                    echo "Group Created";
                    $sqlGet = "SELECT group_id FROM groups WHERE group_name = '$groupName'";
                   
                    if ($result = mysqli_query($connection, $sqlGet)) {
                        $groupID = mysqli_fetch_row($result);   
                        $sqlInsert = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$userID', '$groupID[0]')";
                    
                        mysqli_query($connection, $sqlInsert);
                    } else {
                        echo "Error";
                    }     
                } else {
                    echo "Error";
                }     
            } else {
                echo "Group name exists";   
            }
        }
    } else {
        echo "Field Empty";
    }
?>