<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

//****************Traitement pour affichage de la page produit******************  */

$r = executeRequete("SELECT s.titre, s.description, s.photo, s.pays, s.ville, s.adresse, s.cp, s.capacite, s.categorie, DATE_FORMAT(p.date_arrivee, '%d-%m-%Y') AS date_arrivee, DATE_FORMAT(p.date_depart, '%d-%m-%Y') AS date_depart, p.prix FROM produit p, salle s WHERE s.id_salle = p.id_salle AND p.id_produit = :id_produit",array(':id_produit' => $_GET['id_produit']));

$ficheProduit = $r->fetch(PDO::FETCH_ASSOC);
debug($ficheProduit);











// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');
?>
<div><!-- Partie haute Affichage produit -->

    <div><!-- bandeau présentation titre + note + bouton resa -->
        <h2><?php echo $ficheProduit['titre'] ?></h2>
        <div>Note produit, en etoiles jquerry</div>
        <button>ou lien si non connecté</button>
    </div>
    <div><!-- photo -->
        <img src="" alt="">
    </div>
    <div><!-- description -->
        <h4>Description</h4>
        <p><?php echo $ficheProduit['description'] ?></p>
    </div>
    <div><!-- Localisation -->
        <h4>Localisation</h4>
        <img src="" alt=""></img>
    </div>
    <div><!-- info complémentaires -->
        <h4>Informations complémentaires</h4>
        <div> <p>Arrivée : <?php echo $ficheProduit['date_arrivee'] ?> <br>
                Depart : <?php echo $ficheProduit['date_depart'] ?></p> </div>
        <div> <p>capacite : <?php echo $ficheProduit['capacite'] ?> <br>
                categorie : <?php echo $ficheProduit['categorie'] ?></p></div>
        <div> <p>Adresse : <?php echo $ficheProduit['adresse'] .' '. $ficheProduit['cp'] .' '. $ficheProduit['ville'] ?> <br>
                prix : <?php echo $ficheProduit['prix'] ?> € </p></div>
    </div>




</div><!-- Fin Partie haute Affichage produit -->

<div><!-- Partie basse Autres produit -->

    <a href="">
        <img src="" alt="">
    </a>

</div><!-- Fin Partie basse Autres produit -->






<?php
require_once('../inc/bas.inc.php');

