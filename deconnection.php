<?php

$connectionCookieName = 'connection';

//On met le cookie dans le passé, afin de déconnecter le client 
setcookie($connectionCookieName, "", time() - 3600);
header("Location: index.php?status=deco");

?>
