<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
        $sql = "SELECT * FROM quote_requests_queue WHERE user_id = " . $_SESSION['user_id'];    
        $result = mysqli_query($connection, $sql);
        
        if (mysqli_num_rows($result) === 1){
            echo "<span class='result_list'>You have 1 quote request.</span><br><br><br>";
        } else {
            echo "<span class='result_list'>You have " . mysqli_num_rows($result) . " quote requests.</span><br><br><br>";
        }
        
        foreach($result as $i) {
            $senderQuery = "SELECT first_name FROM users WHERE user_id = " . $i['sender_id'];
            $groupQuery = "SELECT group_name FROM groups WHERE group_id = " . $i['group_id'];
            $senderName = mysqli_query($connection, $senderQuery);
            $groupName = mysqli_query($connection, $groupQuery);

            echo "<span class='result_list'>" . mysqli_fetch_row($senderName)[0] . " has requested to call you " . "'" . $i['quote_text'] . "' in your group, " . mysqli_fetch_row($groupName)[0] . ".<br>";
            echo "<span style='right: 10px;'><button class='quote_accept' name=" . $i['quote_request_id'] . ">Accept</button>&nbsp&nbsp<button class='quote_deny' name= " . $i['quote_request_id'] . ">Deny</button></span></span><br><br>";         
        }

        if (!empty($_POST['action'])) {
            if ($_POST['button'] === "accept"){
                $newQuery = "SELECT * FROM quote_requests_queue WHERE quote_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                $idResult = mysqli_query($connection, $newQuery);
                $arr = mysqli_fetch_assoc($idResult);

                $userID = $arr['user_id'];
                $groupID = $arr['group_id'];
                $quoteText = mysqli_real_escape_string($connection, $arr['quote_text']);

                echo $userID . "<br>";
                echo $groupID . "<br>";
                echo $quoteText . "<br>";
                
                $insertQuery = "INSERT INTO users_access (`user_id`, `group_id`, `quote_text`) VALUES ('$userID', '$groupID', '$quoteText')";
                $deleteQuery = "DELETE FROM quote_requests_queue WHERE quote_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);

                mysqli_query($connection, $insertQuery);
                mysqli_query($connection, $deleteQuery);
            }

            if ($_POST['button'] === "deny"){
                $deleteQuery = "DELETE FROM quote_requests_queue WHERE quote_request_id = " . mysqli_real_escape_string($connection, $_POST['action']);
                mysqli_query($connection, $deleteQuery);
            }
        }
    }
?>