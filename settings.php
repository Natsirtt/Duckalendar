<?php
require_once 'cookieConnection.php';

$title = "Paramètres";

require_once './BddConnection.class.php';
require_once './BddConnectionFailedException.class.php';

//Traitement du formulaire
if (isset($_COOKIE['connection'])) {
    if (isset($_POST['noWorkColor']) && isset($_POST['hasEventColor']) && isset($_POST['incomingEventsDaysNb'])) {
        if ($_POST['incomingEventsDaysNb'] <= 0) {
            $notification = "Le nombre de jours à balayer doit êre supérieur ou égal à 1";
        } else {
            try {
                $inputConnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
            } catch (BddConnectionFailedException $e) {
                $notification = "Erreur de connexion à la base de données";
            }
            if ($inputConnection->isConnected()) {
                $sql = "UPDATE settings SET noWorkColor=?, hasEventColor=?, incomingEventsDaysNb=? WHERE login=?";
                $values = array($_POST['noWorkColor'], $_POST['hasEventColor'], $_POST['incomingEventsDaysNb'], $_COOKIE['connection']);
                $status = $inputConnection->preparedQuery($sql, $values);
                if (!$status) {
                    $notification = "La modification à échoué";
                } else {
                    //Redirection vers l'index, la modification étant effectuée
                    header("Location: index.php");
                }
            }
        }
    }
} else {
    header("Location: index.php?status=connectionRequired");
}

//------------------------------------------------------------------------------

try {
    $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
} catch (BddConnectionFailedException $e) {
    $notification = "Erreur de connexion à la base de données";
}

if ($bddconnection->isConnected()) {
    $reqres = $bddconnection->query('SELECT noWorkColor, hasEventColor, incomingEventsDaysNb FROM settings WHERE login="' . $_COOKIE['connection'] . '"');
    $res = $reqres->fetch();
    if (!$res) {
        $notification = "Erreur lors de la requête SQL";
    }
}

require_once 'inc/header.inc.php';

if ($res) {
    ?>

    <div id="bodyContent">
        <h1>Configuration</h1>
        <form action="" method="post">
            <p><label>Couleur des jours feriés : </label><input type="color" value="<?php echo $res['noWorkColor'] ?>" name="noWorkColor" /></p>
            <p><label>Couleur des jours à événements : </label><input type="color" value="<?php echo $res['hasEventColor'] ?>" name="hasEventColor" /></p> 
            <p><label>Nombre de jours à balayer pour afficher les prochains événements :</label> <input type="number" value="<?php echo $res['incomingEventsDaysNb']; ?>" name="incomingEventsDaysNb" id="daysNbChooser" /></p>
            <input type="submit" value="Modifier" />
        </form>

<?php } else { ?>
        <h3>Une connexion à la base de données est nécessaire à l'utilisation de cette page.</h3>
        <p>Essayez de rafraîchir la page, ou contactez un administrateur si le problème persiste.</p>
<?php } ?>

    </div>
<script type="text/javascript">
    $(document).ready(function() {
        var daysNbChooser = $("#daysNbChooser");
        daysNbChooser.change(function() {
            if (daysNbChooser.val() < 1) {
                daysNbChooser.val("1");
            }
        });
    });
</script>
<?php include_once 'inc/footer.inc.php'; ?>
