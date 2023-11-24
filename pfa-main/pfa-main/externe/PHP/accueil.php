<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require('config.php');
require('../../header/header.php');
require("token.php");



if (isset($_COOKIE["validate"])) {

    // Vérification de la soumission du formulaire
    if (isset($_POST['id_logement'])) {
        $id_logement = $_POST['id_logement'];

        // Vérification si le logement existe déjà dans les favoris de l'utilisateur
        $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
        $requete->execute(array('id_logement' => $id_logement, 'id_user' => $_SESSION['id_user']));
        $existeDeja = $requete->fetch();

        if ($existeDeja) {   // Supprimer le logement des favoris
                $suppression = $conn->prepare('DELETE  FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                $suppression->execute(array('id_logement' => $id_logement, 'id_user' => $_SESSION['id_user']));
        } else {
            // Insertion du logement dans la table des favoris
            $insertion = $conn->prepare('INSERT INTO favoris (id_logement, id_user) VALUES (:id_logement, :id_user)');
            $insertion->execute([':id_logement' => $id_logement, ':id_user' => $_SESSION['id_user']]);
        }
    }



    $requete = $conn->prepare(" SELECT * FROM logement ");

    $requete->execute([]);

    $logements = $requete->fetchAll(PDO::FETCH_ASSOC);
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/accueil.css">
    <link rel="stylesheet" href="../../header/header.css">

    <script src="../../header/header.js" defer></script>
    <title>Accueil</title>
</head>

<body>
    <div class="special">
        <p class="titre">RÉSERVEZ POUR UNE OCCASION SPÉCIALE...</p> 
        <hr class="hr3">
        <div class="icon">
            <a href="birthday.php" class="birthday"><img width="25" height="25" src="../../images/birthday-cake-2.png" alt=""/></a>
            <p class="anniversaire"> SPÉCIAL ANNIVERSAIRE</p>
            <a href="love.php" class="love"><img width="25" height="25" src="../../images/like.png" alt=""/></a>
            <p class="love-appart"> NOS LOVE APPART</p>
            <a href="fiancailles.php" class="ring"><img width="25" height="25" src="../../images/wedding-ring-2.png" alt=""/></a>
            <p class="fiancailles"> SPÉCIAL FIANÇAILLES</p>
            <a href="business.php" class="valise"><img width="25" height="25" src="../../images/suitcase.png" alt=""/></a>
            <p class="business"> SPÉCIAL BUSINESS</p>
        </div>
    </div>


    <div class="affichage_logements">
        
        <?php if(Count($logements) != 0): ?>            
            <?php foreach ($logements as $logement) : ?>
            <a href="produit.php?id_logement=<?=$logement['id']?>">
            <?php
                $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");

                $requete->execute([
                    ":id_logement" => $logement['id'],
                ]);
                
                $photo_logement = $requete->fetch(PDO::FETCH_ASSOC);
                ?>

                    <div class="logement">

                        <img src="../../images/<?=$photo_logement['photo']?>" alt="">

                        <form action="" class="form" method="post">
                            
                            <input type="hidden" name="id_logement" value="<?= $logement['id'] ?>">
                            <?php
                            //verifie si le logement est dans les favoris
                            $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                            $requete->execute(array(':id_logement' => $logement['id'], ':id_user' => $_SESSION['id_user']));
                            $existeDeja = $requete->fetch();

                            if ($existeDeja) { ?>
                                <input type="image" src="../../images/heart.png" name="fav"/>

                            <?php } else {   ?> 
                                <input type="image" src="../../images/love.png" name="fav"/>              
                           <?php }
                            ?>
                        </form>

                        <div class="infos_logement">
                            <p class="adresse"><?=$logement['name'] ?>, <?=$logement['ville'] ?></p>
                            <p class="prix"><?=$logement['prix'] ?>€ par nuit</p>
                        </div>
                    </div>
                    </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="texte">
            <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
    </div>
</body>


</html>