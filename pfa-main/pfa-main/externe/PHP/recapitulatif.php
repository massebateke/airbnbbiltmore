<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('fonctions.php');
//require('../Header/header.php');

session_start();

// récupération des données du logement
$requete = $conn->prepare(" SELECT * FROM logement WHERE id = :id_logement LIMIT 1");
$requete->execute([":id_logement" => $_GET['id_logement']]);
$infos_logement = $requete->fetch(PDO::FETCH_ASSOC);

// récupération des données de l'utilisateur
$requete = $conn->prepare(" SELECT * FROM profil WHERE id_user = :id_user");
$requete->execute([":id_user" => $_SESSION['id_user']]);
$infos_utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

// récupération des photos du logement
$requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");
$requete->execute([":id_logement" => $_GET['id_logement']]);
$photo = $requete->fetch(PDO::FETCH_ASSOC);

// récupération des équipement du logement
$requete = $conn->prepare(" SELECT * FROM equipements WHERE id_logement = :id_logement");
$requete->execute([":id_logement" => $_GET['id_logement']]);
$equipements = $requete->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['confirmer'])){
    $length = rand(6, 10); // longueur aléatoire entre 6 et 10 caractères
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // caractères autorisés
    $public_code = '';

    // générer la chaîne de caractères aléatoire
    for ($i = 0; $i < $length; $i++) {
        $random_index = rand(0, strlen($characters) - 1);
        $public_code .= $characters[$random_index];
    }

    $requete = $conn->prepare("INSERT INTO reservation (id_logement, id_user, date_creation, date_debut, date_fin, nombre_place, code_public) VALUES(:id_logement, :id_user, NOW(), :date_debut, :date_fin, :nombre_place, :code_public)");
    $requete->execute([
        ":id_logement" => $_GET['id_logement'],
        ":id_user" => $_SESSION['id_user'],
        ":date_debut" => $_GET['date_debut'],
        ":date_fin" => $_GET['date_fin'],
        ":nombre_place" => $_GET['nombre_place'],
        ":code_public" => $public_code
    ]); 

    header("Location: accueil.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="recapitulatif.css">
    <link rel="stylesheet" href="../PHP/Header/header.css">
    <title>Récapitulatif</title>
    <script>
            var prix = document.getElementById("prix").value;
            var difference = dateFin - dateDebut;
            var nombreJours = Math.ceil(difference / (1000 * 60 * 60 * 24));
            var prix_total = Math.ceil(nombreJours * prix);
            var taxes = Math.ceil(prix_total * 0.2 );
            var prix_ttc = Math.ceil(prix_total + taxes );

            if (nombreJours >= 0) {;
                document.getElementById("prix_ttc").innerHTML = nombreJours + " nuits: " + nombreJours "x" prix + "+" taxes "=" prix_ttc;
            } else {
                document.getElementById("prix_ttc").innerHTML = "Problème de chargement";
            }
    </script>
</head>
<body>
    <div class="partie_gauche">
        <div class="rectangle1">
        <h1>Récapitulatif de votre réservation:</h1>
        </div>  
        <br>
        <br>
        <br>
        <br>
        <br>
        <h2>Données voyageur:</h2>
        <br>
        <div class="infos_voyageur">
            <p>Prénom : <?= $infos_utilisateur['first_name'] ?></p>
            <p>Nom de famille : <?= $infos_utilisateur['name'] ?></p>
            <p>Email : <?= $infos_utilisateur['email'] ?></p>
            <p id="prix_ttc">Prix Total : <?= $infos_utilisateur['adresse'] ?></p>
        </div>
        <br>
        <br>
        <hr class="hr1">
        <h2>Informations de voyage:</h2>
        <br>
        <div class="infos_voyage">
            <p>Dates souhaitées : <?= formaterDate(new DateTime($_GET['date_debut'])) ?> -<?= formaterDate(new DateTime($_GET['date_fin'])) ?></p>
            <p>Localisation du bien : <?= $infos_logement['ville'] ?></p>
            <p>Prix par nuit : <?= $infos_logement['prix'] ?>€</p>
        </div>           
        <hr class="hr2">
        <h3 id="pay">Veuillez choisir votre méthode de paiement:</h3>
               <div class="rec1">
                    <a href="#">Virement bancaire</a>
                </div>
                <div class="rec2">
                    <a href="#">Paypal</a>
                </div>
                <div class="rec3  ">
                    <a href="#">Carte de credit</a>
                </div>   
    </div>

    <div class="partie_droite">
        <div class="photo"><img src="../images/<?= $photo['photo'] ?>"></div>

        <div class="equipements">
                    <p class="description"> Ce que propose ce logement: 
                    <br>
                    <br> 
                    <?php if($equipements):
                    foreach ($equipements as $equipement) : 
                        ?>
                        <p class="equipement"><?=$equipement['equipement'] ?></p>
                  <?php
                    endforeach;
                endif;
                    ?>
                </div>
            </div>

        <form action="" method="post">
            <input type="submit" id="confirmer" name="confirmer" value="Confirmer">
        </form>

    </div>
</body>
</html>
