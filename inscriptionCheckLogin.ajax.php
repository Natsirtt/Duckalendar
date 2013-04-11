<?php

require_once 'bddconnection.php';

if (isset($_POST['login'])) {

    $req = 'SELECT * FROM users WHERE login="' . $_POST['login'] . '"';
    $res = $bdd_connection->query($req);

    if ($res->fetch()) {
        echo 'exists';
    } else {
        echo 'doesntExist';
    }
} else {
    echo 'error';
}
?>
