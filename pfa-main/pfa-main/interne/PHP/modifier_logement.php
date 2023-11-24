<?php


require("config.php");
require('../../header/header_interne.html');
require("token.php");


session_start();
if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {
        $id_logement = $_GET['id_logement'];


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
                    
                    // Met à jour les données de l'événement dans la BDD

                        $requete = $conn->prepare(" UPDATE logement SET name = :name, adresse = :adresse, code_postale = :code_postale, ville = :ville, prix = :prix, nombre_place = :nombre_place, nombre_chambre = :nombre_chambre, nombre_lit = :nombre_lit, nombre_sdb = :nombre_sdb, description = :description WHERE id=:id LIMIT 1 ");
                    
                    //   $requete ->bindValue(':user',$username, PDO::PARAM_STR_CHAR);

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
                        "id" => $id_logement
                    ]);

                    // Affiche un message de confirmation
                    
                    echo "L'évènement \"$name\" a été modifié avec succès.";

            }
        }

    }

    $requete = $conn ->prepare("SELECT * FROM logement WHERE id = :id_logement");
    $requete -> execute([
        ":id_logement" =>$id_logement
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
    <link rel="stylesheet" href="../../header/header_interne.css">
    <link rel="stylesheet" href="../CSS/modifier_logements.css">
    <title>Modifier le logement</title>


</head>
<body>

   
    <form method="POST">
        <div class="logements">
        <label>Nom: 
        <input placeholder="" class="box-input" type="text" name="name" value= "<?php echo $evenement['name']; ?>"> <!--    -->
        </label>

        <label>Adresse:
        <input placeholder="" class="box-input" type="text" name="adresse" value= "<?php echo $evenement['adresse']; ?>"> <!--    -->
        </label>

        <label>Code postale:
            <input placeholder="" class="box-input" type="number" name="code_postale" value= "<?php echo $evenement['code_postale']; ?>" > <!--    -->
        </label>


        <label>Ville: 
            <input placeholder="" type="text" class="box-input" name="ville" value= "<?php echo $evenement ['ville']; ?>" > <!--    -->
        </label>

        <label>Prix: 
            <input placeholder="" type="number" class="box-input" name="prix" value= "<?php echo $evenement ['prix']; ?>" > <!--    -->
        </label>

        <label>Nombre de places: 
            <input placeholder="" type="number" class="box-input" name="nombre_place" value= "<?php echo $evenement ['nombre_place']; ?>" > <!--    -->
        </label>

        <label>Nombre de chambres: 
            <input placeholder="" type="number" class="box-input" name="nombre_chambre" value= "<?php echo $evenement ['nombre_chambre']; ?>" > <!--    -->
        </label>

        <label>Nombre de lits: 
            <input placeholder="" type="number" class="box-input" name="nombre_lit" value= "<?php echo $evenement ['nombre_lit']; ?>" > <!--    -->
        </label>

        <label>Nombre de salles de bain: 
            <input placeholder="" type="number" class="box-input" name="nombre_sdb" value= "<?php echo $evenement ['nombre_sdb']; ?>" > <!--    -->
        </label>

        <label>Description: 
            <input placeholder="" type="textarea" row="20" class="box-input" name="description" value= "<?php echo $evenement ['description']; ?>" > <!--    -->

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