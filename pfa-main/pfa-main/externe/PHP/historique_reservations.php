<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('../../header/header.php');
require("token.php");


if (isset($_COOKIE["validate"])) {

    //Récupération des réservations
    $requete = $conn->prepare(" SELECT * FROM logement JOIN reservation ON logement.id = reservation.id_logement ");

    $requete->execute([]);

    $all_reservations = $requete->fetchAll(PDO::FETCH_ASSOC);



    if (isset($_POST['supprimer'])) {
        $supprimer_logement = $conn->prepare('DELETE FROM reservation WHERE id = :id');
        $supprimer_logement->execute(array("id" => $_POST['id_logement']));
        echo "a réservation a été supprimée avec succès";
        header('Location: historique_reservations.php');
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
    <link rel="stylesheet" href="../../header/header.css">
    <link rel="stylesheet" href="../CSS/historique_reservations.css">

    <title>Historique de réservations</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

     <div class="footer">
      <div class="texte">
           <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
      </div>
      <hr class="hr1">
     </div>
     <div class="container">
      <div class="left-container">
          <a href="favoris.php">Mes appartements favoris</a><br><br>
          <a id="histo" href="historique_reservations.php">Historique des réservations</a>
      </div>
      <div class="right-container">
      <div class= "">

        <div class="affichage_reservations">
            <div class="categories_reservation">
                <a id="passe" class="categories">Réservations passées</a>
                <a id="present" class="categories">Réservations présentes</a>
                <a id="futur" class="categories">Réservations futures</a>
            </div>

            <div class="contenu_reservations">
            <?php if(Count($all_reservations) != 0): ?>            
                <?php foreach ($all_reservations as $reservation) : 
                     $requete = $conn->prepare(" SELECT * FROM photos WHERE id_logement = :id_logement ORDER BY id DESC LIMIT 1");

                     $requete->execute([
                         ":id_logement" => $reservation['id_logement'],
                     ]);
                     
                     $photo_logement = $requete->fetch(PDO::FETCH_ASSOC);
 
                    if(strtotime($reservation['date_fin']) < time())
                    {
                        ?>
                        <div id="contenu_passe" class="contenu hidden">
                            <div class="logement">
                            <a href="recu.php?id_reservation=<?= $reservation['id']?>">
                                <img src="../../img/<?=$photo_logement['photo']?>" alt="">

                                <form action="" class="form" method="post">
                                    
                                    <input type="hidden" name="id_logement" value="<?= $logement['id'] ?>">
                                    <?php
                                    //verifie si le logement est dans les favoris
                                    $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                                    $requete->execute(array(':id_logement' => $reservation['id'], ':id_user' => $_SESSION['id_user']));
                                    $existeDeja = $requete->fetch();

                                    if ($existeDeja) { ?>
                                        <input type="image" src="../../images/heart.png" name="fav"/>

                                    <?php } else {   ?> 
                                        <input type="image" src="../../images/love.png" name="fav"/>              
                                <?php }
                                    ?>
                                </form>
                            </div>


                                <div class="infos_logement">
                                    <p class="nom"><?=$reservation['name'] ?></p>
                                    <p class="date"> Du
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_debut']));?>
                                    au 
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_fin']));?>
                                    </p>
                                    <p class="nombre_place"><?=$reservation['nombre_place']?></p>

                                </div>
                                <div class="modifier_supprimer">
                                    <form action="" method="post">
                                        <input class="submit" type="submit" name="supprimer" value="Supprimer" />
                                        <input type="hidden" name="id" value="<?= $reservation['id']?>">
                                    </form>
                                </div>
                            </div>
                            </a>
                        </div>

                    <?php
                    }
                    elseif(strtotime($reservation['date_fin']) > time() && strtotime($reservation['date_debut']) < time()){
                        ?>
                        <div id="contenu_present" class="contenu hidden" >
                        <div class="logement">
                            <a href="recu.php?id_reservation=<?= $reservation['id']?>">
                                <img src="../../img/<?=$photo_logement['photo']?>" alt="">

                                <form action="" class="form" method="post">
                                    
                                    <input type="hidden" name="id_logement" value="<?= $logement['id'] ?>">
                                    <?php
                                    //verifie si le logement est dans les favoris
                                    $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                                    $requete->execute(array(':id_logement' => $reservation['id'], ':id_user' => $_SESSION['id_user']));
                                    $existeDeja = $requete->fetch();

                                    if ($existeDeja) { ?>
                                        <input type="image" src="../../images/heart.png" name="fav"/>

                                    <?php } else {   ?> 
                                        <input type="image" src="../../images/love.png" name="fav"/>              
                                <?php }
                                    ?>
                                </form>
                            </div>
                                <div class="infos_logement">
                                    <p class="nom"><?=$reservation['name'] ?></p>
                                    <p class="date"> Du
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_debut']));?>
                                    au 
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_fin']));?>
                                    </p>
                                    <p class="nombre_place"><?=$reservation['nombre_place']?></p>
                                </div>
                                <div class="modifier_supprimer">
                                    <form action="" method="post">
                                        <input class="submit" type="submit" name="supprimer" value="Supprimer" />
                                        <input type="hidden" name="id" value="<?= $reservation['id']?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                        </a>

                    <?php    
                    }else{
                        ?>
                        <div id="contenu_futur" class="contenu hidden" >
                        <div class="logement">
                            <a href="recu.php?id_reservation=<?= $reservation['id']?>">
                                <img src="../../img/<?=$photo_logement['photo']?>" alt="">

                                <form action="" class="form" method="post">
                                    
                                    <input type="hidden" name="id_logement" value="<?= $logement['id'] ?>">
                                    <?php
                                    //verifie si le logement est dans les favoris
                                    $requete = $conn->prepare('SELECT id FROM favoris WHERE id_logement = :id_logement AND id_user = :id_user');
                                    $requete->execute(array(':id_logement' => $reservation['id'], ':id_user' => $_SESSION['id_user']));
                                    $existeDeja = $requete->fetch();

                                    if ($existeDeja) { ?>
                                        <input type="image" src="../../images/heart.png" name="fav"/>

                                    <?php } else {   ?> 
                                        <input type="image" src="../../images/love.png" name="fav"/>              
                                <?php }
                                    ?>
                                </form>
                            </div>
                                <div class="infos_logement">
                                    <p class="nom"><?=$reservation['name'] ?></p>
                                    <p class="date"> Du
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_debut']));?>
                                    au 
                                        <?= $date_debut = formaterDate(new DateTime($reservation['date_fin']));?>
                                    </p>
                                    <p class="nombre_place"><?=$reservation['nombre_place']?></p>
                                </div>
                                <div class="modifier_supprimer">
                                    <form action="" method="post">
                                        <button onclick="window.location.href ='modifier_reservation.php?id_reservation=<?=$reservation['id']?>'">Modifier</button>
                                        <input class="submit" type="submit" name="supprimer" value="Supprimer" />
                                        <input type="hidden" name="id" value="<?= $reservation['id']?>">
                                    </form>
                                </div>
                            </div>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                <?php endforeach; ?>        
            <?php endif; ?>
    
            </div>
        </div>
    </div>
      </div>
    </div>
    
    <script>
        var passe = document.getElementById('passe');
        var present = document.getElementById('present');
        var futur = document.getElementById('futur');
        var all = [passe, present, futur]

        passe.addEventListener('click', function(){
            console.log('click')
            switch_tab(0);
         });
        present.onclick = function(){ switch_tab(1); };
        futur.onclick = function(){ switch_tab(2); };


        var contenu_passe = document.getElementById('contenu_passe');
        var contenu_present = document.getElementById('contenu_present');
        var contenu_futur = document.getElementById('contenu_futur');
        var contents = [contenu_passe, contenu_present, contenu_futur]
        
        function switch_tab(tab)
        {
            for (var i = 0; i < all.length; i++) {


                if (!contents[i].classList.contains('hidden')) {
                    contents[i].classList.add('hidden')
                    all[i].classList.remove('selected')
                }
            }

            all[tab].classList.add('selected')
            contents[tab].classList.remove('hidden')

           
        }
        // switch_tab(1);
    </script>
</body>
</html>
