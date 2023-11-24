<?php 

require('./config.php');
require('../../externe/PHP/fonctions.php');

session_start();

$searchTerm = filter_input(INPUT_GET,"searchTerm");// Terme de recherche
$searchTerm = trim((string) $searchTerm);

$results = searchPeople($searchTerm);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="./JS/header.js" defer></script>
    <title></title>
</head>
<!--header-->
<header class="nav">
        
        <div class="logo">
            <a href="accueil.php"><img  width="60" height="60" src="../../images/BFO_logo_rgb.original.png" alt=""></a>
        </div>
       
        <form method="GET" action="" class="search-bar">
            <input type="text" name="searchTerm" placeholder="Recherche" id="bar_recherche">
            <input type="image" width="25" height="25" src="../../images/search-2.png" id="bouton-recherche">
        </form>
    
        <a href="filtres.php" class="filter"><img width="22" height="22" src="../../images/les-niveaux.png" alt=""></a>
    
        <a href="verify_logout.php" class="connexion"><img width="25" height="25" src="../../images/utilisateur.png"/></a>
    
        <a href="#" class="burger" id="Burger">&#9776;</a>
        
        <div class="burger_cacher" id="Burger_cacher">
            <a id="Burger_fermer" href="#" class="close">x</a>
            <ul>
                <li><a href="#" class="option">Mon compte</a></li>
                <br>
                <li><a href="favoris.php" class="option">Mes favoris</a></li>
                <br>
                <li><a href="historique_reservations" class="option">Mes réservations</a></li>
                <br>
                <li ><a href="filtres.php" class="option">Filtre</a></li>
                <br>
                <li ><a href="#" class="option">Aide</a></li>
            </ul>
        </div>
        <hr>
        
    </header>
     
     <body> 
     <?php
    if(isset($_GET['searchTerm'])):
        foreach ($results as $result):

            $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");

            $requete->execute([
                ":id_logement" => $result['id'],
            ]);

            $photo_logement = $requete->fetch(PDO::FETCH_ASSOC);

            ?>
            <div class="logement">
                <a href='produit.php?id_logement=<?= $result['id'] ?>'>
                <img src="../images/<?=$photo_logement['photo']?>" alt="">

                <form action="" class="form" method="post">
                    
                    <input type="hidden" name="id_logement" value="<?= $result['id'] ?>">
                    <?php
                    //verifie si le logement est dans les favoris
                    $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                    $requete->execute(array(':id_logement' => $result['id'], ':id_user' => $_SESSION['id_user']));
                    $existeDeja = $requete->fetch();

                    if ($existeDeja) { ?>
                        <input type="image" src="../../images/heart.png" name="fav"/>

                    <?php } else {   ?> 
                        <input type="image" src="../../images/love.png" name="fav"/>              
                <?php }
                    ?>
                </form>
                <div class="infos_logement">
                            <p class="adresse"><?=$result['name'] ?>, <?=$result['ville'] ?></p>
                            <p class="prix"><?=$result['prix'] ?>€ par nuit</p>
                        </div>
            </div>
    <?php
endforeach;
endif; ?>
</body>
</html>
