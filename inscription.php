<?php

mt_srand();

function randomStr($len) {
    $chars = "abcdefghijklmnopqrstuvwxyz1234567890)]+=}{(['#\"\\/!?,.;:%&_";
    $res = "";
    $charsLen = strlen($chars);
    for ($i = 0; $i < $len; $i++) {
        $res .= $chars[mt_rand(0, $charsLen - 1)];
    }
    return $res;
}

$title = "Inscription";

include_once 'bddconnection.php';

if ($bdd_connected) {

    if (isset($_POST['login']) && isset($_POST['password'])
                               && isset($_POST['rePassword'])) {
        $req = 'SELECT * FROM users WHERE login="'.$_POST['login'].'"';
        $res = $bdd_connection->query($req);
        
        if ($res->fetch()) {
            $notification = "Ce login est déjà utilisé";
        } else {
            if (strcmp($_POST['password'], $_POST['rePassword']) == 0) {
                $salt = randomStr(32);
                $cryptedPassword = crypt($_POST['password'], $salt);

                $sql = "INSERT INTO users (login, password, salt, ip) VALUES (?, ?, ?, ?)";
                $reqprep = $bdd_connection->prepare($sql);

                $status = $reqprep->execute(array($_POST['login'], $cryptedPassword, $salt, $_SERVER['REMOTE_ADDR']));
                if ($status) {
                    $notification = "Vous êtes maintenant inscrit";
                } else {
                    $notification = "Erreur lors de l'inscription, veuillez réessayer";
                }
            } else {
                $notification = "Les deux mots de passe doivent être les mêmes";
            }
        }
    } else if (isset($_POST['login']) || isset($_POST['password'])
                                      || isset($_POST['rePassword'])) {
        $notification = "Veuillez remplir tout les champs pour pouvoir vous inscrire !";
    }
}

include_once 'inc/header.inc.php';
?>

<script src="inputs.js"></script>

<div id="inscription">
    <form action="" method="post">
        <input type="text" name="login" value="login" class="round" /><br />
        <input type="password" name="password" value="password" class="round" /><br />
        <input type="password" name="rePassword" value ="password" class="round" /><br />
        <input type="submit" value="Inscription" />
    </form>
</div>

<?php include_once 'inc/footer.inc.php'; ?>