<?php

require_once("config.php");

$token = $_SESSION["token"];
$username = $_SESSION["username"];

$stmt = $conn->prepare("SELECT token FROM profil WHERE username = :username");
$stmt->execute(['username' => $username]);
$resultat = $stmt->fetchAll();
$bdd_token = $resultat['token'];

if ($token == $bdd_token) {
  setcookie("validate", true);
} else {
  setcookie("validate", false);
}

?>