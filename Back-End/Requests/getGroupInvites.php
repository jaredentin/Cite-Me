<?php
    header('Content-Type: text/html; charset=utf-8');

    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

        include "../../private/openDB.php";

        $groupQuery = "SELECT group_id FROM users_groups WHERE user_id = " . $_SESSION['id'];
        $groupResult = $connection->query($groupQuery);
        
        $arr = array('');
        while ($groupFields = $groupResult->fetch_assoc()) {
              array_push($arr, $groupFields['group_id']);
        }
        
        $query = "SELECT * FROM group_invites_queue WHERE group_id IN (" . implode(',', array_map('intval', $arr)) . ")";
        $result = $connection->query($query);
    
        $rows = 0;
        while ($groupFields = $result->fetch_assoc()) {
              $rows++;
        }
 
        if ($rows == 1){
            echo "<span class='result_list'>1 person wants to join your group.</span><br><br><br>";
        } else {
            echo "<span class='result_list'>" . $rows . " people want to join your groups.</span><br><br><br>";
        }
        
        foreach($result as $i) {
            $senderQuery = "SELECT first_name FROM users WHERE user_id = " . $i['sender_id'];
            $groupQuery = "SELECT group_name FROM groups WHERE group_id = " . $i['group_id'];
            $senderName = $connection->query($senderQuery);
            $groupName = $connection->query($groupQuery);

            echo $senderName->fetch_assoc()['first_name'] . " has requested to join your group, " . $groupName->fetch_assoc()['group_name'] . ".";
            echo "<span style='right: 10px; position: absolute;'><button class='group_invite_accept'' name=" . $i['group_invite_id'] . ">Accept</button>&nbsp&nbsp<button class='group_invite_deny' name= " . $i['group_invite_id'] . ">Deny</button></span><br><br>";         
        }        
        
        if (isset($_GET['action'])) {
            if ($_GET['button'] == "accept"){
                $newQuery = "SELECT * FROM group_invites_queue WHERE group_invite_id = " . $connection->real_escape_string($_GET['action']);
                $idResult = $connection->query($newQuery);
                $arr = $idResult->fetch_assoc();

                $groupID = $arr['group_id'];
                $userID = $arr['sender_id'];

                $insertQuery = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$userID', '$groupID')";
                $deleteQuery = "DELETE FROM group_invites_queue WHERE group_invite_id = " . $connection->real_escape_string($_GET['action']);

                if ($connection->query($insertQuery) === FALSE) {
                    echo "Error: " . $insertQuery . "<br>" . $connection->error;
                }

                if ($connection->query($deleteQuery) === FALSE) {
                    echo "Error: " . $deleteQuery . "<br>" . $connection->error;
                }
            }

            if ($_GET['button'] == "deny"){
                $deleteQuery = "DELETE FROM group_invites_queue WHERE group_invite_id = " . $connection->real_escape_string($_GET['action']);

                if ($connection->query($deleteQuery) === FALSE) {
                    echo "Error: " . $deleteQuery . "<br>" . $connection->error;
                }
            }
        }
    }
?>