<?php

function returnResponse($response) {
    echo json_encode($response);
}

require_once 'Events.class.php';
require_once 'BddConnectionFailedException.class.php';
require_once 'BddConnection.class.php';
require_once 'EventAlreadyInDatabaseException.class.php';
require_once 'BddInsertionFailedException.class.php';

$res = null;

//Ajout d'un évènement
if ($_POST['req'] == "add") {
    if (!isset($_COOKIE['connection']) || !isset($_POST['eventName']) || !isset($_POST['beginDate']) || !isset($_POST['endDate']) || !isset($_POST['beginTime']) || !isset($_POST['endTime']) || !isset($_POST['desc'])) {
        $res['status'] = "inputsError";
        returnResponse($res);
    }

    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        $res['status'] = "bddConnectionFailed";
        returnResponse($res);
    }

    if ($bddconnection->isConnected()) {
        $newEvent = new Event($_COOKIE['connection'], $_POST['beginDate'], $_POST['eventName']);
        $newEvent->setBeginTime($_POST['beginTime']);
        $newEvent->setEndTime($_POST['endTime']);
        $newEvent->setEndDate($_POST['endDate']);
        $newEvent->setDescription($_POST['desc']);

        try {
            $newEvent->putInDatabase($bddconnection, "events");
        } catch (EventAlreadyInDatabaseException $e) {
            $res['status'] = "existsAlready";
        } catch (BddInsertionFailedException $e) {
            $res['status'] = "insertionFailed";
        }

        $res['status'] = "ok";
        returnResponse($res);
    }
} else if ($_POST['req'] == "modif") {
    //Modification d'un évènement
} else if ($_POST['req'] == "del") {
    //Suppression d'un évènement
} else {
    $res['status'] = "unknownReq";
    returnResponse($res);
}
?>
