<?php

require_once('inc/init.inc.php');

// 2 - Affichage des produits séléctionnés :

$categorie = true;
$ville = true;
$prixMin = true;
$prixMax = true;
$capacite = true;
$compteur = 0;
$date_arrivee = true;
$date_depart = true;
$date_jour = date("Y-m-d");

if(isset($_POST)) {

    if (isset($_POST['categorie'])) {
        $categorie = "categorie IN ('". implode("','", $_POST['categorie']) ."')";
    } 
    if (isset($_POST['ville'])) {
        $ville = "ville IN ('". implode("','", $_POST['ville']) ."')"; 
    } 


   if (isset($_POST['prix'][0])) { // 2 valeurs de prix avec mon range que je met dans un tableau grace a la fonction explode
        $prix = $_POST['prix'][0];
        //debug($_POST['prix'][0]);
        $prixMax = strstr($prix, ',');
        $prixMax = substr($prixMax, 1); 
        //debug($prixMax);
        $prixMin = strstr($prix, ',', TRUE);
        //debug($prixMin);
        $prixMin = 'prix >= '. $prixMin;
        $prixMax = 'prix <= '. $prixMax;
    }

    if (isset($_POST['capacite'])) {
        //$stringCapacite = explode(',', $_POST['capacite']);
        $capacite = 'capacite <= '. $_POST['capacite'][0];
        //debug($_POST['capacite']); 
    }
    if (isset($_POST['date_arrivee'])){ // Si date arrivée existe alors si elle est plus importante que la date du jour, on la prend en compte sinon elle vaut true
        if ($_POST['date_arrivee'] >= $date_jour){
            $date_arrivee = new DateTime($_POST['date_arrivee'][0]);
            $date_arrivee = $date_arrivee->format('Y-m-d');
            $date_arrivee = 'date_arrivee >= "'. $date_arrivee . '"';
            //debug($date_arrivee);
        } else {
            $date_arrivee = true;
            $c .= '<div class="bg-warning"> Attention vos dates sont incorrectes.</div>';
        }
    }
    if (isset($_POST['date_depart'])){
        if ($_POST['date_depart'] > $_POST['date_arrivee']){
            $date_depart = new DateTime($_POST['date_depart'][0]);
            $date_depart = $date_depart->format('Y-m-d');
            $date_depart = 'date_depart <= "'. $date_depart . '"';
            //debug($date_depart);
        } else {
            $date_depart = true;
            $c .= '<div class="bg-warning"> Attention vos dates sont incorrectes.</div>';
        }
    }

    //debug($date_arrivee);
    //debug($date_depart);
    // requête d'affichage pour fiche produit :
    $r = executeRequete("SELECT CURRENT_DATE AS date_jour, s.id_salle, s.titre, s.description, s.photo, s.ville, s.categorie, s.capacite, p.prix, p.date_arrivee, p.date_depart, p.id_produit, ROUND(AVG(a.note),2) AS note_moyenne FROM produit p LEFT JOIN salle s ON s.id_salle = p.id_salle LEFT JOIN avis a ON p.id_salle = a.id_salle WHERE p.etat = 'libre' AND $categorie AND $ville AND $prixMin AND $prixMax AND $capacite AND $date_arrivee AND $date_depart GROUP BY id_produit ");
   

    while($produit_boutique = $r->fetch(PDO::FETCH_ASSOC)){

        if($produit_boutique['date_arrivee'] >= $produit_boutique['date_jour']){ // affichage du produit à la condition que la date de d'arrivée du produit est supérieur ou égale à la date du jour

        //debug($noteSalle);
        $date_arrivee = new DateTime($produit_boutique['date_arrivee']);
        $date_arrivee = $date_arrivee->format('d-m-Y'); // je passe par un objet datetime() pour transformer mon affichage de la date en date timetamps  puis le changer de format ici le passer en format affichage (différent du format de la base de donnée)

        $date_depart = new DateTime($produit_boutique['date_depart']);
        $date_depart = $date_depart->format('d-m-Y');

        $c_droit .= '<div class="col-md-4" style="height:430px">';//div par produit
            $c_droit .= '<img src="'. $produit_boutique['photo'] .'" title="'. $produit_boutique['titre'] .'" alt="'. $produit_boutique['titre'] .'"height="175" width="250"><br>';//image
            $c_droit .= '<h3>'. $produit_boutique['titre'] .'</h3>';//titre
            $c_droit .= '<h4>'. $produit_boutique['prix'] .' €</h4>';//prix
            $c_droit .= '<p>'. substr($produit_boutique['description'], 0, 70) .'...</p>';//descriptif
            $c_droit .= '<p>'. $date_arrivee .' au '. $date_depart .'</p>';
            //période
         

            //Affichage de la note en étoiles :
            if(!empty($produit_boutique['note_moyenne'])){
            $c_droit .= '<div>
                            <div class="star-ratings-css">
                                <div class="star-ratings-css-top" style="width:'. $produit_boutique['note_moyenne'] * 20 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                                <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                            </div>
                        </div>
                        <br><br>';//note
                        
            }else{
            $c_droit .= '<p>Pas encore d\'avis sur cette salle</p><br>';
            
                }

            $c_droit .= '<a href="front/ficheProduit.php?id_produit='. $produit_boutique['id_produit'] .'" >Voir details</a></div>';//lien voir
            $c_droit .= '</div>';// Fin class=col-md-4
        

        $compteur++;
        }
    } // fin du while
} // fin affichage produit

echo $c_droit;

$c_droit .= '<p>'. $compteur .' résultats</p>';







?>