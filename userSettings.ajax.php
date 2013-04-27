<?php

$res = null;

require_once 'BddConnection.class.php';
require_once 'BddConnectionFailedException.class.php';

if (!isset($_COOKIE['connection'])) {
    $res['status'] = "unidentified";
} else {
    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        $res['status'] = "bddError";
    }

    if ($bddconnection->isConnected()) {
        $reqres = $bddconnection->query('SELECT noWorkColor, hasEventColor FROM settings WHERE login="'.$_COOKIE['connection'].'"');
        $result = $reqres->fetch();
        if (!$result) {
            $res['status'] = "queryError";
        } else {
            $res['status'] = "ok";
            $res['settings'] = $result;
        }
    }
}

echo json_encode($res);
?>
