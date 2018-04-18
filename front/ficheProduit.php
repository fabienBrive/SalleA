<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
$adresse = '';
$suggestion = '';


//****************Traitement pour affichage de la page produit******************  */

$r = executeRequete("SELECT p.id_produit, s.titre, s.description, s.photo, s.pays, s.ville, s.adresse, s.cp, s.capacite, s.categorie, DATE_FORMAT(p.date_arrivee, '%d-%m-%Y') AS date_arrivee, DATE_FORMAT(p.date_depart, '%d-%m-%Y') AS date_depart, p.prix FROM produit p, salle s WHERE s.id_salle = p.id_salle AND p.id_produit = :id_produit",array(':id_produit' => $_GET['id_produit']));


$ficheProduit = $r->fetch(PDO::FETCH_ASSOC);
//debug($ficheProduit);


$adresse = $ficheProduit['adresse'].' '.$ficheProduit['cp'].' '.$ficheProduit['ville'].' '.$ficheProduit['pays'];
$adresse = str_replace(' ','+',$adresse);
//debug($adresse);



//Requete pour affichage des produits similaires :
$res = executeRequete("SELECT p.id_produit, s.photo FROM salle s, produit p WHERE s.id_salle = p.id_salle AND s.ville = :ville AND p.id_produit NOT LIKE :id_produit ORDER BY RAND( ) LIMIT 4",
                array(
                    ':ville'        => $ficheProduit['ville'],
                    ':id_produit'   => $ficheProduit['id_produit'] 
                ));

// affichage des contenus via variable suggestion:
while ($p_similaire = $res->fetch(PDO::FETCH_ASSOC)){

 	//debug($resultat_suggestion);
 	$suggestion .= '<div class="col-md-3"> 
 					<a href="?id_produit='. $p_similaire['id_produit'] .'"><img src="../'. $p_similaire['photo'] .'"  class="img-responsive"></a>
 					</div>';
}

// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');
?>
<div><!-- Partie haute Affichage produit -->

    <div class="row"><!-- bandeau présentation titre + note + bouton resa -->
        <h2 class="col-md-4"><?php echo $ficheProduit['titre'] ?></h2>
        <div class="col-md-4">Note produit, en etoiles jquerry</div>
        <div class="col-md-4">
            <?php if (internauteEstConnecte()){
                echo'<button class="btn">Réserver</button>';
            }else{
                echo '<a href="connexion.php">Connectez Vous!</a>';
            }?>
        </div>
    </div><!-- .row -->

    <div class="row">
        <div class="col-md-8"><!-- photo -->
            <img src="<?php echo '../'.$ficheProduit['photo'];?>" alt="photo<?php echo $ficheProduit['titre'];?>" height="500" width="100%">
        </div>

        <div class="col-md-4"><!-- description -->
            <h4>Description</h4>
            <p><?php echo $ficheProduit['description'] ?></p>
        </div>

        <div class="col-md-4"><!-- Localisation -->
            <h4>Localisation</h4>
            <div >
            <iframe
                width="100%"
                height="300"
                frameborder="0" style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAiwCN8YWIOIpAuBw4zBhQ9FZbESd6h-rc 
                    &q=<?php echo $adresse ?>" allowfullscreen>
            </iframe>
            </div>
        </div>
    </div><!-- .row -->

    <div class="row"><!-- info complémentaires -->
        <h4>Informations complémentaires</h4>
        <div class="col-md-4">
            <p>Arrivée : <?php echo $ficheProduit['date_arrivee'] ?> <br>
                Depart : <?php echo $ficheProduit['date_depart'] ?></p> 
        </div>
        <div class="col-md-4">
            <p>capacite : <?php echo $ficheProduit['capacite'] ?> <br>
                categorie : <?php echo $ficheProduit['categorie'] ?></p>
        </div>
        <div class="col-md-4">
            <p>Adresse : <?php echo $ficheProduit['adresse'] .' '. $ficheProduit['cp'] .' '. $ficheProduit['ville'] ?> <br>
                prix : <?php echo $ficheProduit['prix'] ?> € </p>
        </div>
    </div><!-- .row -->
</div><!-- Fin Partie haute Affichage produit -->
<hr>
<div><!-- Partie basse Autres produit -->
    <h3>Autres Produits</h3>

    <?php echo $suggestion; ?>

</div><!-- Fin Partie basse Autres produit -->






<?php // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      //star rating jquerry antena.io notation page avis
      //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      //star rating css url: percentage star rating  codepens blue tide pro
      //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
require_once('../inc/bas.inc.php');

