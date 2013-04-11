<?php

if (isset($_COOKIE['connection'])) {
    require_once 'bddconnection.php';
    
    if ($bdd_connected) {
        $req = 'SELECT ip FROM users WHERE login="'.$_COOKIE['connection'].'"';
        $resreq = $bdd_connection->query($req);
        
        if ($res = $resreq->fetch()) {
            if ($_SERVER['REMOTE_ADDR'] != $res['ip']) {
                $notification = "Votre adresse ip ayant changé, vous avez été déconnecté par mesure de sécurité";
                header('Location: deconnection.php?src=ip');
            }
        } else {
            $notification = "Erreur lors de la récupération de vôtre ancienne ip. Déconnexion forcée";
            header('Location: deconnection.php');
        }
    }
}

?>
