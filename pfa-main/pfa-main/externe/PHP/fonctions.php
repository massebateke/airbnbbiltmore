<?php
//Formatage du fuseau horaire français pour avoir la date en français
function formaterDate(DateTimeInterface $date, $format = 'd LLLL y')
{
$fmt = IntlDateFormatter::create(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    iterator_to_array(IntlTimeZone::createEnumeration('FR'))[0],
    IntlDateFormatter::GREGORIAN,
    $format
    );
    return $fmt->format($date);
}


function searchPeople($searchTerm) {
    global $conn; // Utilisez la connexion à la base de données globale

    // Préparation de la requête SQL avec des paramètres préparés
    $stmt = $conn->prepare('SELECT * FROM logement WHERE name LIKE ? OR adresse LIKE ?  OR ville LIKE ?');
    
    // Validation et échappement des données entrées par l'utilisateur
    $searchTerm = '%' . $searchTerm . '%' ;
    $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(3, $searchTerm, PDO::PARAM_STR);

    // Exécution de la requête préparée
    $stmt->execute();

    // Récupération des résultats de la requête
    

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    return $results;
}
?>