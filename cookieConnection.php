<?php

if (isset($_COOKIE['connection'])) {
    require_once 'BddConnection.class.php';
    $bddconnection = NULL;
    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        header("Location: index.php?status=bddError");
    }
    
    if ($bddconnection->isConnected()) {
        $resreq = $bddconnection->query('SELECT ip FROM users WHERE login="' . $_COOKIE['connection'] . '"');

        if ($res = $resreq->fetch()) {
            if ($_SERVER['REMOTE_ADDR'] != $res['ip']) {
                header('Location: deconnection.php?src=ipdeco');
            }
        } else {
            header('Location: deconnection.php?src=usrerr');
        }
    }
}

?>
