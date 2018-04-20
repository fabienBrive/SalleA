<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}

// En-têtes en dur :
$c .= '<table>';
	$c .= '<th>id commande</th>';
	$c .= '<th>id membre</th>';
	$c .= '<th>id produit</th>';
	$c .= '<th>prix</th>';
	$c .= '<th>date d\'enregistrement</th>';
	$c .= '<th>Actions</th>';
$c .= '</table>';

//requête de selection pour affichage :
$r = executeRequete("SELECT * FROM commande");

while ($details_commande = $r->fetch(PDO::FETCH_ASSOC)) {
	debug($details_commande);
} 
// Affiche des données de la table commande


















// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

