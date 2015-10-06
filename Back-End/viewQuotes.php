<?php
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../private/openDB.php";
        
        $userGroupsQuery = "SELECT * FROM users_groups WHERE user_id = '$_SESSION[id]'";
        $userGroupsResult = $connection->query($userGroupsQuery);
        
        $groupsArr['user_id'] = array();
        $groupsArr['group_id'] = array();
        while($userFields = $userGroupsResult->fetch_assoc()){
            array_push($groupsArr['user_id'], $userFields['user_id']);
            array_push($groupsArr['group_id'], $userFields['group_id']);
        }
   
          
            $groupQuery = "SELECT * FROM groups WHERE group_id IN (" . implode(',', array_map('intval', $groupsArr['group_id'])) . ")";
            $groupResult = $connection->query($groupQuery);  
             
            $userQuery = "SELECT * FROM users_access WHERE user_id IN (" . implode(',', array_map('intval', $groupsArr['user_id'])) . ")";
            $userResult = $connection->query($userQuery);
            
         if ($userResult) {     
            $userArr['user_id'] = array('');
            $userArr['quote'] = array('');
            $userArr['group_id'] = array('');
            while($userFields = $userResult->fetch_assoc()){
                array_push($userArr['user_id'], $userFields['user_id']);
                array_push($userArr['quote'], $userFields['quote_text']);
                array_push($userArr['group_id'], $userFields['group_id']);
            }     
            
            //While will loop through each group the user is in  
            while($groupFields = $groupResult->fetch_assoc()){
                $found = 0;
                echo $groupFields['group_name'] . "<br>";
                for ($i = 0; $i < count($userArr['group_id']); $i++){
                    if ($groupFields['group_id'] === $userArr['group_id'][$i]) {
                        echo "&nbsp&nbsp&nbsp" . $userArr['quote'][$i] . "<br>";
                        $found = 1;
                    }
                }
                if ($found === 0) {
                    echo "&nbsp&nbsp&nbsp(No quotes)<br>";   
                }
                echo "<br>";     
            }    
        } else {
            echo "(No Groups)";   
        }
    }
?>