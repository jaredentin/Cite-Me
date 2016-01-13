<?php
    session_start();

    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
        if (!empty($_POST['groupName'])){
            $groupName = mysqli_real_escape_string($connection, $_POST['groupName']);

            $query = "SELECT * FROM groups WHERE group_name LIKE '$groupName%'";
            $result = mysqli_query($connection, $query);

            $arr = array();
            while ($db_field = mysqli_fetch_assoc($result)){
                array_push($arr, $db_field['group_name']);
            }
            if ($result) {
                if ($arr) {
                    foreach ($arr as $i) {
                        echo "<a class='group_link' id=" . $i . " href='toGroup_sendGroupRequest.html'>" . $i . "</a><br><br>";
                    }
                } else {
                    echo "No results were found.";
                }
            }
        }
        
        if (!empty($_POST['action'])) {
            if ($_POST['action'] === "submit request"){
                if (!empty($_POST['group'])) {  
                    $group =  mysqli_real_escape_string($connection, $_POST['group']);      
                    $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                    $groupResult = mysqli_query($connection, $groupQuery);
                    $groupID = mysqli_fetch_row($groupResult)[0];
                    $userID = $_SESSION['user_id'];
                    
                    $checkQuery = "SELECT user_id FROM users_groups WHERE user_id = '$userID' AND group_id = '$groupID'";
                    $checkResult = mysqli_query($connection, $checkQuery);
                    
                    if (mysqli_num_rows($checkResult) === 0) {
                        $userCheckQuery = "SELECT sender_id FROM group_invites_queue WHERE sender_id = '$userID' AND group_id = '$groupID'";
                        $userCheckResult = mysqli_query($connection, $userCheckQuery);

                        if (mysqli_num_rows($userCheckResult) === 0) {
                            $sql_insert = "INSERT INTO group_invites_queue (`group_id`, `sender_id`) VALUES ('$groupID', '$userID')";     
                            if (mysqli_query($connection, $sql_insert)) {
                                echo "Request sent";
                            }
                        } else {
                            echo "You already have a request pending for this group";   
                        }
                    } else {
                        echo "You are already in this group";   
                    }
                }
            }
        }
    }
?>