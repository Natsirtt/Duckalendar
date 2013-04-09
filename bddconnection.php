<?php

$bdd_connected = true;
try {
    $dsn = "mysql:host=localhost;dbname=Duckalendar";
    $bdd_connection = new PDO($dsn, "root", "motdepasse");
} catch (PDOException $e) {
//    $notification = "Erreur de connexion à la BDD : " . $e−>getMessage();
    $notification = "Erreur de connexion à la BDD";
    $bdd_connected = false;
}
?>
