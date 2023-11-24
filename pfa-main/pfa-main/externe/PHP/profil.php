<?php


require("config.php");
require("../../header/header.php");
require("token.php");

require("token.php");



if (isset($_COOKIE["validate"])) {
    $username = $_SESSION["username"];

    $requete = $conn ->prepare("SELECT * FROM profil WHERE username = :username");

    $requete -> execute([
        ":username" =>$username
    ]);

    $evenement = $requete->fetch(PDO::FETCH_ASSOC);



    if(isset($_POST['modifier_profil'])){
        var_dump($_POST['modifier']);
        header("Location: modifier_profil.php");
    }
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../header/header.css">
    <link rel="stylesheet" href="../CSS/profil.css">
    <script src="../../header/header.js" defer></script>
    <title>Profil</title>
</head>
<body>
    <form method="POST">
        <div class="profil">
            
        <h1><?php echo $evenement['username']; ?></h1> <!---->

        <label class="email">Email:<br>
            <p class="box-input" type="text" name="email"><?php echo $evenement['email']; ?> </p><!--    -->
        </label>
   
      <br>
      <br>

        <label class="Nom-utilisateur">Nom d'utilisateur:<br>
            <p class="box-input" type="text" name="username"> <?php echo $evenement['username']; ?></p> <!--    -->
        </label>

        <br>
        <br>

        <label class="Nom">Nom: <br>
            <p type="text" class="box-input" name="name"><?php echo $evenement ['name']; ?></p> <!--    -->
        </label>

        <br>
        <br>

        <label class="Prenom">Prénom: <br>
            <p type="text" class="box-input" name="first_name"> <?php echo $evenement ['first_name']; ?> </p><!--    -->
        </label>

        <br>
        <br>

        <label class="numero">Numéro de téléphone: <br>
            <p type="text" class="box-input" name="num_tel"> <?php echo $evenement ['num_tel']; ?> </p> <!--    -->
        </label>

        <br>
        <br>

        <label class="Adresse">Adresse: <br> 
            <p type="text" class="box-input" name="adresse"> <?php echo $evenement ['adresse']; ?></p> <!--    -->
        </label>
        <br>
        <br>
        
        <input class="submit" type="submit" id="modifier" name="modifier_profil" value="Modifier">

       
    </div>


</form>
  
</body>
</html>