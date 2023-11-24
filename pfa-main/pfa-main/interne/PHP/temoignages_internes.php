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

    //Récupération de tous les témoignages en attente
    $requete = $conn->prepare(" SELECT * FROM temoignages_en_attente ORDER BY date_publication DESC");

    $requete->execute([]);

    $temoignages_en_attente = $requete->fetchAll(PDO::FETCH_ASSOC);


    //Récupération de tous les témoignages validés
    $requete = $conn->prepare(" SELECT * FROM temoignages ORDER BY date_publication DESC");

    $requete->execute([]);

    $temoignages_valide = $requete->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['valider_attente'])){

        //Récupération du témoignage dans la liste en attente
        $requete = $conn->prepare(" SELECT * FROM temoignages_en_attente WHERE id = :id");

        $requete->execute([":id" => $_POST['id_temoignage_en_attente']]);

        $temoignage = $requete->fetch(PDO::FETCH_ASSOC);
        var_dump($temoignage);

        //Ajout des informations dans la liste témoignages
        $requete = $conn->prepare("INSERT INTO temoignages (id_logement, id_user, date_publication, contenu) VALUES (:id_logement, :id_user, :date_publication, :contenu)");

        $requete->execute([
            ":id_logement" => $temoignage['id_logement'],
            ":id_user" => $temoignage['id_user'],
            ":date_publication" => $temoignage['date_publication'],
            ":contenu" => $temoignage['contenu']]);

        //Suppression dans la liste témoignages en attentes
        $requete = $conn->prepare("DELETE FROM temoignages_en_attente WHERE id = :id");

        $requete->execute([
            ":id" => $_POST['id_temoignage_en_attente']
        ]);

    }elseif(isset($_POST['supprimer_attente'])){
        //Suppression dans la liste témoignages en attentes
        $requete = $conn->prepare("DELETE FROM temoignages_en_attente WHERE id = :id");

        $requete->execute([
            ":id" => $_POST['id_temoignage_en_attente']
        ]);

    }elseif(isset($_POST['supprimer_valide'])){
        //Suppression dans la liste témoignages 
        $requete = $conn->prepare("DELETE FROM temoignages WHERE id = :id");

        $requete->execute([
            ":id" => $_POST['id_temoignage_valide']
        ]);
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
    <link rel="stylesheet" href="../../externe/CSS/temoignages.css">
    <title>Témoignages en attente</title>
    <style>
        .hidden {
            display: none;
        }
        body {
            padding-top: 20px;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class= "">


        <div class="affichage_temoignages">
        <div class="categories_temoignages">
                <span id="attente" class="categories">Témoignages en attente</span>
                <span id="valide" class="categories">Témoignages validés</span>
            </div>



            <?php if($temoignages_en_attente): ?>            
                <?php foreach ($temoignages_en_attente as $temoignage_en_attente) : 

                    //Récupération des information du logement sur lequel on a posté un témoignage
                    $requete = $conn->prepare(" SELECT * FROM logement WHERE id = :id_logement");

                    $requete->execute([
                        ":id_logement" => $temoignage_en_attente['id_logement'],
                    ]);
                    
                    $infos_logement = $requete->fetch(PDO::FETCH_ASSOC);
                    

                    //Récupération des informations de l'utilisateur qui a posté le témoignage
                    $requete = $conn->prepare(" SELECT * FROM profil WHERE id_user = :id_user");

                    $requete->execute([
                        ":id_user" => $temoignage_en_attente['id_user'],
                    ]);
                    
                    $infos_user = $requete->fetch(PDO::FETCH_ASSOC);

                    ?>

                    <div class="contenu temoignages_attente">
                        <div class="infos_logement">
                            <p class="nom_logement"><?=$infos_logement['name'] ?> , <?= $infos_logement['ville']?></p>
                        </div>
                        <div class="affichage_temoignages">
                            <p class="nom_utilisateur"><?=$infos_user['first_name'] ?>  <?= $infos_user['name']?></p>
                            <p class="contenu"><?=$temoignage_en_attente['contenu'] ?></p>
                        </div>

                        <form action="" method="post">
                            <input class="submit" type="submit" name="valider_attente" value="Valider" />
                            <input class="submit" type="submit" name="supprimer_attente" value="Supprimer" />
                            <input type="hidden" name="id_temoignage_en_attente" value="<?= $temoignage_en_attente['id']?>">
                        </form>
                    </div>
                    <?php endforeach; ?>        
                <?php endif; ?>

                <?php if($temoignages_valide): ?>            
                <?php foreach ($temoignages_valide as $temoignage_valide) : 

                    //Récupération des information du logement sur lequel on a posté un témoignage
                    $requete = $conn->prepare(" SELECT * FROM logement WHERE id = :id_logement");

                    $requete->execute([
                        ":id_logement" => $temoignage_valide['id_logement'],
                    ]);
                    
                    $infos_logement = $requete->fetch(PDO::FETCH_ASSOC);
                    

                    //Récupération des informations de l'utilisateur qui a posté le témoignage
                    $requete = $conn->prepare(" SELECT * FROM profil WHERE id_user = :id_user");

                    $requete->execute([
                        ":id_user" => $temoignage_valide['id_user'],
                    ]);
                    
                    $infos_user = $requete->fetch(PDO::FETCH_ASSOC);

                    ?>

                    <div class="contenu  temoignages_valide">
                        <div class="infos_logement">
                        <p class="nom_logement"><?=$infos_logement['name'] ?> , <?= $infos_logement['ville']?></p>
                        </div>
                        <div class="affichage_temoignage">
                        <p class="nom_utilisateur"><?=$infos_user['first_name'] ?>  <?= $infos_user['name']?></p>
                        <p class="contenu"><?=$temoignage_valide['contenu'] ?></p>
                        </div>

                        <form action="" method="post">
                            <input class="submit" type="submit" name="supprimer_valide" value="Supprimer" />
                            <input type="hidden" name="id_temoignage_valide" value="<?= $temoignage_valide['id']?>">
                        </form>
                    </div>
                    <?php endforeach; ?>        
                <?php endif; ?>               

        </div>
    </div>
    <script>
        var attente = document.getElementById('attente');
        var valide = document.getElementById('valide');
        var all = [attente, valide]

        attente.addEventListener('click', function(){
            console.log('click')
            tab_switch(0);
         });
        valide.onclick = function(){ tab_switch(1); };


;
        var temoignages_attente = document.querySelectorAll('.temoignages_attente');
        var temoignages_valide = document.querySelectorAll('.temoignages_valide');
        
        function tab_switch(tab)
        {
            switch (tab) {
                case 0:
                    Array.from(temoignages_attente).forEach(element => {
                        element.classList.remove('hidden')
                    });
                    Array.from(temoignages_valide).forEach(element => {
                        element.classList.add("hidden")
                    });
                    break;

                case 1:
                    Array.from(temoignages_attente).forEach(element => {
                        element.classList.add('hidden')
                    });
                   Array.from(temoignages_valide).forEach(element => {
                        element.classList.remove("hidden")
                    });
                    break;
            }

            


            // for (var i = 0; i < all.length; i++) {


            //     if (!contents[i].classList.contains('hidden')) {
            //         contents[i].classList.add('hidden')
            //         all[i].classList.remove('selected')
            //     }
            // }

            all[tab].classList.add('selected')
//contents[tab].classList.remove('hidden')

   
        }
    </script>
</body>
</html>
