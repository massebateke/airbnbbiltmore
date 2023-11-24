<?php
    session_start();
    
    require('config.php');
    

    if(isset($_POST['deconnexion'])){
        header("Location: logout.php");
    }elseif(isset($_POST['retour'])){
        header("Location: accueil.php");
    }
   
    ?>

<!DOCTYPE html>
<html>
    <head>
        <link href="../CSS/verify_logout.css" rel="stylesheet" />
        <title>Déconnexion</title>
    </head>
    <body>
        <div class="container">
            <div class="container-bleu">
                <div class="logo">
                    <a href=""><img  width="75" height="75" src="../images/BFO_logo_rgb.original.png" alt=""></a>
                </div>
                <h1 class="titre-deconnexion">Déconnexion</h1>
            </div>
            <div class="carré-de-deco">
                <p class="texte">Êtes-vous absolument sûr(e) de vouloir vous déconnecter ? 
                    En quittant cette interface, vous perdrez l'accès à toutes les fonctionnalités et informations disponibles ici. </p>
            </div>
             <form action="" class="form" method="POST">
                    <input type="submit" name="retour" value="Retour" class="button" />
                    <input type="submit" name="deconnexion" value="Déconnexion" class="button1" />
                </form>
        </div>        

        <div class="footer">
            <div class="texte">
                <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
            </div>
            <hr class="hr1">
        </div>    
    </body>
</html>