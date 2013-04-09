<?php

$connectionCookieName = 'connection';


if (isset($_POST['login']) && isset($_POST['password'])) {
    require_once 'bddconnection.php';
    if ($bdd_connected) {
        $req = 'SELECT salt, password FROM users WHERE login="'.$_POST['login'].'"';
        $res = $bdd_connection->query($req);

        if ($res = $res->fetch()) {
            $cryptedPassword = crypt($_POST['password'], $res['salt']);
            if (strcmp($cryptedPassword, $res['password']) == 0) {
                $three_days = 259200;
                setcookie($connectionCookieName, $_POST['login'], time() + $three_days);
                header("Location: index.php?status=connected");
            } else {
                header("Location: index.php?status=noUserOrPassErr");
            }
        } else {
            header("Location: index.php?status=noUserOrPassErr");
        }
    }
} else if (isset($_POST['login']) || isset($_POST['password'])) {
    header("Location: index.php?status=conErr");
}

?>
