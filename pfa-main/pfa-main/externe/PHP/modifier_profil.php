<?php


require("config.php");
require("../../header/header.php");
require("token.php");



if (isset($_COOKIE["validate"])) {
    
        $username = $_SESSION["username"];
        $user_id = $_SESSION["id_user"];
        
        if(isset($_FILES["photo_profil"])){// Vérifie si le champ d'upload "photo_profil" est défini
        
        
            $imageName = $_FILES["photo_profil"]["name"];// Récupère le nom de l'image uploadée depuis le champ de formulaire "photo_profil"
            $imageSize = $_FILES["photo_profil"]["size"];// Récupère la taille de l'image uploadée depuis le champ de formulaire "photo_profil"
            $tmpName = $_FILES["photo_profil"]["tmp_name"];// Récupère le nom temporaire de l'image uploadée depuis le champ de formulaire "photo_profil"
        
            //Validation de l'image
            $validImageExtension = ['jpg', 'jpeg', 'png'];// Les extensions d'images autorisées
            $imageExtension = explode('.', $imageName);// Sépare le nom de l'image et son extension
            $imageExtension = strtolower(end($imageExtension));// Convertit l'extension en minuscules
            if(!in_array($imageExtension, $validImageExtension)){// Vérifie si l'extension de l'image est autorisée
                echo "<ceci n'est pas une image";
                header("Location: modifier_profil.php");
        
            }
            elseif ($imageSize > 1200000) {// Vérifie si la taille de l'image est supérieure à 1,2 Mo
                echo "L'image est trop lourde";
                header("Location: modifier_profil.php");
            }else{
            /*  $newImageName = $imageName ; *///Genere un nouveau nom d'image 
            // Ajoute l'extension de l'image au  nouveau nom généréquery = "UPDATE profil SET photo_profil='$newImageName' WHERE user_id= $user_id"; Met à jour la base de données avec le nouveau nom de l'image */
                $requete = $conn->prepare("UPDATE profil SET photo_profil=:photo_profil WHERE id_user=:user_id");
                $requete->execute([":photo_profil" => $imageName, ":user_id" => $user_id]);
                move_uploaded_file($tmpName, 'img/' . $imageName);
                header("Location: modifier_profil.php");
            }
        }
        
        
        $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        
        if($methode == "POST")
        {
            if(isset($_POST['submit'])){
                // Récupère les données du formulaire
                $first_name = filter_input(INPUT_POST, "first_name");
                $name = filter_input(INPUT_POST, "name");
                $adresse = filter_input(INPUT_POST, "adresse");
                $num_tel = filter_input(INPUT_POST, "num_tel");
                $email = filter_input(INPUT_POST, "email");
                $username = filter_input(INPUT_POST, "username");
                $password = filter_input(INPUT_POST, "password");
                $confirm_password = filter_input(INPUT_POST, "confirm_password");    
                    
        
        
        
                // Vérifier que les champs ne sont pas vides
                if(empty($first_name) || empty($name) || empty($adresse) || empty($num_tel) || empty($email) || empty($username)){
                    echo "Tous les champs sont obligatoires.";
                } else {
                    if($password == $confirm_password){
                        
                        // Met à jour les données de l'événement dans la BDD
        
                            $requete = $conn->prepare(" UPDATE profil SET first_name = :first_name, name = :name, adresse = :adresse, num_tel = :num_tel, email = :email, username = :username, password = :password WHERE username=:username LIMIT 1 ");
                        
                        //   $requete ->bindValue(':user',$username, PDO::PARAM_STR_CHAR);
        
                        $requete->execute([
                            ":first_name" => $first_name,
                            ":name" => $name,
                            ":adresse" => $adresse,
                            ":num_tel" => $num_tel,
                            ":email" => $email,
                            ":password" => $password,
                            ":username" => $username,
                            
                        ]);
        
                        // Affiche un message de confirmation
                        
                        echo "L'évènement \"$username\" a été modifié avec succès.";
                    }else{
                        echo "Les mots de passe sont différents";
                    }
                }
            }
        
        }
        $requete = $conn ->prepare("SELECT * FROM profil WHERE username = :username");
        $requete -> execute([
            ":username" =>$username
        ]);
        $evenement = $requete->fetch(PDO::FETCH_ASSOC);
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
      <title>Modifier le profil</title>
      <link rel="stylesheet" href="../../header/header.css">
        <link rel="stylesheet" href="../CSS/modifier_profil.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../../header/header.js" defer></script>
    </head>
    <body>
              <form class ="form" id= "form" action="" enctype= "multipart/form-data" method="post">
                <div class="upload"><!-- Ouverture de la div qui contient l'élément pour télécharger l'image -->
                    <img src="../../img/<?php echo $evenement["photo_profil"];?>" width = 125 height = 125 title = "<?php echo $evenement["photo_profil"]; ?>" alt=""><!-- Affiche l'image de profil de l'utilisateur -->
                    <div class= round>
                        <input type= "hidden" name="user_id" value="<?php echo $user_id; ?>"><!-- Champ caché contenant l'ID de l'utilisateur -->
                        <input type= "hidden" name="name" value="<?php echo $name; ?>"><!-- Champ caché contenant le nom de l'utilisateur -->
                        <input type= "file" name="photo_profil" id="photo_profil" accept= ".jpg, .jpeg, .png"><!-- Champ de formulaire pour sélectionner une nouvelle image -->
                        <i class= "fa fa-camera" style= "color: #fff"></i>
                    </div>
                </div>
               </form>
                <script type="text/javascript">
                    document.getElementById("photo_profil").onchange = function(){
                        document.getElementById('form').submit();
                    }
                </script>
       
       <form method="POST">
            <div class="profil">
                
            
          <label class="email">Email:<br>
          <input placeholder="" class="box-input" type="text" name="email" value= "<?php echo $evenement['email']; ?>"> <!--    -->
          </label>
       
          <br>
          <br>
    
          <label class="Nom-utilisateur">Nom d'utilisateur:<br>
          <input placeholder="" class="box-input" type="text" name="username" value= "<?php echo $evenement['username']; ?>"> <!--    -->
            </label>
    
            <br>
            <br>
    
            <label class="mdp">Mot de passe:<br>
                <input placeholder="Entrez votre mot de passe" class="box-input" type="text" name="password" value= "" > <!--    -->
            </label>
    
            <br>
            <br>
    
            <label class="Confirmer-mdp">Confirmez votre mot de passe:<br>
                <input placeholder="Confirmez votre mot de passe" class="box-input" type="text" name="confirm_password" value= "" > <!--    -->
            </label>
            
            <br>
            <br>
    
            <label class="Nom">Nom: <br>
                <input placeholder="" type="text" class="box-input" name="name" value= "<?php echo $evenement ['name']; ?>" > <!--    -->
            </label>
    
            <br>
            <br>
    
            <label class="Prenom">Prénom: <br>
                <input placeholder="" type="text" class="box-input" name="first_name" value= "<?php echo $evenement ['first_name']; ?>" > <!--    -->
            </label>
    
            <br>
            <br>
    
            <label class="numero">Numéro de téléphone: <br>
                <input placeholder="" type="text" class="box-input" name="num_tel" value= "<?php echo $evenement ['num_tel']; ?>" > <!--    -->
            </label>
    
            <br>
            <br>
    
            <label class="Adresse">Adresse: <br> 
                <input placeholder="" type="text" class="box-input" name="adresse" value= "<?php echo $evenement ['adresse']; ?>" > <!--    -->
            </label>
            <br>
            <br>
    
                <input class="submit" type="submit" name="submit" value="Enregistrer les modifications" /> <!--    -->
           
        </div>
    </form>
      
    </body>
    </html>