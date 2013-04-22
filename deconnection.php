<?php

$connectionCookieName = 'connection';

//On met le cookie dans le passé, afin de déconnecter le client 
setcookie($connectionCookieName, "", time() - 3600);
if (isset($_GET['src'])) {
    header("Location: index.php?status=".$_GET['src']);
} else {
    header("Location: index.php?status=deco");
}

?>
