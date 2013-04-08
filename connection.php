<?php

$connectionCookieName = 'connection';

if (isset($_COOKIE[$connectionCookieName])) {
    //On met le cookie dans le passé, afin de déconnecter le client 
   setcookie($connectionCookieName, "", time() - 3600);
} else {
    $three_days = 259200;
    setcookie($connectionCookieName, "toto", time() + $three_days);
}
header("Location: index.php");

?>
