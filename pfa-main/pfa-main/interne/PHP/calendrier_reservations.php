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
    // Récupérer les logements à partir de la base de données
    $id_logement = $_GET['id'];
    $requete_logements = $conn->query('SELECT * FROM logement');
    $logements = $requete_logements->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour récupérer les réservations
    $requete_reservations = $conn->prepare('SELECT * FROM reservation WHERE id_logement = :id_logement');
    $requete_reservations->bindParam(':id_logement', $id_logement);
    $requete_reservations->execute();
    $reservations = $requete_reservations->fetchAll(PDO::FETCH_ASSOC);

    // Fonction pour formater la date 
    function genererDates($dateDebut, $dateFin) {
        $dates = array();
        $currentDate = strtotime($dateDebut);
        $endDate = strtotime($dateFin);

        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dates;
    }
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
}

// Vérifier si des logements ont été trouvés
if (!empty($logements)) { ?>


        <!DOCTYPE html>
        <html>
        <head>
            <link rel="stylesheet" href="../../header/header_interne.css">
            <title>Calendrier des réservations par logement</title>
            <style>
                table {
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                th, td {
                    border: 1px solid black;
                    padding: 5px;
                    text-align: center;
                }

                .calendar {
                    width: 200px;
                }

                .calendar th {
                    background-color: #ccc;
                }

                .calendar td {
                    height: 30px;
                }

                .reservation {
                    background-color: #ffcdd2;
                }
            </style>
        </head>
        <body>

        <?php
            // Parcourir les logements avec une boucle foreach
            foreach ($logements as $logement) {
        ?>
            <h3>Calendrier pour le logement <?= $logement['name'] ?></h3>
            <table class="calendar">
                <tr>
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mer</th>
                    <th>Jeu</th>
                    <th>Ven</th>
                    <th>Sam</th>
                    <th>Dim</th>
                </tr>
                <?php
                // Mois et année actuels
                $mois_actuel = date('n');
                $annee_actuelle = date('Y');

                // Jour de la semaine du premier jour du mois
                $premier_jour_semaine = date('N', strtotime("first day of $mois_actuel-$annee_actuelle"));

                // Nombre de jours dans le mois en cours
                $nombre_jours = date('t', strtotime("last day of $mois_actuel-$annee_actuelle"));

                
                 // Compteur pour les jours
                 $jour_compteur = 1;

                 // Boucle pour afficher les semaines du mois en cours
                 for ($semaine = 1; $semaine <= 6; $semaine++) {
                     echo '<tr>';
                     // Boucle pour afficher les jours de la semaine
                     for ($jour_semaine = 1; $jour_semaine <= 7; $jour_semaine++) {
                         // Vérification si le jour fait partie du mois en cours
                         if ($semaine === 1 && $jour_semaine < $premier_jour_semaine || $jour_compteur > $nombre_jours) {
                             // Jour vide pour les jours avant le premier jour du mois ou après le dernier jour du mois
                             echo '<td>&nbsp;</td>';
                         } else {
                             // Récupération de la date
                             $date_courante = sprintf('%04d-%02d-%02d', $annee_actuelle, $mois_actuel, $jour_compteur);
 
                             // Vérification si la date est une réservation
                             $est_reservation = false;
                             foreach ($reservations as $reservation) {
                                 $dates_reservation = genererDates($reservation['date_debut'], $reservation['date_fin']);
                                 if (in_array($date_courante, $dates_reservation)) {
                                     $est_reservation = true;
                                     break;
                                 }
                             }
                            
 
                             // Classe CSS pour mettre en évidence les réservations
                             $classe_css = $est_reservation ? 'reservation' : '';
 
                             // Affichage de la cellule du calendrier
                             echo '<td class="' . $classe_css . '">' . $jour_compteur . '</td>';
 
                             // Incrémenter le compteur de jours
                             $jour_compteur++;
                         }
                     }
                     echo '</tr>';
                 }
                 ?>
             </table>
         <?php
         }
     } else {
         // Aucun logement trouvé
         echo "Aucun logement trouvé.";
     }
     ?>
     </body>
     </html>
                
