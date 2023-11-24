<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('../../header/header_interne.html');
require("token.php");


session_start();
if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {

    //récupération de tous les logements
    $requete = $conn->prepare(" SELECT * FROM logement ");
    $requete->execute([]);
    $logements = $requete->fetchAll(PDO::FETCH_ASSOC);



    if (isset($_POST['supprimer'])) {
        $supprimer_logement = $conn->prepare('DELETE FROM logement WHERE id = :id');
        $supprimer_logement->execute(array("id" => $_POST['id_logement']));
        echo "Le logement a été supprimé avec succès";
    }elseif(isset($_POST['modifier'])){
        header('Location: modifier_logement.php?id_logement='.$_POST['id_logement']);
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
    <link rel="stylesheet" href="../../header/header_interne.css">
    <link rel="stylesheet" href="../CSS/appartements.css">
    <title>Appartements</title>
</head>
    <br>
    <br>
<body>
    <div class= "">
    <br>
    <button onclick="window.location.href ='ajouter_logement.php'" class="button" >Ajouter</button>
    <br>
    <br>
    <br>
        <div class="affichage_logements">
            <?php if(count($logements) != 0): ?>    
                  
                <?php foreach ($logements as $logement) : 
                    $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");

                    $requete->execute([
                        ":id_logement" => $logement['id'],
                        
                    ]);
                    
                    $photo_logement = $requete->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <div class="logement">
                        <div class="infos_logement">
                        <div class="photogrille"> 
                        <img src=../../images/<?=$photo_logement['photo'] ?>  alt="">                   
                    </div>   
                        <p class="adresse">Ville de <?=$logement['ville']?></p>
                        <p class="prix"><?=$logement['prix'] ?>€</p>
                        </div>
                        <br>
                        <div class="modifier_supprimer">
                            <br>
                            <form action="" method="post">
                                <input class="submit" type="submit" name="modifier" value="Modifier">
                                <input class="submit" type="submit" name="supprimer" value="Supprimer">
                                <input type="hidden" name="id_logement" value="<?= $logement['id']?>">
                            </form>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                <?php endforeach; ?>        
            <?php endif; ?>
        </div>
    </div>
</body>
    <div class="footer">
         <div class="texte">
            <p>
                Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc
            </p>
        </div>
         <hr class="hr1">
    </div>
</html>