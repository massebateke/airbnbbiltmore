<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('../../header/header.php');
require("token.php");

if (isset($_COOKIE["validate"])) {


    // récupération des données de la réservation
    $requete = $conn->prepare(" SELECT * FROM reservation WHERE id = :id_reservation");
    $requete->execute([":id_reservation" => $_GET['id_reservation']]);
    $infos_reservation = $requete->fetch(PDO::FETCH_ASSOC);


    // récupération des photos du logement
    $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");
    $requete->execute([":id_logement" => $infos_reservation['id_logement']]);
    $photo = $requete->fetch(PDO::FETCH_ASSOC);
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../header/header.css">
    <link rel="stylesheet" href="../CSS/recu.css">
    <title>Reçu</title>
</head>

    <br>
    <br>
    <br>
<body>
<div class= "partie_gauche">
        <div class="remerciement">
            <img class="remerciementimg" src="../../images/<?=$photo['photo']?>" alt="">
            <div id="carre"></div>
            <p class="remerciementtxt">Merci d'avoir fait confiance à Biltmore</p>
        </div>
        <br>
        <br>
        <div class="dates" >
            <div class="arrivee">
                <p>Arrivée:<br><?= formaterDate(new DateTime($infos_reservation['date_debut'])) ?></p>
            </div>
            <br>
            <br>
            <div class="depart">
                <p>Départ:<br><?= formaterDate(new DateTime($infos_reservation['date_fin'])) ?></p>
            </div>
        </div>
        <br>
        <br>
        <div class="message_adresse">
            <div class="message">
              <!--   <img class="imgicon" src="../PHP/icon/" style="color:black;" alt=""> -->
                <a href=""><p>Envoyer un message à votre hôte</p></a>
                
            </div>
            <br>
            <div class="adresse">
                <a href="" class="adressefind"><p>Trouver l'adresse de votre logement</p></a>
            </div>
        </div>
        <div class="infos_reservations">
            <div class="nombre_place">
                <p>Nombre de voyageurs : <?= $infos_reservation['nombre_place'] ?></p>
                <p>Code de confirmation : <?= $infos_reservation['code_public'] ?></p>
            </div>
        </div>

        <div class="liens_infos">
            <div class="condition_annulation">
                <a href=""><p>Conditions d'annulation</p></a>
                <a href=""><p>Règlements et instructions</p></a>
            </div>
        </div>
    </div>
    <div class="partie_droite">
        <img class="itineraire" src="../PHP/icon/image.jpg" alt="" >
        <div class="map">
            
        </div>
  </div>  
  
</body>
<br>
<br>
<br>
<div class="footer">
         <div class="texte">
            <p>
                Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmorse, Inc
            </p>
        </div>
         <hr class="hr1">
    </div>
</html>