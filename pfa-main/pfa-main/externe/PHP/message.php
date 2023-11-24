<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ne pas oublier de vérifier le token
require('config.php');

session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_logement = $_GET['id_logement'];
    $recupUsers = $conn->prepare('SELECT * FROM profil WHERE id_user = ?');
    $recupUsers->execute(array($getid));

    if ($recupUsers->rowCount() > 0) {
        while ($id_user = $recupUsers->fetch()) {
            // Traitement des données du profil ici
            // Par exemple :
            echo "Nom d'utilisateur : " . $id_user['username'];
            echo "Email : " . $id_user['email'];
            // Vous pouvez accéder aux autres colonnes du profil de la même manière
        }
    } else {
        echo "Aucun utilisateur trouvé";
    }

    if ($recupUsers->rowCount() > 0) {
        if (isset($_POST['envoyer'])) {
            $message = htmlspecialchars($_POST['message']);
            $insererMessage = $conn->prepare('INSERT INTO message (id_user, id_logement, contenu) VALUES(:id, :id_logement, :contenu )');
            $insererMessage->bindValue('id', $_SESSION['id_user'], PDO::PARAM_INT);
            $insererMessage->bindValue('id_logement', $id_logement, PDO::PARAM_INT);
            $insererMessage->bindValue('contenu', $message, PDO::PARAM_STR);
            $insererMessage->execute();
            
    } else {
        echo "Aucun utilisateur trouvé";
    }
} else {
    echo "Aucun identifiant trouvé";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Message.css">
    <title>discussion privée</title>
</head>
<body>

    <form action="" method="post">
        <textarea class="Box-message" name="message" id="message" rows="10"></textarea>
        <br><br>
        <input type="submit" name="envoyer" value="Envoyer" class="Envoyer">
    </form>
    
    <section id="messages">
        <?php 
            $recupMessages = $conn->prepare('SELECT * FROM message WHERE (id_user = ? AND id_logement = ?) OR (id_user = ? AND id_logement = ?) ORDER BY date_creation DESC LIMIT 25'); 
            $recupMessages->execute(array($_SESSION['id_user'], $getid, $getid, $_SESSION['id_user'] ));
            while($message = $recupMessages->fetch()){
                if($message['id_user'] == $_SESSION['id_user']){
                    ?>
                        <p class="msg-envoyer"style="color:red;"><?=  $message['contenu']; ?></p>
                        <input class="msg-envoyer" type="submit" name="supprimer" value="Supprimer">
                        <input class="msg-envoyer" type="submit" name="modifier" value="Modifier">
                 <?php        
                }
                elseif($message['id_user'] ==$getid){
                    ?>
                    <p style="color:green;"><?=  $message['contenu']; ?></p>
             <?php      
                }
                ?>
                <?php 

            }
        ?>

    </section>

</body>
</html>
