<?php
$title = "Calendrier";
include_once 'inc/header.inc.php';
?>
<script src="inputs.js" type="text/javascript"></script>

<div id="connect">
    <form action="connection.php" method="post">
        <?php if (!isset($_COOKIE['connection'])) { ?>
            <input type="text" name="login" value="login" class="round" /><br />
            <input type="password" value="password" name="pass" class="round" /><br />
            <input type="submit" value="Connexion" />
        <?php } else { ?>
            <p>Bonjour, <?php echo $_COOKIE['connection']; ?></p>
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
</div>

<script src="date.js" type="text/javascript"></script>

<?php include_once 'inc/footer.inc.php'; ?>
