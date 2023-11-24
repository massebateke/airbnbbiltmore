<?php
    session_start();
    
    require('config.php');
    
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    $error = null;
    
    if ($methode == "POST") {
      $login = filter_input(INPUT_POST, "login");
      $password = filter_input(INPUT_POST, "password");
      $_SESSION["email"]=$login;
    
      $requete = $conn->prepare("SELECT * FROM profil WHERE email = :login");
      $requete->execute([":login" => $login]);
    
      $user = $requete->fetch(PDO::FETCH_ASSOC);
      var_dump($user);
      var_dump($_SESSION);
      $_SESSION["id_user"]=$user['id_user'];
      $_SESSION["username"]=$user['username'];
    
    var_dump(password_hash($password, PASSWORD_DEFAULT));
    var_dump($user["password"]);
      if (password_verify($password, $user["password"])) {
    
        $_SESSION["loggedin"] = true;
        $error = null;
        
        $token = uniqid('', true);
        $_SESSION['token']=$token;
    
        $requete1 = $conn->prepare("UPDATE profil SET token = :token WHERE email = :login");
        $requete1->execute([":token" => $token, ":login" => $login]);
        if($user['role'] == "public"){
          header('Location: accueil.php');
        }else{
          header('Location: ../../interne/SCRIPT/accueil_interne.html');
        }

      } else {
        $error = "Identifiants invalides";
        var_dump($error);
      }
    }
    ?>

<!DOCTYPE html>
<html>
    <head>
        <link href="../CSS/login.css" rel="stylesheet" />
        <link href="https://fonts.cdnfonts.com/css/cammron" rel="stylesheet">
        <style> @import url('https://fonts.cdnfonts.com/css/cammron'); </style>
        <title>Connexion</title>
    </head>
    <body>
      <div class="container">

        <div class="container-bleu">
          <div class="logo">
            <a href=""><img  width="75" height="75" src="../../images/BFO_logo_rgb.original.png" alt=""></a>
          </div>
          <h1 class="titre-connexion">Connexion</h1>
        </div>
            
        <form action="" class="form" method="POST">
          <label><h2 class="ad-mail">Adresse mail </h2>
              <input class="mail" placeholder="Entrez votre adresse mail" type="text" name="login" id="login"> <!--    -->
          </label>

          <label><h2 class="mdp">Mot de passe</h2>
              <input class="password" placeholder="Mot de passe" type="password" name="password" id="password"><!--    -->
          </label>

          <p class="mdp_oublie"><a href="#">Mot de passe oublié</a></p>

          <input type="submit" name="submit" value="Connexion" class="button" />
        </form>
        <div class="trait">
          <hr>

          <p class="inscription"><a href="register.php">Pas de compte ? Inscrivez-vous</a></p>
        </div>

        <div class="compte">
          <div class="google">
            <a href=""></a>
            <img src="../../images/google.png" alt="">
            <p class="google-compte">ME CONNECTER VIA MON COMPTE GOOGLE MAIL</p>
          </div>
          <br>
          <div class="apple">
            <img src="../../images/apple.png" alt="">
            <p class="apple-compte">ME CONNECTER VIA MON COMPTE APPLE</p>
          </div>
          <br>
          <div class="facebook">
            <img src="../../images/facebook.png" alt="">
            <p class="facebook-compte">ME CONNECTER VIA MON COMPTE FACEBOOK</p>
          </div>
        </div>
      </div>

      <div class="footer">
        <div class="texte">
          <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
      </div>
    </body>
</html>