<?php

$connectionCookieName = 'connection';


if (isset($_POST['login']) && isset($_POST['password'])) {
    require_once 'bddconnection.php';
    if ($bdd_connected) {
        $login = trim($_POST['login']);
        $req = 'SELECT salt, password FROM users WHERE login="'.$login.'"';
        $reqres = $bdd_connection->query($req);

        if ($res = $reqres->fetch()) {
            $cryptedPassword = crypt($_POST['password'], $res['salt']);
            if (strcmp($cryptedPassword, $res['password']) == 0) {
                $three_days = 259200;
                setcookie($connectionCookieName, $login, time() + $three_days);
                //Mise à jour de l'ip de l'utilisateur
                $sql = 'UPDATE users SET ip="'.$_SERVER['REMOTE_ADDR'].'" WHERE login="'.$login.'"';
                $reqprep = $bdd_connection->prepare($sql);
                $status = $reqprep->execute();
                if ($status) {
                    header("Location: index.php?status=connected");
                } else {
                    header("Location: index.php?status=conErr&login=$login");
                }
            } else {
                header("Location: index.php?status=noUserOrPassErr&login=$login");
            }
        } else {
            header("Location: index.php?status=noUserOrPassErr&login=$login");
        }
    }
} else if (isset($_POST['login']) || isset($_POST['password'])) {
    header("Location: index.php?status=conErr");
}

?>
