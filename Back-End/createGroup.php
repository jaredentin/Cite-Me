<?php
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        if (!empty($_GET['groupName'])){
            include "../private/openDB.php";
            
            $found = 0;
            $groupName = $connection->real_escape_string($_GET['groupName']);
            
            
            
            $query = "SELECT group_name FROM groups";
            $result = $connection->query($query);

            if ($result) {
                $found = 0;
            
                while ($db_field = mysqli_fetch_assoc($result)) {
                    if ($connection->real_escape_string($db_field["group_name"]) === $groupName) {
                        $found = 1;
                        break;
                    }
                }
            
                if ($found == 0) {
                    $group_insert = "INSERT INTO groups (`group_name`) VALUES ('$groupName')";
                    
                    if ($connection->query($group_insert) === FALSE) {
                        echo "Error: " . $group_insert . "<br>" . $connection->error;
                    } else {
                        echo $groupName . " has been created!";
                    }
                    
                    $new_group = "SELECT group_id FROM groups WHERE group_name = '$groupName'";
                    $groupResult = $connection->query($new_group);                   
                    $groupID = $groupResult->fetch_row();
                    
                    $user_insert = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$_SESSION[id]', '$groupID[0]')";
                    
                    if ($connection->query($user_insert) === FALSE) {
                        echo "Error: " . $user_insert . "<br>" . $connection->error;
                    } else {
                        echo "You have been added to the group, " . $groupName;
                    }          
                } else if ($found == 1) {
                    echo $groupName. " already exists.";
                } else {
                    echo "Error";   
                }
            }
        }
    }
?>