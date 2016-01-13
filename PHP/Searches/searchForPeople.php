<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
        if ($_POST['action'] === "search") {
            if (!empty($_POST['personName'])){
                $personName = mysqli_real_escape_string($connection, $_POST['personName']);

                $query = "SELECT * FROM users WHERE username LIKE '$personName%' OR first_name LIKE '$personName%' OR last_name LIKE '$personName%'";
                $result = mysqli_query($connection, $query);

                $uNameArr = array();
                $fNameArr = array();
                $lNameArr = array();
                while ($db_field = mysqli_fetch_assoc($result)){        
                    if ($db_field['user_id'] !== $_SESSION['user_id']) {
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
        
        if (!empty($_POST['action'])) {
            if ($_POST['action'] === "submit form"){
                if (!empty($_POST['group'] && $_POST['user'])) {
                    $group = mysqli_real_escape_string($connection, $_POST['group']);
                    $user = mysqli_real_escape_string($connection, $_POST['user']);
                         
                    $nameQuery = "SELECT user_id FROM users WHERE username = '$user'";
                    $nameResult = mysqli_query($connection, $nameQuery);
                    $nameID = mysqli_fetch_row($nameResult)[0];
                       
                    $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                    $groupResult = mysqli_query($connection, $groupQuery);
                    $groupID = mysqli_fetch_row($groupResult)[0];
                    
                    $checkQuery = "SELECT user_id FROM users_groups WHERE group_id = '$groupID' AND user_id = '$nameID'";
                    $checkResult = mysqli_query($connection, $checkQuery);
                    
                    if (mysqli_num_rows($checkResult) === 0) {
                        $userCheckQuery = "SELECT * FROM group_requests_queue WHERE user_id = '$nameID' AND group_id = '$groupID'";
                        $userCheckResult = mysqli_query($connection, $userCheckQuery);

                        if (mysqli_num_rows($userCheckResult) === 0) {
                            $sql_insert = "INSERT INTO group_requests_queue (`group_id`, `user_id`, `sender_id`) VALUES ('$groupID', '$nameID', '$_SESSION[user_id]')";

                            if (mysqli_query($connection, $sql_insert)) {
                                echo "Request sent.";
                            }
                        } else {
                            echo "Already pending.";
                        }
                    } else {
                        echo "This person is already a member of " . $group;
                    }
                }
            }
        }
    }
?>