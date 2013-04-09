<?php

$connectionCookieName = 'connection';

if (isset($_COOKIE[$connectionCookieName])) {
    //On met le cookie dans le passé, afin de déconnecter le client 
   setcookie($connectionCookieName, "", time() - 3600);
   header("Location: index.php?status=deco");
} else {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        include_once '/Ducklandar/bddconnection.php';
        if ($bdd_connected) {
            
        }
    } else if (isset($_POST['login']) || isset($_POST['password'])) {
        header("Location: index.php?status=conErr");
    }
    
    $three_days = 259200;
    setcookie($connectionCookieName, $login, time() + $three_days);
}

?>
