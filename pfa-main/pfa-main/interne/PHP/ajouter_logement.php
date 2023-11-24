<?php


require("config.php");
require('../../header/header_interne.html');
require("token.php");


session_start();
if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {


    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    
    if($methode == "POST")
    {
        if(isset($_POST['submit'])){
            // Récupère les données du formulaire
            $name = filter_input(INPUT_POST, "name");
            $adresse = filter_input(INPUT_POST, "adresse");
            $code_postale = filter_input(INPUT_POST, "code_postale");
            $ville = filter_input(INPUT_POST, "ville");
            $prix = filter_input(INPUT_POST, "prix");
            $nombre_place = filter_input(INPUT_POST, "nombre_place");
            $nombre_chambre = filter_input(INPUT_POST, "nombre_chambre");
            $nombre_lit = filter_input(INPUT_POST, "nombre_lit");        
            $nombre_sdb = filter_input(INPUT_POST, "nombre_sdb");
            $description = filter_input(INPUT_POST, "description");

            // Vérifier que les champs ne sont pas vides
            if(empty($name) || empty($adresse) || empty($code_postale) || empty($ville) || empty($prix) || empty($nombre_place)|| empty($nombre_chambre) || empty($nombre_lit) || empty($description)){
                echo "Tous les champs sont obligatoires.";
            } else {
                    
                    // Ajoute l'événement dans la BDD

                        $requete = $conn->prepare(" INSERT INTO logement  (name, adresse, code_postale, ville, prix, nombre_place, nombre_chambre, nombre_lit, nombre_sdb, description) VALUES (:name, :adresse, :code_postale, :ville, :prix, :nombre_place, :nombre_chambre, :nombre_lit, :nombre_sdb, :description) ");
                

                    $requete->execute([
                        ":name" => $name,
                        ":adresse" => $adresse,
                        ":code_postale" => $code_postale,
                        ":ville" => $ville,
                        ":prix" => $prix,
                        ":nombre_place" => $nombre_place,
                        ":nombre_chambre" => $nombre_chambre,
                        ":nombre_lit" => $nombre_lit,
                        ":nombre_sdb" => $nombre_sdb,
                        ":description" => $description,
                    ]);

            }

                    $requete = $conn->prepare("SELECT id FROM logement ORDER BY id DESC LIMIT 1");
                    $requete->execute([]);
                    $last_id = $requete->fetch(PDO::FETCH_ASSOC);
                    
                    
                    foreach($_POST['equipement'] as $value)
                        {
                                // Insertion des équipements dans a tlble
                                $requete = $conn->prepare(" INSERT INTO equipements (id_logement, equipement) VALUES(:id_logement, :equipement)");
                                $requete->execute([":id_logement" => $last_id['id'], ":equipement" => $value]);

                        }
        }

    }
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
}


?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../header/header_interne.css">
    <link rel="stylesheet" href="../CSS/ajouter_logement.css">
    <style>
        .box-label {
            border: 1px solid black;
            padding: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
    <title>Ajouter le logement</title>
</head>
<body>
<header class="header">
    
</header>
   
    <form method="POST">
        <div class="logements">
            <label class="box-label">Nom: 
                <input placeholder="" class="box-input" type="text" name="name" value=""> <!--    -->
            </label>

        <label>Adresse:
        <input placeholder="" class="box-input" type="text" name="adresse" value= ""> <!--    -->
        </label>

        <label>Code_postale:
            <input placeholder="" class="box-input" type="number" name="code_postale" value= "" > <!--    -->
        </label>


        <label>Ville: 
            <input placeholder="" type="text" class="box-input" name="ville" value= "" > <!--    -->
        </label>

        <label>Prix: 
            <input placeholder="" type="number" class="box-input" name="prix" value= "" > <!--    -->
        </label>

        <label>Nombre de places: 
            <input placeholder="" type="number" class="box-input" name="nombre_place" value= "" > <!--    -->
        </label>

        <label>Nombre de chambres: 
            <input placeholder="" type="number" class="box-input" name="nombre_chambre" value= "" > <!--    -->
        </label>

        <label>Nombre de lits: 
            <input placeholder="" type="number" class="box-input" name="nombre_lit" value= "" > <!--    -->
        </label>

        <label>Nombre de salles de bain: 
            <input placeholder="" type="number" class="box-input" name="nombre_sdb" value= "" > <!--    -->
        </label>

        <label>Description: 
            <input placeholder="" type="textarea" row="20" class="box-input" name="description" value= "" > <!--    -->


        <div class="equipements">
            <label>Equipements: <br>
                <input type="checkbox" class="checkbox"  id= "piscine" name="equipement[]" value="Piscine privée"> <label for="piscine">Piscine privée</label>
                <input type="checkbox" class="checkbox"  id= "spa_jacuzzi" name="equipement[]" value="Spa ou jacuzzi"> <label for="spa-jacuzzi">Spa ou jacuzzi</label>
                <input type="checkbox" class="checkbox"  id= "vue_panoramique" name="equipement[]" value="Vue panoramique"> <label for="vue_panoramique">Vue panoramique</label>
                <input type="checkbox" class="checkbox"  id= "fitness" name="equipement[]" value="Chauffeur personnel"> <label for="fitness">Fitness ou salle de sport</label>
                <input type="checkbox" class="checkbox"  id= "plage" name="equipement[]" value="Salle de sport"> <label for="plage">Accès privée à la plage</label>
                <input type="checkbox" class="checkbox"  id= "securite" name="equipement[]" value="Plage privée"> <label for="securite">Sécurité renforcée</label>
                <input type="checkbox" class="checkbox"  id= "chauffeur" name="equipement[]" value="Sécurité renforcée"> <label for="chauffeur">Chauffeur personnel</label>
            </label>
        </div>

        <label>
            <input class="submit" type="submit" name="submit" value="Enregistrer" /> <!--    -->
        </label>
    </div>
</form>
  
</body>
</html>

            <label class="box-label">
                <input class="submit" type="submit" name="submit" value="Enregistrer" /> <!--    -->
            </label>
        </div>
    </form>
  
</body>
</html>
