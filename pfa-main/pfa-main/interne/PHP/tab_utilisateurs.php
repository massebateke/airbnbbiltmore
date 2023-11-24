<?php
// ne pas oublier de vérifier token
require('config.php');
require('../../header/header_interne.html');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes des utilisateurs</title>
    <link rel="stylesheet" href="../../header/header_interne.css">
    <link rel="stylesheet" href="../CSS/tab_utilisateurs.css">
    <script type="text/javascript">
    </script>
</head>
<body>

    <a href="tab_utilisateurs.php"><p class="titre">LISTE DES UTILISATEURS</p></a>

    <form action="" method="post">
    <table class="tab">
        <thead>
            <tr>
                <th scope="col">Nom </th>
                <th scope="col">Prénom</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $recupUsers = $conn->prepare('SELECT * FROM profil');
            $recupUsers->execute([]);
            foreach($recupUsers as $user) {
                    ?>
                <tr>
                    <td><p><?= $user['name']; ?></p></td>
                    <td><p><?= $user['first_name']; ?></p></td>
                    
                    
                <?php
                } 
            ?>
                </tr>

        </tbody>
        <div class="les-buttons">
            <input class="boutton_supprimer" type="submit" name="supprimer" value="Supprimer">
            <input class="desactiver" type="submit" name="désactiver" value="activer">
        </div>
        </form>
    <div class="footer">
        <div class="texte">
            <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
    </div>
</body>
</html>