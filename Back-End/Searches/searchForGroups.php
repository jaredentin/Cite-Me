<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../../private/openDB.php";
        
        if (!empty($_GET['groupName'])){
            $groupName = $connection->real_escape_string($_GET['groupName']);

            $query = "SELECT * FROM groups WHERE group_name LIKE '$groupName%'";
            $result = $connection->query($query);

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
        
        if (isset($_GET['action'])) {
            if ($_GET['action'] == "submit request"){
                if (isset($_GET['group'])) {        
                    $group = $connection->real_escape_string($_GET['group']);
                       
                    $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                    $groupResult = $connection->query($groupQuery);
                    $groupID = $groupResult->fetch_row();
                    
                    
//                    $userIDQuery = "SELECT user_id FROM users_groups WHERE group_id = '$group[0]'";
//                    $userIDResult = $connection->query($userIDQuery);
//                    
////                    $idArr = array();
////                    while ($db_field = $userIDResult->fetch_assoc()) {
////                           array_push($idArr, $dbField['user_id']);
////                    }
////                    
////                    
////                    
////                    
////                    $userQuery = "SELECT * FROM users WHERE user_id IN (" . implode(',', array_map('intval', $idArr)) . ")";
////                    $userResult = $connection->query($userQuery);
////                    //$uName = $userResult->fetch_row();
                    
                    
                    $userCheckQuery = "SELECT * FROM group_invites_queue WHERE sender_id = '$_SESSION[id]'";
                    $userCheckResult = $connection->query($userCheckQuery);
               
                    $found = false;
                    while($field = $userCheckResult->fetch_assoc()){
                        if ($field['group_id'] === $groupID[0]){
                            $found = true;
                        }
                    }
                           
                    if ($found === false) {
                        $sql_insert = "INSERT INTO group_invites_queue (`group_id`, `sender_id`) VALUES ('$groupID[0]', '$_SESSION[id]')";     
                        if ($connection->query($sql_insert) === FALSE) {
                            echo "Error: " . $sql_insert . "<br>" . $connection->error;
                        } else {
                            echo "Request sent.";   
                        }
                    } else {
                        echo "You already have a request pending for this group.";
                    }
                }
            }
        }
    }
?>