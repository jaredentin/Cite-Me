<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../../private/openDB.php";
        //mysqli_set_charset ($connection , 'utf8');
        
        if (isset($_GET['action'])){    
            if ($_GET['action'] == "populate groups"){
                $groupIDQuery = "SELECT group_id FROM users_groups WHERE user_id = " . $_SESSION['id'];
                $idResult = $connection->query($groupIDQuery);
        
                $groupIDrows = array(); 
                while ($arr = $idResult->fetch_assoc()) {
                    array_push($groupIDrows, $arr['group_id']);   
                }
                
                $groupNameQuery = "SELECT group_name FROM groups WHERE group_id IN (" . implode(',', array_map('intval', $groupIDrows)) . ")";
                $nameResult = $connection->query($groupNameQuery);
                
                $groupNameRows = array();
                while ($arr = $nameResult->fetch_assoc()) {
                    array_push($groupNameRows, $arr['group_name']);  
                    
                }
                
                echo '<option selected disabled hidden></option>';
                foreach($groupNameRows as $i) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            }
            
            if ($_GET['action'] == "populate users"){
                if (isset($_GET['selected'])) {
                    $selected = $connection->real_escape_string($_GET['selected']);
                    echo $selected;
                    
                    $groupIDQuery = "SELECT group_id FROM groups WHERE group_name = '$selected'";
                    $groupIDResult = $connection->query($groupIDQuery);
                    
                    $groupIDArr = $groupIDResult->fetch_row();
         
                    $userIDQuery = "SELECT user_id FROM users_groups WHERE group_id = " . $groupIDArr[0];
                    $userIDResult = $connection->query($userIDQuery);
                
                    $userIDrows = array();
                    while ($arr = $userIDResult->fetch_assoc()) {
                        if ($arr['user_id'] !== $_SESSION['id']) {
                            array_push($userIDrows, $arr['user_id']); 
                        }
                    }
                
                    $userNameQuery = "SELECT username FROM users WHERE user_id IN (" . implode(',', array_map('intval', $userIDrows)) . ")";
                    $nameResult = $connection->query($userNameQuery);
   
                    $userNameRows = array();
                    while ($arr = $nameResult->fetch_assoc()) {
                        array_push($userNameRows, $arr['username']);
                    }
                
                    echo '<option selected disabled hidden></option>';
                    foreach($userNameRows as $i) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                }
            }
            
            if ($_GET['action'] == "submit form"){
                if (isset($_GET['group']) && isset($_GET['user']) && isset($_GET['quote'])) {
                    
                    $group = $connection->real_escape_string($_GET['group']);
                    $user = $connection->real_escape_string($_GET['user']);            
                    $quote = $connection->real_escape_string($_GET['quote']);
                       
                    $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                    $groupResult = $connection->query($groupQuery);
                    $groupID = $groupResult->fetch_row();
                    
                    $nameQuery = "SELECT user_id FROM users WHERE username = '$user'";
                    $nameResult = $connection->query($nameQuery);
                    $nameID = $nameResult->fetch_row();
                    
                    $sql_insert = "INSERT INTO quote_requests_queue (`user_id`, `group_id`, `sender_id`, `quote_text`) VALUES ('$nameID[0]', '$groupID[0]', '$_SESSION[id]', '$quote')";
                    
                    if ($connection->query($sql_insert) === FALSE) {
                       echo 'Error: ' . $sql_insert . '<br>' . $connection->error;
                    } else {
                        echo "Quote request sent.";
                    }
                }
            }
        }
    }
?>