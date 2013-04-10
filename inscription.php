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
        <input type="password" name="password" value="password" class="round" id="passwordInput"/><br />
        <input type="password" name="rePassword" value ="password" class="round" /><br />
        <input type="submit" value="Inscription" id="submitButton" />
    </form>
</div>

<script type="text/javascript">
    $(document).ready(
        function() {
            $("#passwordInput").change(function () {
                var val = $(this).val();
                $.ajax({
                    url: 'inscriptionCheckLogin.ajax.php',
                    type: 'POST',
                    data: {
                        login: val
                    },
                    error: function(j, textStatus, errorThrown) {
                        var notif = $("#notificationMsg");
                        notif.empty();
                        notif.append("Erreur lors de la requête asynchrone");
                    },
                    success: function(data, textStatus, j) {
                        if (data == "exists") {
                            var notif = $("#notificationMsg");
                            notif.empty();
                            notif.append("Ce login est déjà utilisé");
                            $("#submitButton").attr("disabled", "disabled");
                        } else if (data == "doesntExist") {
                            $("#submitButton").removeAttr("disabled");
                        } else {
                            var notif = $("#notificationMsg");
                            notif.empty();
                            notif.append("Erreur côté serveur lors de la requête asynchrone");
                        }
                    }
                });
            });
        });
</script>

<?php include_once 'inc/footer.inc.php'; ?>