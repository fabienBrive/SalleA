<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}

//requête de selection pour affichage :
$r = executeRequete("SELECT c.id_commande, c.id_membre, m.email, c.id_produit, s.titre,DATE_FORMAT(p.date_arrivee, '%d-%m-%Y') AS date_arrivee,DATE_FORMAT(p.date_depart, '%d-%m-%Y') AS date_depart, p.prix, DATE_FORMAT(c.date_enregistrement, '%d-%m-%Y H%:i%') AS date_enregistrement FROM salle s, produit p, commande c, membre m WHERE s.id_salle = p.id_salle AND p.id_produit = c.id_produit AND c.id_membre = m.id_membre ");

while ($details_commande = $r->fetch(PDO::FETCH_ASSOC)) {
	debug($details_commande);
} 
// Affiche des données de la table commande

$c .= '';














// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

