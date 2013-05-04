<?php
//On considère les utilisateurs Français pour
//tout action sur les dates... Parce que bon.
date_default_timezone_set("Europe/Paris");

require_once 'cookieConnection.php';

$title = "Calendrier";
if (isset($_GET['status'])) {
    if ($_GET['status'] == "deco") {
        $notification = "Vous êtes maintenant déconnecté";
    } else if ($_GET['status'] == "connected") {
//        $notification = "Bonjour " . $_COOKIE['connection'];
    } else if ($_GET['status'] == "conErr") {
        $notification = "La connexion a échouée";
    } else if ($_GET['status'] == "noUserOrPassErr") {
        $notification = "Utilisateur inconnu ou mot de passe incorrect";
    } else if ($_GET['status'] == "alreadyCon") {
        $notification = "Veuillez vous déconnecter avant d'ouvrir un autre compte";
    } else if ($_GET['status'] == "ipdeco") {
        $notification = "Par mesure de sécurité, vous avez été déconnecté suite au changement de votre adresse ip";
    } else if ($_GET['status'] == "usrerr") {
        $notification = "Cookie de connection érroné. Déconnexion forcée";
    } else if ($_GET['status'] == "bddError") {
        $notification = "Erreur de connexion à la base de données";
    } else if ($_GET['status'] == "connectionRequired") {
        $notification = "Veuillez vous connecter afin d'utiliser Duckalendar";
    }
}

require_once './BddConnection.class.php';
require_once './BddConnectionFailedException.class.php';
require_once './BddNotConnectedException.class.php';
require_once './Events.class.php';
//Préparation des prochains événements avant d'inclure le header (afin de pouvoir
//faire des notifications)
$incomingEventsReady = false;
if (isset($_COOKIE['connection'])) {
    try {
        $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
    } catch (BddConnectionFailedException $e) {
        $notification = "Erreur lors de la tentative de connexion à la base de données";
    }

    if ($bddconnection->isConnected()) {
        $reqres = $bddconnection->query('SELECT incomingEventsDaysNb FROM settings WHERE login="' . $_COOKIE['connection'] . '"');
        $res = $reqres->fetch();
        if (!$res) {
            $notification = "Base de données inconsistante, contacez un administrateur";
        } else {
            //OK
            $daysToParse = $res['incomingEventsDaysNb'];
        }

        //On cherche les événements des $daysToParse prochains jours
        $incomingEventsArray = array();
        $sql = 'SELECT * FROM `events` WHERE `login`="' . $_COOKIE['connection'] . '" AND `date` BETWEEN DATE(NOW()) AND DATE(DATE_ADD(CURRENT_DATE(), INTERVAL ' . $daysToParse . ' DAY))';
        $reqres = $bddconnection->query($sql);
        while ($res = $reqres->fetch()) {
            array_push($incomingEventsArray, $res);
        }
        $incomingEventsReady = true;
    }
}

require_once 'inc/header.inc.php';
?>
<script src="inputs.js" type="text/javascript"></script>

<div id="connect">
    <?php if (!isset($_COOKIE['connection'])) { ?>
        <form action="connection.php" method="post">
            <?php if (!isset($_GET['login'])) { ?>
                <input type="text" name="login" value="login" class="round" />
            <?php } else { ?>
                <input type="text" name="login" value=<?php echo '"' . $_GET['login'] . '"' ?> class="round" />
            <?php } ?>
            <br />
            <input type="password" name="password" class="round" id="passwordInput" <?php
            if (isset($_GET['login'])) {
                echo 'autofocus="autofocus"';
            } else {
                echo 'value="password"';
            }
            ?>/><br />
            <input type="submit" value="Connexion" />
            <a href="inscription.php">Inscription</a>
        <?php } else { ?>
            <form action="deconnection.php" method="post">
                <p>Connecté en tant que <a href="settings.php"><?php echo $_COOKIE['connection']; ?></a>.&nbsp;<input type="submit" value="Déconnexion" id="decoButton" /></p>
            <?php } ?>
        </form>

</div>

<div id="leftPanel">
    <div id="topLeftPanel">
        <h3>Prochains événements</h3>
    </div>
    <div id="innerLeftPanel">
        <dl>
            <?php
            if ($incomingEventsReady) {
                $len = count($incomingEventsArray);
                for ($i = 0; $i < $len; $i++) {
                    $event = $incomingEventsArray[$i];
                    $beginDate = new DateTime($event['date']);
                    echo "<dt>[" . $beginDate->format("d-m-Y") . "][" . $event['beginTime'] . "] " . $event['name'] . "</dt>";
                    if ($event['desc'] != "") {
                        echo "<dd>" . $event['desc'] . "</dd>";
                    }
                }
            }
            ?>
        </dl>
    </div>
    <div id="bottomLeftPanel">
        <img src="/Duckalendar/images/fleche gauche.png" alt="toggle" id="leftPanelToggle" />
    </div>
</div>

<p id="date"></p>
<input type="image" src="images/fleche gauche.png" id="left" />
<p id="month"></p>
<input type="image" src="images/fleche droite.png" id="right" />
<table id="calendar" summary="calendar">
    <tr> <!-- Liste des jours de la semaine -->
        <th>L</th>
        <th>M</th>
        <th>M</th>
        <th>J</th>
        <th>V</th>
        <th>S</th>
        <th>D</th>
    </tr>
    <tbody id="calendarBody">        
    </tbody>
</table>

<div id="rightPanel">
    <div id="topRightPanel">

    </div>
    <div id="innerRightPanel">

    </div>
    <div id="bottomRightPanel">
        <img src="/Duckalendar/images/plus.png" alt="plus" id="plus" />
        <img src="/Duckalendar/images/undo.png" alt="undo" id="undo" />
        <form id="addEventForm" method="post" action="">
            <input type="submit" value="Enregistrer" id="newEventSubmit" />
        </form>
        <img src="/Duckalendar/images/ajax-loader.gif" alt="loading" id="newEventStatus" />
        <!--<img src="/Duckalendar/images/moins.png" alt="moins" id="moins" />-->
        <img src="/Duckalendar/images/fleche droite.png" alt="fermer panel" id="panelArrow" />
    </div>
</div>

<script src="calendar.js" type="text/javascript"></script>

<?php
//Rend le focus à l'input du password en cas d'erreur du mot de passe
/* if (isset($_GET['login'])) { ?>
  <script type="text/javascript">
  $(document).ready(function() {
  $("#passwordInput").get(0).focus();
  });
  <?php } */
?>

<?php include_once 'inc/footer.inc.php'; ?>

