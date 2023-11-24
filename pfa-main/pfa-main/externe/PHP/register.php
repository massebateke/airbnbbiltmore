<?php
session_start();

require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");

if($methode == "POST")
{
    if(isset($_POST['submit'])){
        var_dump($methode);
        $name = filter_input(INPUT_POST, "name");
        $first_name = filter_input(INPUT_POST, "first_name");
        $username = filter_input(INPUT_POST, "username");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $adresse = filter_input(INPUT_POST, "adresse");
        var_dump($adresse);
        $num_tel = filter_input(INPUT_POST, "num_tel");
        $confirm_password = filter_input(INPUT_POST, "confirm_password");

        if($password == $confirm_password){
            
            $requete = $conn->prepare("INSERT INTO profil (username, email, password, first_name, name, num_tel, adresse) VALUES(:username, :email, :password, :first_name, :name, :num_tel, :adresse)");
            $requete->execute([
                ":name" => $name,
                ":first_name" => $first_name,
                ":username" => $username,
                ":email" => $email,
                ":password" => password_hash($password, PASSWORD_DEFAULT),
                ":num_tel" => $num_tel,
                ":adresse" => $adresse
            ]); 

            header("Location: login.php");
            exit();
        }
    }
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/register.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <div class="container-bleu">
            <div class="logo">
                <a href=""><img  width="75" height="75" src="../../images/BFO_logo_rgb.original.png" alt=""></a>
            </div>
            <h1 class="titre-connexion">Inscription</h1>
        </div>
        <div class="sous-Titre">Bienvenue sur BILTMORE!</div>
        <form class="form" action="" method="POST">
            <div class="intro">
            </div>
            
            <div class="infos1">               
                <div class="nom">
                    <label>Nom<br>
                        <input type="text" class="box-input" name="name" value="" placeholder="Entrez votre nom" required>
                    </label>
                </div>
                <div class="prenom">
                    <label>Prénom<br>
                        <input type="text" class="box-input" name="first_name" value="" placeholder="Entrez votre prénom" required>
                    </label>
                </div>
                <div class="nom_dutilisateur">
                    <label>Nom d'utilisateur <br>
                        <input type="text" class="box-input" name="username" value="" placeholder="Entrez votre nom d'utilisateur" required>
                    </label>
                </div>
                <div class="email">
                    <label>Email <br>
                        <input type="email" class="box-input" name="email" value="" placeholder="Entrez votre email" required>
                    </label>
                </div>
            </div>
            <div class="infos2">
            <div class="telephone">
                    <label>Téléphone <br>
                        <input type="tel" class="box-input" name="num_tel" value="" placeholder="Entrez votre numéro de téléphone" required>
                    </label>
                </div>
                <div class="adresse">
                    <label>Adresse <br>
                        <input type="text" class="box-input" name="adresse" value="" placeholder="Entrez votre adresse" required>
                    </label>
                </div>
                <div class="mdp">
                    <label>Mot de passe <br>
                        <input type="password" class="box-input" name="password" value="" placeholder="Entrez votre mot de passe" required>
                    </label>    
                </div>
                <div class="mdp">
                    <label>Confirmer votre mot de passe <br>
                        <input type="password" class="box-input" name="confirm_password" value="" placeholder="Confirmez votre mot de passe" required>
                    </label>    
                </div>
                
            </div>
            <input type="submit" name="submit" value="S'inscrire" class="button">
            
        </form>
        <p class="box-register">Vous avez déjà un compte? <a href="login.php">Connectez-vous ici.</a></p>
    </div>
    <div class="footer">
        <div class="texte">
            <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
    </div>
</body>
</html>