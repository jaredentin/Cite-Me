<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
        $query = "SELECT * FROM group_requests_queue WHERE user_id = " . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);
        $arr = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) === 1){
            echo "<span class='result_list'>You have 1 group invite.</span<br><br><br>";
        } else {
            echo "<span class='result_list'>You have " . mysqli_num_rows($result) . " group invites.</span><br><br><br>";
        }
        
        foreach($result as $i) {
            $senderQuery = "SELECT first_name FROM users WHERE user_id = " . $i['sender_id'];
            $groupQuery = "SELECT group_name FROM groups WHERE group_id = " . $i['group_id'];
            $senderName = mysqli_query($connection, $senderQuery);
            $groupName = mysqli_query($connection, $groupQuery);

            echo mysqli_fetch_row($senderName)[0] . " has invited you to join the group, " . mysqli_fetch_row($groupName)[0] . ".<br>";
            echo "<span style='right: 10px;'><button class='group_accept' name=" . $i['group_request_id'] . ">Accept</button>&nbsp&nbsp<button class='group_deny' name= " . $i['group_request_id'] . ">Deny</button></span><br><br>";         
        }
        
        if (!empty($_POST['action'])) {
            if ($_POST['button'] === "accept"){
                $newQuery = "SELECT * FROM group_requests_queue WHERE group_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                $idResult = mysqli_query($connection, $newQuery);
                $arr = mysqli_fetch_assoc($idResult);

                $userID = $arr['user_id'];
                $groupID = $arr['group_id'];

                $insertQuery = "INSERT INTO users_groups (`user_id`, `group_id`) VALUES ('$userID', '$groupID')";
                $deleteQuery = "DELETE FROM group_requests_queue WHERE group_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);

                mysqli_query($connection, $insertQuery);
                mysqli_query($connection, $deleteQuery);
            }

            if ($_POST['button'] === "deny"){
                $deleteQuery = "DELETE FROM group_requests_queue WHERE group_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                mysqli_query($connection, $deleteQuery);
            }
        }
    }
?>