<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('./Header/header.php');
require("token.php");



if (isset($_COOKIE["validate"])) {

    // récupération des données du logement
    $requete = $conn->prepare(" SELECT * FROM logement WHERE id = :id_logement LIMIT 1");
    $requete->execute([":id_logement" => $_GET['id_logement']]);
    $infos_logement = $requete->fetch(PDO::FETCH_ASSOC);


    // récupération des photos du logement
    $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement");
    $requete->execute([":id_logement" => $_GET['id_logement']]);
    $photos = $requete->fetchAll(PDO::FETCH_ASSOC);

    // récupération du nombre de témoignage du logement
    $requete = $conn->prepare(" SELECT * FROM temoignages WHERE id_logement = :id_logement");
    $requete->execute([":id_logement" => $_GET['id_logement']]);
    $temoignages = $requete->fetchAll(PDO::FETCH_ASSOC);
    $nb_temoignages = count($temoignages);


    // récupération des équipement du logement
    $requete = $conn->prepare(" SELECT * FROM equipements WHERE id_logement = :id_logement");
    $requete->execute([":id_logement" => $_GET['id_logement']]);
    $equipements = $requete->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['reserver'])){
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $nombre_place = $_POST['nb_personnes'];
        $id_logement = $_GET['id_logement'];

        header('Location: recapitulatif.php?id_logement='.$id_logement.'&date_debut='.$date_debut.'&date_fin='.$date_fin.'&nombre_place='.$nombre_place);

    }
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
    <!-- <link rel="stylesheet" href="../PHP/accueil.css"> -->
    <link rel="stylesheet" href="../../header/header.css">
    <link rel="stylesheet" href="../CSS/produit.css">


    <title>Produit</title>
</head>
<script>
        function verifierChampsDate() {
            var dateDebut = document.getElementById("date_debut").value;
            var dateFin = document.getElementById("date_fin").value;

            if (dateDebut !== "" && dateFin !== "") {
                // Les deux champs de date sont remplis
                calculerNombreJours();
            }
        }

        function calculerNombreJours() {
            var dateDebut = new Date(document.getElementById("date_debut").value);
            var dateFin = new Date(document.getElementById("date_fin").value);
            var prix = document.getElementById("prix").value;
            var difference = dateFin - dateDebut;
            var nombreJours = Math.ceil(difference / (1000 * 60 * 60 * 24));
            var prix_total = Math.ceil(nombreJours * prix);
            var taxes = Math.ceil(prix_total * 0.2 );
            var prix_ttc = Math.ceil(prix_total + taxes );

            if (nombreJours >= 0) {
                document.getElementById("resultat").innerHTML = "Nombre de soirées de réservation : " + nombreJours;
                document.getElementById("affichage_prix_total").innerHTML = prix  + " € " + " x " + nombreJours + " nuits " + " = ";
                document.getElementById("prix_total").innerHTML = prix_total + " € ";
                document.getElementById("taxes").innerHTML = taxes + " € ";
                document.getElementById("prix_ttc").innerHTML = prix_ttc + " € ";
            } else {
                document.getElementById("resultat").innerHTML = "La date de fin doit être postérieure à la date de début.";
            }
        }
    </script>


    <br>
    <br>
  <body>
       <br>
       <div class="nom">
       <p>|<?= $infos_logement['name']; ?>
       <a href = "temoignages.php?id_logement="<?= $_GET['id_logement'] ?>>|<?= $nb_temoignages; ?> temoignages</a>
       |<?= $infos_logement['ville']; ?></p>
        </div>
        <br>

                <div class="affichage_photos">
                <?php if(Count($photos) != 0): ?>
                     <img class="affichage_photos-col-2 affichage_photos-row-2 " src="../images/<?=$photos[0]['photo'] ?>"  alt= >
                     <img src='../../images/<?=$photos[1]['photo'] ?>' alt="">
                     <img src='../../images/<?=$photos[2]['photo'] ?>' alt=""> 
                     <img src='../../images/<?=$photos[3]['photo']?>' alt="">
                     <img src='../../images/<?=$photos[4]['photo'] ?>' alt="">          
                     <?php foreach ($photos as $photo) : 
                        $photo['photo'];
                        endforeach;
                    endif;
                    ?>
                  </div>
                  <br>
                  <br>
            <div class= "oui">
                <div class="a_propos">
                    <p class="description"> A propos de ce logement:</p>
                     <br>
                    <div class="infos_logement">
                    <p class="nb_chambre">Nombre de chambres: <?=$infos_logement['nombre_chambre'] ?></p>
                    <p class="nb_lit">Nombre de lits: <?=$infos_logement['nombre_lit']; ?></p>
                    <p class="nb_sdb">Nombre de salles de bain: <?=$infos_logement['nombre_sdb']; ?></p>
                     </div>
                  <pre class="about_description"><?=$infos_logement['description']; ?></pre></p>
                </div>     
          </div>
          <br>
          <div class="equipements">
                    <p class="description"> Ce que propose ce logement: 
                    <br>
                    <br> 
                    <?php if(Count($equipements) != 0):
                    foreach ($equipements as $equipement) : 
                        ?>
                        <p class="equipement"><?=$equipement['equipement'] ?></p>
                  <?php
                    endforeach;
                endif;
                    ?>
                </div>
            </div>
          <br>
          <br>
            <div class="reservation">
               <div class="reservation2">
                <div class="prixsejour">
                <p><?=$infos_logement['prix'] ?>€ par nuit</p>
                <a href="temoignages.php?id_logement=<?=$_GET['id_logement']?>" class=""></a>
                </div>
                <br>
                <form action="" method="post">
                    <input class="date" type="date" id="date_debut" name="date_debut" required onchange="verifierChampsDate()">
                    <input class="date" type="date" id="date_fin" name="date_fin" required onchange="verifierChampsDate()">
                    <br>
                    <input class= "number" type="number" placeholder="Nombre de personnes" name="nb_personnes" value= "" required>
                    <input type="hidden" name="prix" id="prix" value="<?=$infos_logement['prix'] ?>">
                    <br>
                    <br>
                    <p id="resultat"></p>
                    <br>
                    <div class="prix_total">
                        <p id="affichage_prix_total"></p>
                        <p id="prix_total"></p>
                    </div>
                    <br>
                    <div class="taxes">
                        <p id="affichage_taxes">Taxes:</p>
                        <p id="taxes"></p>
                    </div>
                    <br>
                    <div class="prix_ttc">
                        <p id="affichage_prix_ttc">Total: </p>
                        <p id="prix_ttc"></p>
                    </div>
                    <br>
                    <input class= "submit" type="submit" name="reserver" value="Réserver">
                </form>
            </div>
            </div>
</body>
<br>
<br>
<br>
<br>
<br>
<br>
<footer>
    <div class="footer2">
         <div class="texte">
            <p>
                Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc
            </p>
        </div>
         <hr class="hr1">
    </div>
</footer>
</html>