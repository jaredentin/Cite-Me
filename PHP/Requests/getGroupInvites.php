<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";

        $query = "SELECT group_id FROM users_groups WHERE user_id = " . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);
                
        $arr = array();
        while ($groupFields = mysqli_fetch_assoc($result)) {  
            array_push($arr, $groupFields['group_id']);
        }
        
        if (count($arr) > 0) {
            $query = "SELECT * FROM group_invites_queue WHERE group_id IN (" . implode(',', array_map('intval', $arr)) . ")";
            $result = mysqli_query($connection, $query);
            
            if (mysqli_num_rows($result) === 1){
                echo "<span class='result_list'>1 person wants to join your group.</span><br><br><br>";
            } else {
                echo "<span class='result_list'>" . mysqli_num_rows($result) . " people want to join your groups.</span><br><br><br>";
            }
        } else {
            echo "<span class='result_list'>0 people want to join your group.</span><br><br><br>";   
        } 
        
        foreach($result as $i) {
            $senderQuery = "SELECT first_name FROM users WHERE user_id = " . $i['sender_id'];
            $groupQuery = "SELECT group_name FROM groups WHERE group_id = " . $i['group_id'];
            $senderName = mysqli_query($connection, $senderQuery);
            $groupName = mysqli_query($connection, $groupQuery);

            echo mysqli_fetch_row($senderName)[0] . " has requested to join " . mysqli_fetch_row($groupName)[0] . ".<br>";
            echo "<span style='right: 10px;'><button class='group_invite_accept'' name=" . $i['group_invite_id'] . ">Accept</button>&nbsp&nbsp<button class='group_invite_deny' name= " . $i['group_invite_id'] . ">Deny</button></span><br><br>";         
        }     
        
        if (!empty($_POST['action'])) {
            if ($_POST['button'] === "accept"){
                $newQuery = "SELECT * FROM group_invites_queue WHERE group_invite_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                $result = mysqli_query($connection, $newQuery);
                $arr = mysqli_fetch_assoc($result);
                
                $groupID = $arr['group_id'];
                $userID = $arr['sender_id'];
                
                $insertQuery = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$userID', '$groupID')";
                $deleteQuery = "DELETE FROM group_invites_queue WHERE group_invite_id = " . mysqli_real_escape_string($connection, $_POST['action']);

                mysqli_query($connection, $insertQuery);
                mysqli_query($connection, $deleteQuery);  
            }

            if ($_POST['button'] === "deny"){
                $deleteQuery = "DELETE FROM group_invites_queue WHERE group_invite_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                mysqli_query($connection, $deleteQuery);
            }
        }
    }
?>