<?php

function returnResponse($response) {
    echo json_encode($response);
}

//DEBUG ##################
/* if (isset($_GET['en'])) {
  $_POST['eventName'] = $_GET['en'];
  }
  if (isset($_GET['d'])) {
  $_POST['beginDate'] = $_GET['d'];
  $_POST['oriBeginDate'] = $_GET['d'];
  }
  if (isset($_GET['oen'])) {
  $_POST['oriEventName'] = $_GET['oen'];
  $_POST['beginTime'] = "";
  $_POST['endTime'] = "";
  $_POST['endDate'] = "";
  $_POST['desc'] = "";
  }
  if (isset($_GET['req'])) {
  $_POST['req'] = $_GET['req'];
  } */
//########################

require_once 'Events.class.php';
require_once 'BddConnectionFailedException.class.php';
require_once 'BddConnection.class.php';
require_once 'EventAlreadyInDatabaseException.class.php';
require_once 'BddInsertionFailedException.class.php';
require_once 'BddDeleteFailedException.class.php';

$res = null;

try {
    $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
} catch (BddConnectionFailedException $e) {
    $res['status'] = "bddConnectionFailed";
}
if (!isset($_COOKIE['connection'])) {
    $res['status'] = "unidentified";
} else if (!isset($_POST['req'])) {
    $res['status'] = "inputsError";
} else {
    if ($bddconnection->isConnected()) {
        if ($_POST['req'] == "add") {
            //Ajout d'un évènement
            if (!isset($_POST['eventName']) || !isset($_POST['beginDate']) || !isset($_POST['endDate']) || !isset($_POST['beginTime']) || !isset($_POST['endTime']) || !isset($_POST['desc'])) {
                $res['status'] = "inputsError";
            } else {
                $newEvent = new Event($_COOKIE['connection'], $_POST['beginDate'], $_POST['eventName']);
                $newEvent->setBeginTime($_POST['beginTime']);
                $newEvent->setEndTime($_POST['endTime']);
                $newEvent->setEndDate($_POST['endDate']);
                $newEvent->setDescription($_POST['desc']);

                try {
                    $newEvent->putInDatabase($bddconnection, "events");
                    $res['status'] = "ok";
                } catch (EventAlreadyInDatabaseException $e) {
                    $res['status'] = "existsAlready";
                } catch (BddInsertionFailedException $e) {
                    $res['status'] = "insertionFailed";
                }
            }
        } else if ($_POST['req'] == "modif") {
            //Modification d'un évènement
            if (!isset($_POST['eventName']) || !isset($_POST['oriEventName']) || !isset($_POST['oriBeginDate']) || !isset($_POST['endDate']) || !isset($_POST['beginTime']) || !isset($_POST['endTime']) || !isset($_POST['desc'])) {
                $res['status'] = "inputsError";
            } else {
                //Clear to go
                $event = new Event($_COOKIE['connection'], $_POST['oriBeginDate'], $_POST['oriEventName']);
                $ok = true;
                try {
                    $event->constructFromDatabase($bddconnection, "events");
                } catch (NoSuchEventException $e) {
                    $res['status'] = "noSuchEvent";
                    $ok = false;
                }
                if ($ok) {
                    if ($_POST['beginTime'] != "") {
                        $event->setBeginTime($_POST['beginTime']);
                    }
                    if ($_POST['endTime'] != "") {
                        $event->setEndTime($_POST['endTime']);
                    }
                    if ($_POST['endDate'] != "") {
                        $event->setEndDate($_POST['endDate']);
                    }
                    if ($_POST['desc'] != "") {
                        $event->setDescription($_POST['desc']);
                    }
                    $res['status'] = "ok";
                    try {
                        $event->updateDatabase($bddconnection, "events", $_POST['eventName']);
                    } catch (NoSuchEventException $e) {
                        $res['status'] = "noSuchEvent";
                    } catch (BddInsertionFailedException $e) {
                        $res['status'] = "modifFailed";
                    }
                }
            }
        } else if ($_POST['req'] == "del") {
            //Suppression d'un évènement
            if (!isset($_POST['eventName']) || !isset($_POST['beginDate'])) {
                $res['status'] = "inputsError";
            } else {
                //OK
                $event = new Event($_COOKIE['connection'], $_POST['beginDate'], $_POST['eventName']);
                $res['status'] = "ok";
                try {
                    $event->deleteIntoDatabase($bddconnection, "events");
                } catch (NoSuchEventException $e) {
                    $res['status'] = "noSuchEvent";
                } catch (BddDeleteFailedException $e) {
                    $res['status'] = "delFailed";
                }
            }
        } else if ($_POST['req'] == "infos") {
            //Envoi des infos d'un événement
            if (!isset($_POST['eventName']) || !isset($_POST['beginDate'])) {
                $res['status'] = "inputsError";
            } else {
                //OK
                $event = new Event($_COOKIE['connection'], $_POST['beginDate'], $_POST['eventName']);
                $ok = true;
                try {
                    $event->constructFromDatabase($bddconnection, "events");
                } catch (NoSuchEventException $e) {
                    $ok = false;
                    $res['status'] = "NoSuchEvent";
                }
                if ($ok) {
                    //Une classe se sérialise facilement en objet JSON, mais
                    //On va le faire manuellement afin de modifier les
                    //champs de date et de temps
                    $res['desc'] = $event->getDescription();
                    $res['endTime'] = $event->getEndTime()->format("H:i");
                    $res['beginTime'] = $event->getBeginTime()->format("H:i");
                    $res['endDateYMD'] = $event->getEndDate()->format("Y-m-d");
                    $res['endDateDMY'] = $event->getEndDate()->format("d-m-Y");
                    $res['status'] = "ok";
                }
            }
        } else {
            $res['status'] = "unknownReq";
        }
    }
}

returnResponse($res);
?>
