<?php

require_once 'BddConnection.class.php';

try {
    $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
} catch (BddConnectionFailedException $e) {
    echo 'bddError';
}

if ($bddconnection->isConnected() && isset($_POST['login'])) {

    $reqres = $bddconnection->query('SELECT * FROM users WHERE login="' . $_POST['login'] . '"');
    $res = $reqres->fetch();
    if ($res) {
        echo 'exists';
    } else {
        echo 'doesntExist';
    }
} else {
    echo 'error';
}

?>
