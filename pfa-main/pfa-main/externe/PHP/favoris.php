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

    // Récupère tous les favoris de l'utilisateur
    $requete = $conn->prepare(" SELECT * FROM favoris WHERE id_user = :id_user");

    $requete->execute([':id_user' => $_SESSION['id_user']]);

    $logementsfav = $requete->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="../CSS/favoris.css">
    <link rel="stylesheet" href="../../header/header.css">
    <script src="../../header/header.js" defer></script>
    <title>Favoris</title>
</head>

<body>

    <p class="titre">MES APPARTEMENTS FAVORIS</p>

    <div class="affichage_logements">
        <?php if(Count($logementsfav) != 0): ?>            
            <?php foreach ($logementsfav as $logementfav) : 

                //Récupération de la première photo du logement
                $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");

                $requete->execute([
                    ":id_logement" => $logementfav['id_logement'],
                ]);
                
                $photo_logement = $requete->fetch(PDO::FETCH_ASSOC);

                //Récupération des infos du logement
                    $requete = $conn->prepare("SELECT * FROM logement WHERE id = :id_logement");

                    $requete->execute([
                        ":id_logement" => $logementfav['id_logement'],
                    ]);
                    
                    $infos_logement = $requete->fetch(PDO::FETCH_ASSOC);
    ?>



                    <div class="logement">

                        

                        <img src="../../images/<?=$photo_logement['photo']?>" alt="">

                        <form action="" class="form" method="post">
                            
                            <input type="hidden" name="id_logement" value="<?= $logementfav['id_logement'] ?>">
                            <?php
                            //verifie si le logement est dans les favoris
                            $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                            $requete->execute(array(':id_logement' => $logementfav['id_logement'], ':id_user' => $_SESSION['id_user']));
                            $existeDeja = $requete->fetch();

                            if ($existeDeja) { ?>
                                <input type="image" src="../../images/heart.png" name="fav"/>

                            <?php } else {   ?>
                                <input type="image" src="../../images/love.png" name="fav"/>                 
                            <?php }
                            ?>
                        </form>

                        <div class="infos_logement">
                            <p class="adresse"><?=$infos_logement['ville'] ?></p>
                            <p class="prix"><?=$infos_logement['prix'] ?>€ par nuit</p>
                        </div>
                    </div>
                
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
