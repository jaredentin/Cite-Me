<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../private/openDB.php";
        $sql = "SELECT group_id FROM users_groups WHERE user_id = '$_SESSION[user_id]'";   
        $result =  mysqli_query($connection, $sql);
        
        $groupsArray = array();
        while ($db_field = mysqli_fetch_assoc($result)){
            array_push($groupsArray, $db_field['group_id']);
        }
        
        $sqlGroups = "SELECT * FROM users_access WHERE group_id IN (" . implode(',', array_map('intval', $groupsArray)) . ")";
        $result = mysqli_query($connection, $sqlGroups); 
        
        $groupsArr = array();
        $quotesArr = array();
        
     //   $testArray = array();
        while ($db_field = mysqli_fetch_assoc($result)){
            array_push($groupsArr, $db_field['user_id']);
          //  array_push($quotesArr, $db_field['quote_text']);
            array_push($quotesArr, $db_field['quote_text']);
            
           // array_push($testArray, $db_field);
        }
        
       
        
//        $arr['first_name'] = array();
//        $arr['last_name'] = array();
        //$arr['quote_text'] = array();

        $finalArr = array();

        $userQuery = "SELECT * FROM users WHERE user_id IN (" . implode(',', array_map('intval', $groupsArr)) . ")";  
        $result = mysqli_query($connection, $userQuery);
      
        
        while ($db_field = mysqli_fetch_assoc($result)){
            for ($i = 0; $i < count($quotesArr); $i++){
                if ($groupsArr[$i] === $db_field['user_id']) {
                $arr = array();
                 
                  //  array_push($arr['user_id'], $groupsArr[$i]);
                    
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
        
        if (count($finalArr) > 0) {
            echo json_encode($finalArr);  
        }
    }
?>