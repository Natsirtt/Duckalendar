<?php

if (isset($_COOKIE['connection'])) {
    header("Location: index.php?status=alreadyCon");
}

mt_srand();

function randomStr($len) {
    $chars = "abcdefghijklmnopqrstuvwxyz1234567890)]+=}{(['#\"\\/!?,.;:%&";
    $res = "";
    $charsLen = strlen($chars);
    for ($i = 0; $i < $len; $i++) {
        $res .= $chars[mt_rand(0, $charsLen - 1)];
    }
    return $res;
}

$title = "Inscription";

require_once 'BddConnection.class.php';
try {
    $bddconnection = new BddConnection(BddConnection::$mysql, "localhost", "duckalendar", "root", "motdepasse");
} catch (BddConnectionFailedException $e) {
    $notification = "Erreur de connexion à la base de données";
}

if ($bddconnection->isConnected()) {

    if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['rePassword'])) {
        if ($_POST['login'] == "") {
            $notification = "Veuillez entrer un login";
        } else {
            $reqres = $bddconnection->query('SELECT * FROM users WHERE login="' . $_POST['login'] . '"');
            $res = $reqres->fetch();
            if ($res) {
                $notification = "Ce login est déjà utilisé";
            } else {
                if (strcmp($_POST['password'], $_POST['rePassword']) == 0) {
                    $salt = randomStr(32);
                    $cryptedPassword = crypt($_POST['password'], $salt);

                    $sql = "INSERT INTO users (login, password, salt, ip) VALUES (?, ?, ?, ?)";
                    $values = array($_POST['login'], $cryptedPassword, $salt, $_SERVER['REMOTE_ADDR']);
                    $status = $bddconnection->preparedQuery($sql, $values);

                    if ($status) {
                        $notification = "Vous êtes maintenant inscrit";
                    } else {
                        $notification = "Erreur lors de l'inscription, veuillez réessayer";
                    }
                } else {
                    $notification = "Les deux mots de passe doivent être les mêmes";
                }
            }
        }
    } else if (isset($_POST['login']) || isset($_POST['password']) || isset($_POST['rePassword'])) {
        $notification = "Veuillez remplir tout les champs pour pouvoir vous inscrire !";
    }
}

include_once 'inc/header.inc.php';
?>

<script src="inputs.js"></script>

<div id="inscription">
    <form action="" method="post">
        <input type="text" name="login" value="login" class="round" id="loginInput" />
        <img src="/Duckalendar/images/ajax-loader.gif" alt="loading" id="loadingImg" /><br />
        <p id="loginNotif"></p>
        <input type="password" name="password" value="password" class="round" /><br />
        <input type="password" name="rePassword" value ="password" class="round" /><br />
        <input type="submit" value="Inscription" id="submitButton" />
    </form>
</div>

<script type="text/javascript">
    //Gestion de l'AJAX
    function disableSubmit() {
        $("#submitButton").attr("disabled", "disabled");
        $("#loginInput").attr("class", "round redBorder");
    }
    
    function enableSublmit() {
        $("#submitButton").removeAttr("disabled");
        $("#loginInput").attr("class", "round");
    }
    
    $(document).ready(
            function() {
                $("#loginInput").keyup(function() {
                    var val = $(this).val().trim();
                    if (val == "") {
                        $("#loginNotif").text("Veuillez entrer un login");
                        disableSubmit();
                    } else {
                        $("#loadingImg").css({visibility: "visible"});
                        $.ajax({
                            url: 'inscriptionCheckLogin.ajax.php',
                            type: 'POST',
                            data: {
                                login: val
                            },
                            error: function(j, textStatus, errorThrown) {
                                var notif = $("#login");
                                notif.text("Erreur lors de la requête asynchrone");
                            },
                            success: function(data) {
                                $("#loadingImg").css({visibility: "hidden"});
                                var loginNotif = $("#loginNotif");
                                if (data == "exists") {
                                    loginNotif.text("Login déjà utilisé");
                                    disableSubmit();
                                } else if (data == "doesntExist") {
                                    loginNotif.text("Login disponible");
                                    enableSublmit();
                                } else if (data == "bddError") {
                                    loginNotif.text("Erreur lors de la connexion à la base de données");
                                } else {
                                    loginNotif.text("Erreur côté serveur lors de la requête asynchrone");
                                }
                            }
                        });
                    }
                });
            });

</script>

<?php include_once 'inc/footer.inc.php'; ?>