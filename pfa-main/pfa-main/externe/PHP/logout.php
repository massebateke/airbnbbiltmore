
<?php
require('config.php');
session_start();

$login = $_SESSION["username"];
$requete = $conn->prepare("UPDATE profil SET token = :token WHERE username = :login");
$requete->execute([":token" => "", ":login" => $login]);

//On supprime tout le contenu de la session
session_destroy();

//On redirige la personne vers le login
header('Location: login.php');

?>