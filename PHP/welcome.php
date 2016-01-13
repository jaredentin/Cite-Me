<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../private/openDB.php";
        
        $sql = "SELECT * FROM users WHERE user_id = '$_SESSION[user_id]'";
        $query = mysqli_query($connection, $sql);
        $firstName = mysqli_fetch_assoc($query)['first_name'];

        $sql = "SELECT * FROM group_requests_queue WHERE user_id = '$_SESSION[user_id]'";
        $query = mysqli_query($connection, $sql);
        $numGroupRequests = mysqli_num_rows($query);
        
        $sql = "SELECT group_id FROM users_groups WHERE user_id = '$_SESSION[user_id]'";   
        $result =  mysqli_query($connection, $sql);
        
        $groupsArray = array();
        while ($db_field = mysqli_fetch_assoc($result)){
            array_push($groupsArray, $db_field['group_id']);
        }
        
        $sql = "SELECT * FROM group_invites_queue WHERE group_id IN (" . implode(',', array_map('intval', $groupsArray)) . ")";
        $query = mysqli_query($connection, $sql); 
        if (empty($groupsArray)) {
            $numGroupInvites = 0;
        } else {
            $numGroupInvites = mysqli_num_rows($query);
        }
         
        $sql = "SELECT * FROM quote_requests_queue WHERE user_id = '$_SESSION[user_id]'";
        $query = mysqli_query($connection, $sql);
        $numQuoteRequests = mysqli_num_rows($query);
        
        $jsonArray = array($firstName, $numGroupRequests, $numGroupInvites, $numQuoteRequests);
        echo json_encode($jsonArray);
    }

?>