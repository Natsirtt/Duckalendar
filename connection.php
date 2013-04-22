<?php

$connectionCookieName = 'connection';


if (isset($_POST['login']) && isset($_POST['password'])) {
    require_once 'BddConnection.class.php';
    $bddconnection = NULL;
    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        header("Location: index.php?status=bddError&login=$login");
    }
    
    if ($bddconnection->isConnected()) {
        $login = trim($_POST['login']);
        $reqres = $bddconnection->query('SELECT salt, password FROM users WHERE login="'.$login.'"');
        $res = $reqres->fetch();
        if ($res) {
            $cryptedPassword = crypt($_POST['password'], $res['salt']);
            if (strcmp($cryptedPassword, $res['password']) == 0) {
                $three_days = 259200;
                setcookie($connectionCookieName, $login, time() + $three_days);
                //Mise à jour de l'ip de l'utilisateur
                $sql = 'UPDATE users SET ip = ? WHERE login = ?';
                $values = array($_SERVER['REMOTE_ADDR'], $login);
                $status = $bddconnection->preparedQuery($sql, $values);
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
