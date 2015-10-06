<?php
header('Content-Type: text/html; charset=utf-8');

    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../../private/openDB.php";
        
        $query = "SELECT * FROM group_requests_queue WHERE user_id = " . $_SESSION['id'];
        $result = $connection->query($query);
    
        $rows = 0;
        while ($groupFields = $result->fetch_assoc()) {
              $rows++;
        }
 
        if ($rows == 1){
            echo "<span class='result_list'>You have 1 group invite.</span<br><br><br>";
        } else {
            echo "<span class='result_list'>You have " . $rows . " group invites.</span><br><br><br>";
        }
        
        foreach($result as $i) {
            $senderQuery = "SELECT first_name FROM users WHERE user_id = " . $i['sender_id'];
            $groupQuery = "SELECT group_name FROM groups WHERE group_id = " . $i['group_id'];
            $senderName = $connection->query($senderQuery);
            $groupName = $connection->query($groupQuery);

            echo $senderName->fetch_assoc()['first_name'] . " has invited you to join the group, " . $groupName->fetch_assoc()['group_name'] . ".";
            echo "<span style='right: 10px; position: absolute;'><button class='group_accept' name=" . $i['group_request_id'] . ">Accept</button>&nbsp&nbsp<button class='group_deny' name= " . $i['group_request_id'] . ">Deny</button></span><br><br>";         
        }
        
        if (isset($_GET['action'])) {
            if ($_GET['button'] == "accept"){
                $newQuery = "SELECT * FROM group_requests_queue WHERE group_request_id = " . $connection->real_escape_string($_GET['action']);
                $idResult = $connection->query($newQuery);
                $arr = $idResult->fetch_assoc();

                $userID = $arr['user_id'];
                $groupID = $arr['group_id'];
                $quoteText = $arr['quote_text'];

                $insertQuery = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$userID', '$groupID')";
                $deleteQuery = "DELETE FROM group_requests_queue WHERE group_request_id = " . $connection->real_escape_string($_GET['action']);

                if ($connection->query($insertQuery) === FALSE) {
                    echo "Error: " . $insertQuery . "<br>" . $connection->error;
                }

                if ($connection->query($deleteQuery) === FALSE) {
                    echo "Error: " . $deleteQuery . "<br>" . $connection->error;
                }
            }

            if ($_GET['button'] == "deny"){
                $deleteQuery = "DELETE FROM group_requests_queue WHERE group_request_id = " . $connection->real_escape_string($_GET['action']);

                if ($connection->query($deleteQuery) === FALSE) {
                    echo "Error: " . $deleteQuery . "<br>" . $connection->error;
                }
            }
        } else {
            include "../../private/closeDB.php";
        }
    }
?>