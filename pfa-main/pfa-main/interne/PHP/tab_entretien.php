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
    //Récupération de tous les entretiens
    $requete = $conn->prepare('SELECT * FROM entretien ');
    $requete->execute([]);
    $entretiens = $requete->fetchAll(PDO::FETCH_ASSOC);

    // Vérification de la soumission du formulaire
    if (isset($_POST['date'])) {
        $date = $_POST['date'];

        // Requête pour sélectionner les entrées de la table "entretien" en fonction de la date
        $requete = $conn->prepare('SELECT * FROM entretien WHERE date = :date');
        $requete->bindParam(':date', $date);
        $requete->execute();
        $entretiens_par_date = $requete->fetchAll(PDO::FETCH_ASSOC);

    } 



    // Enregistrer les modifications faites pour les entretiens
    if (isset($_POST['enregistrer'])){

        // Récupère les données du formulaire
        $note_information = filter_input(INPUT_POST, "note_informations");
        $etat = $_POST['etat'];
        $id_logement = $_POST['id_logement'];

        $commentaire = filter_input(INPUT_POST, "commentaire");
            


        $requete = $conn->prepare(" UPDATE entretien SET etat = :etat, contenu = :contenu,  commentaire =:commentaire WHERE id_logement = :id_logement ");
        $requete->execute([
            ":etat" => $etat,
            ":contenu" => $note_information,
            ":id_logement" => $id_logement,
            ":commentaire" => $commentaire,
            
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
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/tab_entretien.css">
    <link rel="stylesheet" href="../../header/header_interne.css">
    <script src="../JS/header.js" defer></script>
    <title>Tableau d'entretiens</title>
</head>

<body>
    
    <a href="tab_entretien.php"><p class="titre">TABLEAU D'ENTRETIENS</p></a>
    <form class="form" method="post" action="">
        <label for="date">Sélectionner une date :</label>
        <input type="date" class="date-design" name="date" required>
        <input type="submit" class="button-design-ok" value="Ok">
    </form>

        

           
                        <?php   if (isset($_POST['date'])) { ?>
                             <form method="post" action="">

                            <?php $date = $_POST['date']; ?>
                            <table>
                            <thead>
                                <tr>
                                    <th scope="col">Dates </th>
                                    <th scope="col">Références</th>
                                    <th scope="col">Etat</th>
                                    <th scope="col">Informations</th>
                                    <th scope="col">Commentaires</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                <?php
                            // Vérification s'il y a des résultats
                                if (count($entretiens_par_date) != 0) {
                                    // Affichage des de tous les entretiens à la date selectionnée
                                    foreach ($entretiens_par_date as $entretien_par_date) {?>
                        <tr>
                            <td><?= $entretien_par_date['date'] ?></td>
                            <td><?= $entretien_par_date['id_logement'] ?></td>
                            <td>
                                <select name="etat">
                                    <option value="a faire">A faire</option>
                                    <option value="en cours">En cours</option>
                                    <option value="fait">Fait</option>
                                </select>
                            </td>
                            <td><input type="textarea" name="note_informations" value="<?=$entretien_par_date['contenu']?>">
                                <input type="hidden" id="id_logement" name="id_logement" value="<?=$entretien_par_date['id_logement']?>" />
                            </td>
                            <td>
                                <textarea name="commentaire"><?=$entretien_par_date['commentaire']?></textarea>
                            </td>
                            

                        </tr>
                        </form>
                    <?php }?>
                    </tbody>
                    </table>
                    <form method="post" action="">
                         <input type="submit" class="button1" name="enregistrer" value="Ok">
                     </form>
               <?php } ?>
                  

           
                    <?php     
                        } else {  ?>
                            <table>
                            <thead>
                                <tr>
                                    <th scope="col">Dates </th>
                                    <th scope="col">Références</th>
                                    <th scope="col">Etat</th>
                                    <th scope="col">Informations</th>
                                    <th scope="col">Commentaires</th>
                                </tr>
                            </thead> <?php
                            foreach($entretiens as $entretien){?>
                    <div class="formu">
                        <form method="post" action="">


                            <tbody>
                        <tr>
                            <td><?= $entretien['date'] ?></td>
                            <td><?= $entretien['id_logement'] ?></td>
                            <td>
                                <select name="etat">
                                    <option value="a faire">A faire</option>
                                    <option value="en cours">En cours</option>
                                    <option value="fait">Fait</option>
                                </select>
                            </td>
                            <td><input type="textarea" name="note_informations" value="<?=$entretien['contenu']?>">
                                <input type="hidden" id="id_logement" name="id_logement" value="<?=$entretien['id_logement']?>" />
                            </td>
                            <td>
                                <textarea name="commentaire"><?=$entretien['commentaire']?></textarea>
                            </td>
                        </form>
                            
                        </tr>
                        <?php } ?>
                        </tbody>
                </table>

                <form method="post" action="">
                <input type="submit" class="button1" name="enregistrer" value="Ok">
                </form>
                        
                           <?php }?>

                    
             </div>



    <div class="footer">
        <div class="texte">
            <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
    </div>
</body>
</html>