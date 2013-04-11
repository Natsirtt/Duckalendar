<?php

if (isset($_COOKIE['connection'])) {
    require_once 'bddconnection.php';
    
    if ($bdd_connected) {
        $req = 'SELECT ip FROM users WHERE login="'.$_COOKIE['connection'].'"';
        $resreq = $bdd_connection->query($req);
        
        if ($res = $resreq->fetch()) {
            if ($_SERVER['REMOTE_ADDR'] != $res['ip']) {
                header('Location: deconnection.php?src=ipdeco');
            }
        } else {
            header('Location: deconnection?src=iperr.php');
        }
    }
}

?>
