<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../../private/openDB.php";
        
        if ($_GET['action'] == "search") {
            if (!empty($_GET['personName'])){
                $personName = $connection->real_escape_string($_GET['personName']);

                $query = "SELECT * FROM users WHERE username LIKE '$personName%'";
                $result = $connection->query($query);

                $uNameArr = array();
                $fNameArr = array();
                $lNameArr = array();
                while ($db_field = mysqli_fetch_assoc($result)){
                    if ($db_field['user_id'] !== $_SESSION['id']) {
                        array_push($uNameArr, $db_field['username']);
                        array_push($fNameArr, $db_field['first_name']);
                        array_push($lNameArr, $db_field['last_name']);
                    }
                }
                if ($result) {
                    if ($uNameArr) {
                        for ($i = 0; $i < count($uNameArr); $i++) {
                            echo "<a class='username_link' id=" . $uNameArr[$i] . " href='toUser_sendGroupRequest.html'>" . $uNameArr[$i] . "</a><h4 style='display: inline;'>&nbsp&nbsp&nbsp(" . $fNameArr[$i] . " " . $lNameArr[$i] . ")</h4><br><br>";   
                        }
                    } else {
                        echo "No results were found.";
                    }
                }
            }
        }
        
        if ($_GET['action'] == "submit form"){
            if (isset($_GET['group']) && isset($_GET['user'])) {
                $group = $connection->real_escape_string($_GET['group']);
                $user = $connection->real_escape_string($_GET['user']);
                $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                
                $groupResult = $connection->query($groupQuery);
                $groupID = $groupResult->fetch_row();

                $nameQuery = "SELECT user_id FROM users WHERE username = '$user'";
                $nameResult = $connection->query($nameQuery);
                $nameID = $nameResult->fetch_row();
                
                $userCheckQuery = "SELECT * FROM group_requests_queue WHERE user_id = '$nameID[0]'";
                $userCheckResult = $connection->query($userCheckQuery);
               
                $found = false;
                while($field = $userCheckResult->fetch_assoc()){      
                    if ($field['group_id'] === $groupID[0]){
                        $found = true;
                    }
                }
                       
                if ($found === false) { 
                    $sql_insert = "INSERT INTO group_requests_queue (`group_id`, `user_id`, `sender_id`) VALUES ('$groupID[0]', '$nameID[0]', '$_SESSION[id]')";
                    
                    if ($connection->query($sql_insert) === FALSE) {
                        echo "Error: " . $sql_insert . "<br>" . $connection->error;
                    } else {
                        echo "Request sent.";   
                    }
                } else {
                    echo "Already pending.";
                }
            }
        }
    }
?>