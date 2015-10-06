<?php
   
 session_start();
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../private/openDB.php";
        
        $yourQuery = "SELECT group_id FROM users_groups WHERE user_id = '$_SESSION[id]'";   
        $yourQueryResult = $connection->query($yourQuery);
        
        $yourGroupsArr = array();
        while ($db_field = mysqli_fetch_assoc($yourQueryResult)){
            array_push($yourGroupsArr, $db_field['group_id']);
        }
        
        
        //print_r($yourGroupsArr);
        
        
        $groupsQuery = "SELECT * FROM users_access WHERE group_id IN (" . implode(',', array_map('intval', $yourGroupsArr)) . ")";
        $groupsQueryResult = $connection->query($groupsQuery);
        
        
        
        $groupsArr = array();
        $quotesArr = array();
        while ($db_field = mysqli_fetch_assoc($groupsQueryResult)){
            array_push($groupsArr, $db_field['user_id']);
            array_push($quotesArr, $db_field['quote_text']);
            array_push($quotesArr, $db_field['quote_text']);
        }
        
        //print_r($groupsArr);
        //print_r($quotesArr);
        //$arr['user_id'] = array();
        
        $arr['first_name'] = array();
        $arr['last_name'] = array();
        //$arr['quote_text'] = array();

        $finalArr = array();
        
        //$arr = array('user_id' => "", 'quote_text' => "");
        
        $userQuery = "SELECT * FROM users WHERE user_id IN (" . implode(',', array_map('intval', $groupsArr)) . ")";  
        $userQueryResult = $connection->query($userQuery);
     
        while ($db_field = mysqli_fetch_assoc($userQueryResult)){
            
            for ($i = 0; $i < count($quotesArr); $i++){
                
                if ($groupsArr[$i] === $db_field['user_id']) {
                $arr = array();
                   // array_push($arr['user_id'], $groupsArr[$i]);
                    
//                    array_push($arr['first_name'], $db_field['first_name']);
//                    array_push($arr['last_name'], $db_field['last_name']);
//                    array_push($arr['quote_text'], $quotesArr[$i]);
                    
                    array_push($arr, $db_field['first_name']);
                    array_push($arr, $db_field['last_name']);
                    array_push($arr, $quotesArr[$i]);
                    
                    array_push($finalArr, $arr);
                }
            }
        }
     // print_r($finalArr);
        if (count($finalArr) > 0) {
		header("Content-Type: application/json; charset=utf-8", true);
		echo json_encode($finalArr);  
        }
    } else {
        session_destroy();
    }
?>
