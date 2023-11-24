<?php


require('config.php');
require('../../header/header.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
require("token.php");



if (isset($_COOKIE["validate"])) {
    if($methode == "POST")
    {


    }
}else{
    echo "Vous n'êtes pas connecté";
    header('Location: login.php');
} 
?>


<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/filtres.css">
    <link rel="stylesheet" href="../../header/header.css">

    <title>Filtres</title>
</head>
<body>
    <div class="description">
        <p>Découvrez une variété d'options d'hébergement, qu'il s'agisse de chambres <br>
            privées, de logements entiers et bien plus encore. Les tarifs par nuit, <br>
            incluant les frais et les taxes, sont affichés pour votre commodité.
        </p>
    </div>
    <div class="formulaire_filtre">
      <form class="box" action="" method="POST">

                <div class="date">
                  <label class="dates-align">Du&emsp;
                    <input id="dates" type="date" name="date_debut" value="">
                  </label>
                  <label>&emsp;au&emsp;
                    <input id="dates" type="date" name="date_fin" value="">
                  </label>
              </div> 
              <div class="prix">    
                    <label>Prix entre&emsp;
                        <p class="prix_min"><input  class="max-min" type="number" name="prix_min" min="0">€&emsp;et&emsp;</p>
                    </label>
                    <label>&emsp;&emsp;
                        <p class="prix_max"><input  class="max-min" type="number" name="prix_max" min="0">€&emsp;</p>
                        <script>
                            //permet de faire en sorte que me minimum du prix max soit celui du prix min
                            const firstInput = document.getElementById('prix_min');
                            const secondInput = document.getElementById('prix_max');
                            
                            firstInput.addEventListener('input', function() {
                            secondInput.min = firstInput.value;
                            
                            if (secondInput.value < secondInput.min) {
                                secondInput.value = secondInput.min;
                            }
                            });
                        </script>
                    </label>
                    
              </div>

                <div class="chambres">
                    <label>Chambres: 
                        <br>
                        <input type="radio" class="radio-box"  id= "chambre_tout" name="chambre" value="0"> <label for="chambre_tout">Tout&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_un" name="chambre" value="1"> <label for="chambre_un">1&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_deux" name="chambre" value="2"> <label for="chambre_deux">2&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_trois" name="chambre" value="3"> <label for="chambre_trois">3&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_quatre" name="chambre" value="4"> <label for="chambre_quatre">4&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_cinq" name="chambre" value="5"> <label for="chambre_cinq">5&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_six" name="chambre" value="6"> <label for="chambre_six">6&emsp;</label>
                        <input type="radio" class="radio-box" id= "chambre_sept" name="chambre" value="7"> <label for="chambre_sept">7</label>
                    </label>
                </div><br>

                <div class="lits">
                    <label>Lits:
                        <br>
                        <input type="radio" class="radio-box"  id= "lit_tout" name="lit" value="0"> <label for="lit_tout">Tout&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_un" name="lit" value="1"> <label for="lit_un">1&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_deux" name="lit" value="2"> <label for="lit_deux">2&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_trois" name="lit" value="3"> <label for="lit_trois">3&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_quatre" name="lit" value="4"> <label for="lit_quatre">4&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_cinq" name="lit" value="5"> <label for="lit_cinq">5&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_six" name="lit" value="6"> <label for="lit_six">6&emsp;</label>
                        <input type="radio" class="radio-box" id= "lit_sept" name="lit" value="7"> <label for="lit_sept">7</label>
                    </label>
                </div><br>

                <div class="sdb">
                    <label>Salles de bain:
                        <br>
                        <input type="radio" class="radio-box"  id= "sdb_tout" name="sdb" value="0"> <label for="sdb_tout">Tout&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_un" name="sdb" value="1"> <label for="sdb_un">1&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_deux" name="sdb" value="2"> <label for="sdb_deux">2&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_trois" name="sdb" value="3"> <label for="sdb_trois">3&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_quatre" name="sdb" value="4"> <label for="sdb_quatre">4&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_cinq" name="sdb" value="5"> <label for="sdb_cinq">5&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_six" name="sdb" value="6"> <label for="sdb_six">6&emsp;</label>
                        <input type="radio" class="radio-box" id= "sdb_sept" name="sdb" value="7"> <label for="sdb_sept">7</label>
                    </label>
                </div><br>

                <div class="equipements">
                    <label>Equipements:
                        <br>
                    <div class="piscine-spa">
                        <input type="checkbox" class="checkbox"  id= "piscine" name="equipement" value="Piscine privée"> <label for="piscine" id="piscine">Piscine privée&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                        <input type="checkbox" class="checkbox"  id= "spa_jacuzzi" name="equipement" value="Spa ou jacuzzi"> <label for="spa-jacuzzi">Spa ou jacuzzi</label>
                    </div>
                    <div class="vue-fitness"> 
                        <input type="checkbox" class="checkbox"  id= "vue_panoramique" name="equipement" value="Vue panoramique"> <label for="vue_panoramique">Vue panoramique&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                        <input type="checkbox" class="checkbox"  id= "fitness" name="equipement" value="Fitness ou salle de sport"> <label for="fitness">Fitness ou salle de sport</label><br>
                    </div>
                    <div class="plage-secu">
                    <input type="checkbox" class="checkbox"  id= "chauffeur" name="equipement" value="Chauffeur personnel"> <label for="chauffeur">Chauffeur personnel&emsp;&emsp;</label>
                        <input type="checkbox" class="checkbox"  id= "securite" name="equipement" value="Sécurité renforcée"> <label for="securite">Sécurité renforcée</label><br>
                    </div>    
                        
                    </label>
                </div>

                <div class="experiences_gastronomiques">
                    <label> 
                        <p class="exp"> Expérience gastronomiques<br><br>
                            Dîners préparés par des chefs renommés, des dégustations de vins haut de gamme, des cours de cuisine privés,...
                            <div class="switch">   
                                <input type="checkbox" class="toggle_button" id="exp_gastro" name="equipement" value="Expérience gastronomique">
                                <span class="slider-round"></span>
                           </div>  
                        </p>
                    </label>
                </div>
                <br><br>
                <div class="evenement_exclusifs">
                    <label>
                        <p class="eve"> Evènements exclusifs<br><br>
                            Accès privilégié à des événements exclusifs tels que des festivals, des soirées de gala, des spectacles,...   
                            <div class="switch-2">
                                <input type="checkbox" class="toggle_button" id="evt_exclusifs" name="equipement" value="Evenements exclusifs">
                                <span class="slider-round"></span>
                            </div>
                        </p>
                    </label>
                </div>

            <input type="submit" name="submit" value="Enregistrer" class="button">
    </form>

    </div>
    
</body>
</html>