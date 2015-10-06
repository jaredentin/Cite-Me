<?php
header('Content-Type: text/html; charset=utf-8');
    // Initialize the session.
    // If you are using session_name("something"), don't forget it now!
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        echo "true" . "<br>";   
    } else {
        echo "false" . "<br>";   
    }

    // Unset all of the session variables.
    //$_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    


    //$_SESSION['PHPSESSID'] = "";
    print_r($_COOKIE);
    echo "<br>";
    print_r($_SESSION);
    // Finally, destroy the session.
    //setcookie("PHPSESSID", "", -1);

    session_destroy();
?>