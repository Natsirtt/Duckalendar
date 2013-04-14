<?php

require_once 'cookieConnection.php';

$title = "Calendrier";
if (isset($_GET['status'])) {
    if ($_GET['status'] == "deco") {
        $notification = "Vous êtes maintenant déconnecté";
    } else if ($_GET['status'] == "connected") {
        $notification = "Bonjour " . $_COOKIE['connection'];
    } else if ($_GET['status'] == "conErr") {
        $notification = "La connexion a échouée";
    } else if ($_GET['status'] == "noUserOrPassErr") {
        $notification = "Utilisateur inconnu ou mot de passe incorrect";
    } else if ($_GET['status'] == "alreadyCon") {
        $notification = "Veuillez vous déconnecter avant d'ouvrir un autre compte";
    } else if ($_GET['status'] == "ipdeco") {
        $notification = "Votre adresse ip ayant changé, vous avez été déconnecté par mesure de sécurité";
    } else if ($_GET['status'] == "iperr") {
        $notification = "Erreur lors de la récupération de vôtre ancienne ip. Déconnexion forcée";
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
                <input type="text" name="login" value=<?php echo '"'.$_GET['login'].'"' ?> class="round" />
            <?php } ?>
            <br />
            <input type="password" value="password" name="password" class="round" id="passwordInput" /><br />
            <input type="submit" value="Connexion" />
            <a href="inscription.php">Inscription</a>
<?php } else { ?>
            <form action="deconnection.php" method="post">
                <input type="submit" value="Déconnexion" />
        <?php } ?>
        </form>

</div>

<p id="date"></p>
<input type="image" src="images/fleche gauche.png" id="left" />
<p id="month"></p>
<input type="image" src="images/fleche droite.png" id="right" />
<table id = "calendar" summary = "calendar">
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
<div id = "testBlock">
    <div></div>
    <img src="/Duckalendar/images/plus.png" alt="plus" id="plus" />
    <!--<img src="/Duckalendar/images/moins.png" alt="moins" id="moins" />-->
</div>

<script src="calendar.js" type="text/javascript"></script>

<?php /* if (isset($_GET['login'])) { ?>
    <script type="text/javascript">
       $(document).ready(function() {
          $("#passwordInput").get(0).focus(); 
       });
<?php } */ ?>

<?php include_once 'inc/footer.inc.php'; ?>

