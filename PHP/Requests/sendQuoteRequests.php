<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
        if (!empty($_POST['action'])){
            if ($_POST['action'] === "populate groups"){
                $groupIDQuery = "SELECT group_id FROM users_groups WHERE user_id = " . $_SESSION['user_id'];
                $idResult = mysqli_query($connection, $groupIDQuery);
        
                $groupIDrows = array(); 
                while ($arr = mysqli_fetch_assoc($idResult)) {
                    array_push($groupIDrows, $arr['group_id']);   
                }
                
                $groupNameQuery = "SELECT group_name FROM groups WHERE group_id IN (" . implode(',', array_map('intval', $groupIDrows)) . ")";
                $nameResult = mysqli_query($connection, $groupNameQuery);
                
                $groupNameRows = array();
                while ($arr = mysqli_fetch_assoc($nameResult)) {
                    array_push($groupNameRows, $arr['group_name']);  
                }
                
                echo '<option selected disabled hidden></option>';
                foreach($groupNameRows as $i) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            }
            
            if ($_POST['action'] === "populate users"){
                if (!empty($_POST['selected'])) {
                    $selected = $connection->real_escape_string($_POST['selected']);
                    echo $selected;
                    
                    $groupIDQuery = "SELECT group_id FROM groups WHERE group_name = '$selected'";
                    $groupIDResult = mysqli_query($connection, $groupIDQuery);
                    
                    $groupIDArr = mysqli_fetch_row($groupIDResult);
         
                    $userIDQuery = "SELECT user_id FROM users_groups WHERE group_id = " . $groupIDArr[0];
                    $userIDResult = mysqli_query($connection, $userIDQuery);
                
                    $userIDrows = array();
                    while ($arr = mysqli_fetch_assoc($userIDResult)) {
                        if ($arr['user_id'] !== $_SESSION['user_id']) {
                            array_push($userIDrows, $arr['user_id']); 
                        }
                    }
                
                    $userNameQuery = "SELECT username FROM users WHERE user_id IN (" . implode(',', array_map('intval', $userIDrows)) . ")";
                    $nameResult = mysqli_query($connection, $userNameQuery);
   
                    $userNameRows = array();
                    while ($arr = mysqli_fetch_assoc($nameResult)) {
                        array_push($userNameRows, $arr['username']);
                    }
                
                    echo '<option selected disabled hidden></option>';
                    foreach($userNameRows as $i) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                }
            }
            
            if ($_POST['action'] == "submit form"){
                if (!empty($_POST['group'] && $_POST['user'] && $_POST['quote'])) {
                     
                    $group = mysqli_real_escape_string($connection, $_POST['group']);
                    $user = mysqli_real_escape_string($connection, $_POST['user']);            
                    $quote = mysqli_real_escape_string($connection, trim($_POST['quote'], '"'));
                    
                    $groupQuery = "SELECT group_id FROM groups WHERE group_name = '$group'";
                    $groupResult = mysqli_query($connection, $groupQuery);
                    $groupID = mysqli_fetch_row($groupResult)[0];

                    $nameQuery = "SELECT user_id FROM users WHERE username = '$user'";
                    $nameResult = mysqli_query($connection, $nameQuery);
                    $nameID = mysqli_fetch_row($nameResult)[0];

                    $sql_insert = "INSERT INTO quote_requests_queue (`user_id`, `group_id`, `sender_id`, `quote_text`) VALUES ('$nameID', '$groupID', '$_SESSION[user_id]', '$quote')";
                    mysqli_query($connection, $sql_insert);
                    echo "Quote request sent";
                }
            }
        }
    }
?>