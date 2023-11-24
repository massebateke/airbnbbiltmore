<?php
// Ne pas oublier de vérifier le token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('../../header/header_interne.html');
require("token.php");


session_start();
if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {
    // Vérification de la soumission du formulaire
    if (isset($_POST['date'])) {
        $date = $_POST['date'];

        // Requête pour sélectionner les entrées de la table "entretien" en fonction de la date
        $requete = $conn->prepare('SELECT * FROM entretien WHERE date = :date');
        $requete->bindParam(':date', $date);
        $requete->execute();
        $entretiens = $requete->fetchAll(PDO::FETCH_ASSOC);

    } 



    // Enregistrer les modifications faites pour les entretiens
    if (isset($_POST['enregistrer'])){

        // Récupère les données du formulaire
        $note_information = filter_input(INPUT_POST, "note_informations");
        $etat = $_POST['etat'];
        $id_logement = $_POST['id_logement'];
        $detail = $_POST['detail'];
        $commentaire = filter_input(INPUT_POST, "commentaire");
            

        // t'as juste besoin d'update toutes les infos quand c'est enregistré
        //reprends l'exemple de modifier_profil c'est exactement pareil
        $requete = $conn->prepare(" UPDATE entretien SET etat = :etat, contenu = :contenu, detail = :detail, commentaire =:commentaire WHERE id_logement = :id_logement ");
    
        $requete->execute([
            ":etat" => $etat,
            ":contenu" => $note_information,
            ":id_logement" => $id_logement,
            ":commentaire" => $commentaire,
            ":detail" => implode(",",$detail),//un tableau qui contient une chaine de caratère séparer par des ","

        ]);
                

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
    <title>Affichage des entretiens par date</title>
</head>
<body>
    <h2>Entretiens par date</h2>
    <form method="post" action="">
        <label for="date">Date :</label>
        <input type="date" name="date" required>
        <input type="submit" value="Afficher les entretiens">
    </form>
            <?php
            if (isset($_POST['date'])) {
                $date = $_POST['date']; 
             // Vérification s'il y a des résultats
                    if (count($entretiens) != 0) {
                        // Affichage des de tous les entretiens à la date selectionnée
                        foreach ($entretiens as $entretien) {
            ?>
            
        <form method="post" action="">
                        <p>Date <?= $entretien['date'] ?> <br></p>
                        <p>Référence : <?= $entretien['id_logement'] ?> <br></p>
                        
                        <input type="checkbox" name="detail[]" value="salon">
                        <label for="salon">Salon</label><br>

                        <input type="checkbox" name="detail[]" value="cuisine" >
                        <label for="cuisine">Cuisine</label><br>

                        <input type="checkbox"  name="detail[]" value="W.C">
                        <label for="wc">W.C.</label><br>

                        <select name="etat">
                            <option value="a faire">A faire</option>
                            <option value="en cours">En cours</option>
                            <option value="fait">Fait</option>
                        </select><br>
                        
                        <input type="textarea" name="note_informations" value="<?=$entretien['contenu']?>">
                        <input type="hidden" id="id_logement" name="id_logement" value="<?=$entretien['id_logement']?>" /><br>
                    
                        <textarea name="commentaire"><?=$entretien['commentaire']?></textarea>
                        <input type="hidden" name="id_logement" value="<?=$entretien['id_logement']?>" />

                        
                    <input type="submit" name="enregistrer" value="Enregistrer">
                </form>
    <?php     
                        }
                    }
                    } else {
                            echo "Aucun résultat trouvé pour la date spécifiée.";
                        }

    ?>
</body>
</html>
