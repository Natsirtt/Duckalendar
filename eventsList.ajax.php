<?php

function returnResponse($response) {
    echo json_encode($response);
}

require_once 'Events.class.php';
require_once 'BddConnection.class.php';
require_once 'BddConnectionFailedException.class.php';

$res = null;

if (!isset($_COOKIE['connection'])) {
    $res['status'] = "userUnidentified";
    returnResponse($res);
} else {
//$_POST['date'] = $_GET['d'];
    if (!isset($_POST['date'])) {
        $res['status'] = "inputError";
        returnResponse($res);
    } else {

        try {
            $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
        } catch (BddConnectionFailedException $e) {
            $res['status'] = "bddConnectionError";
            returnResponse($res);
        }

        if ($bddconnection->isConnected()) {
            //Plutôt que de créer une instance d'Event puis de créer le tableau
            //de réponse, nous allons faire la requête nous même car
            //la réponse de la bdd est un tableau tout prêt à être
            //renvoyer ! (a l'exception des deux valeurs de temps dont
            //il faut retirer les secondes, inutilisées par duckalendar
            
            $reqres = $bddconnection->query("SELECT * from events WHERE `date`='" . $_POST['date'] . "' AND login='" . $_COOKIE['connection'] . "'");

            $eventsArray = array();
            $eventsNb = 0;
            while ($event = $reqres->fetch()) {
                $beginTimeExploded = explode(":", $event['beginTime']);
                $event['beginTime'] = $beginTimeExploded[0].":".$beginTimeExploded[1];
                $endTimeExploded = explode(":", $event['endTime']);
            $event['endTime'] = $endTimeExploded[0].":".$endTimeExploded[1];
                $eventsNb = array_push($eventsArray, $event);
            }


            $res['eventsNb'] = $eventsNb;
            $res['eventsArray'] = $eventsArray;
            $res['status'] = "ok";
            returnResponse($res);
        }
    }
}
?>
