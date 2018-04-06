<?php 

require_once('inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1 - Affichage des catégories de produit


 // Selecteur de catégorie :
    $c_gauche .= '<label for="categorie"><h3>Catégorie</h3></label><br>';

$r = executeRequete("SELECT DISTINCT categorie FROM salle"); 
    while ($salle = $r->fetch(PDO::FETCH_ASSOC)){
        $c_gauche .= '<input type="checkbox" name="categorie[]" value="'. $salle['categorie'] .'"> '.$salle['categorie'];
        $c_gauche .= '<br><br>';
    }
    
// Selecteur de ville :
    $c_gauche .= '<label for="ville"><h3>Ville</h3></label><br>';

$r = executeRequete("SELECT DISTINCT ville FROM salle");
    while ($salle = $r->fetch(PDO::FETCH_ASSOC)){
        $c_gauche .= '<input type="checkbox" name="ville[]" value="'. $salle['ville'] .'"> '.$salle['ville']; 
        $c_gauche .= '<br><br>';
    }

// Selecteur capacité

    $c_gauche .= '<label for="capacite"><h3>Capacité</h3></label><br>';
    $c_gauche .= '<select name="capacite">';
for ($i = 10; $i > 0; $i-- ) {
        $c_gauche .= '<option>'. $i*100 .'</option>';
}
    $c_gauche .= '</select><br><br>';

// Selecteur de prix 
    $c_gauche .= '<label><h3>Prix</h3></label><br><br>';
    $c_gauche .= '<input type="hidden" class="input" name="prix" value="1000" /><br><br>';

// Selecetion période :
    $c_gauche .= '<h3>Période</h3><br>';

        $c_gauche .= '<label><h4>Date d\'arrivée</h4></label><br>';
        $c_gauche .= '<input type="text" class="datepicker" name="date_arrivee" id="date_arrivee"><br><br>';
        
        $c_gauche .= '<label><h4>Date de départ</h4></label><br>';
        $c_gauche .= '<input type="text" class="datepicker" name="date_depart" id="date_depart"><br><br>';

    $c_gauche .= '<input type="submit" value="Rechercher" class="btn"><br><br>';

// 2 - Affichage des produits séléctionnés :

$categorie = true;
$ville = true;
$prixMin = true;
$prixMax = true;
$capacite = true;
$compteur = 0;

if(!empty($_POST)) {

    if (isset($_POST['categorie'])) {
        $categorie = "categorie IN ('". implode("','", $_POST['categorie']) ."')";
    } 
    if (isset($_POST['ville'])) {
        $ville = "ville IN ('". implode("','", $_POST['ville']) ."')"; 
    } 
    if (isset($_POST['prix'])) { // 2 valeurs de prix avec mon range que je met dans un tableau grace a la fonction explode
        $prix = explode(',', $_POST['prix']);
        $prixMin = 'prix >= '. $prix[0];
        $prixMax = 'prix <= '. $prix[1];
    }
    if (isset($_POST['capacite'])) {
        $capacite = 'capacite <= '. $_POST['capacite'];
    }
    if (isset($_POST['date_arrivee'])) {  
        $date_arrivee = new DateTime($_POST['date_arrivee']);
        $date_arrivee = $date_arrivee->format('Y-m-d'); // je met la date recu par el
        $date_arrivee = 'date_arrivee >= "'. $date_arrivee . '"';
    }
    if (isset($_POST['date_depart'])) {
        $date_depart = new DateTime($_POST['date_depart']);
        $date_depart = $date_depart->format('Y-m-d');
        $date_depart = 'date_depart <= "'. $date_depart . '"';
    }

    //debug($date_arrivee);
    //debug($date_depart);
    
    $r = executeRequete("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND $categorie AND $ville AND $prixMin AND $prixMax AND $capacite AND $date_depart AND $date_arrivee");
   
    //debug($r);


    while($produit_boutique = $r->fetch(PDO::FETCH_ASSOC)){

      $date_arrivee = new DateTime($produit_boutique['date_arrivee']);
      $date_arrivee = $date_arrivee->format('d-m-Y'); // je passe par un objet datetime() pour transformer mon affichage de la date en date timetamps  puis le changer de format ici le passer en format affichage (différent du format de la base de donnée)

      $date_depart = new DateTime($produit_boutique['date_depart']);
      $date_depart = $date_depart->format('d-m-Y');

    $c_droit .= '<div class="col-md-4">';//div par produit
        $c_droit .= '<img src="'. $produit_boutique['photo'] .'" title="'. $produit_boutique['titre'] .'" alt="'. $produit_boutique['titre'] .'"><br>';//image
        $c_droit .= '<h3>'. $produit_boutique['titre'] .'</h3>';//titre
        $c_droit .= '<h4>'. $produit_boutique['prix'] .' €</h4>';//prix
        $c_droit .= '<p>'. $produit_boutique['description'] .'</p>';//descriptif
        $c_droit .= '<p>'. $date_arrivee .' au '. $date_depart .'</p><br>';//période
        $c_droit .= '<div></div><br>';//note
        $c_droit .= '<a href="?id_produit='. $produit_boutique['id_produit'] .'" >Voir details</a><br><br>';//lien voir
    $c_droit .= '</div>';

    $compteur++;
    }
} // fin affichage produit

$c_gauche .= '<p>'. $compteur .' résultats</p>';

// ----------------------- AFFICHAGE ----------------------------------------

require_once('inc/haut.inc.php');

echo $c;

?>
<form class="col-md-3" method="post" action="#">
<?php 
echo $c_gauche;
?>
</form>    
<div class="col-md-9">
<?php 
echo $c_droit;
?>
</div>
<a href="">Voir plus</a>


<?php

require_once('inc/bas.inc.php');

