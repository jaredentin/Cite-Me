<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        include "../../private/openDB.php";
        //mysqli_set_charset ($connection , 'utf8');
        
        if ($_GET['action'] == "populate groups"){
            $groupIDQuery = "SELECT group_id FROM users_groups WHERE user_id = " . $_SESSION['id'];
            $idResult = $connection->query($groupIDQuery);

            $groupIDrows = array(); 
            while ($arr = $idResult->fetch_assoc()) {
                array_push($groupIDrows, $arr['group_id']);   
            }

            $groupNameQuery = "SELECT group_name FROM groups WHERE group_id IN (" . implode(',', array_map('intval', $groupIDrows)) . ")";
            $nameResult = $connection->query($groupNameQuery);

            $groupNameRows = array();
            while ($arr = $nameResult->fetch_assoc()) {
                array_push($groupNameRows, $arr['group_name']);
            }
            echo "<option selected disabled hidden value=''></option>";
            foreach($groupNameRows as $i) {
                echo "<option value=" . $i . ">" . $i . "</option>";
            }
        }        
    }
?>