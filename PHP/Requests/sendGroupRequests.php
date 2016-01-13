<?php
    session_start();
    if (!empty($_SESSION['logged_in'] && $_SESSION['user_id'] && $_SESSION['username'])) {
        include "../../private/openDB.php";
        
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
            
            echo "<option selected disabled hidden value=''></option>";
            foreach($groupNameRows as $i) {
                echo "<option value=" . $i . ">" . $i . "</option>";
            }
        }        
    }
?>