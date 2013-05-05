<?php

function returnResponse($res) {
    echo json_encode($res);
}

require_once 'BddConnection.class.php';
require_once 'BddConnectionFailedException.class.php';

$res = null;

//GET pour le débug
if (isset($_GET['m'])) {
    $_POST['m'] = $_GET['m'];
}
if (isset($_GET['y'])) {
    $_POST['y'] = $_GET['y'];
}

if (!isset($_COOKIE['connection'])) {
    $res['status'] = "userUnidentified";
} else if (!isset($_POST['month']) || !isset($_POST['year'])) {
    $res['status'] = "inputError";
} else {
    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        $res['status'] = "bddConnectionError";
    }

    if ($bddconnection->isConnected()) {
        $reqres = $bddconnection->query('SELECT `date` FROM events WHERE MONTH(`date`)="'
                . $_POST['month']
                . '"AND YEAR(`date`)="'
                . $_POST['year']
                . '" AND login="'
                . $_COOKIE['connection'] . '"');
        $hasEventList = array();
        while($result = $reqres->fetch()) {
            $date = new DateTime($result['date']);
            $easterDay = $date->format("d");
            if (array_search($easterDay, $hasEventList) === FALSE) {
                array_push($hasEventList, $easterDay);
            }
        }
        $res['array'] = $hasEventList;
        $res['status'] = "ok";
    }
}

returnResponse($res);

?>
