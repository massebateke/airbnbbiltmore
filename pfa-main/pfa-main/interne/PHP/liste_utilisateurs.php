<?php
// ne pas oublier de vérifier token
require('config.php');
require('../../header/header_interne.html');

session_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>discussion privée</title>
    <link rel="stylesheet" href="../../header/header_interne.css">
    <script type="text/javascript">
    </script>
</head>
<body>


        <?php
        $recupUsers = $conn->prepare('SELECT * FROM profil');
        $recupUsers->execute([]);
        foreach($recupUsers as $user) {
                ?>
                <p><?= $user['first_name']; ?></p>
                <p><?= $user['name']; ?></p>
                <form action="" method="post">
                    <input id="boutton_supprimer" class="msg-envoye" type="submit" name="supprimer" value="Supprimer">
                    <input id="desactiver" class="msg-envoye" type="submit" name="désactiver" value="activer">
                </form>
            <?php
            } 
        ?>

</body>
</html>