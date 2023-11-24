<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

require('../../header/header.php');
require("token.php");

if (isset($_COOKIE["validate"])) {

    $id_logement = $_GET['id_logement'];


    $requete = $conn->prepare(" SELECT * FROM temoignages WHERE id_logement = :id_logement ORDER BY date_publication");

    $requete->execute([
        ":id_logement" => $id_logement,
    ]);

    $temoignages = $requete->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../CSS/temoignages.css">
    <link rel="stylesheet" href="../../header/header.css">
    <script src="../../header/header.js" defer></script>
    <title>Témoignages</title>
</head>
<body>
    <div class= "">


        <div class="affichage_logements">
            <?php if(Count($temoignages) != 0): ?>            
                <?php foreach ($temoignages as $temoignage) : 
                    $requete = $conn->prepare(" SELECT * FROM profil WHERE id_user = :id_user");

                    $requete->execute([
                        ":id_user" => $temoignage['id_user'],
                    ]);
                    
                    $infos_user = $requete->fetch(PDO::FETCH_ASSOC);
                    
                    ?>
                    <div class="temoignage">
                        <div class="infos">
                        <p class="nom_user"><?=$infos_user['first_name'] . $infos_user['name']?></p>
                        <p class="date_pblication"><?=$temoignage['date_publication'] ?></p>
                        <p class="contenu"><?=$temoignage['contenu'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>        
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
