<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------


// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}
$r = executeRequete("SELECT c.id_commande, c.id_membre, m.email, c.id_produit, DATE_FORMAT(p.date_arrivee, '%d-%m-%Y') AS date_arrivee, DATE_FORMAT(p.date_depart, '%d-%m-%Y') AS date_depart, p.prix, DATE_FORMAT(c.date_enregistrement, '%d-%m-%Y %H:%i') AS date_enregistrement FROM commande c, produit p, membre m WHERE m.id_membre = c.id_membre AND p.id_produit = C.id_produit");

// En-têtes en dur :
$c .= '<table class="table">';
	$c .= '<tr>';
		$c .= '<th>id commande</th>';
		$c .= '<th>id membre</th>';
		$c .= '<th>id produit</th>';
		$c .= '<th>prix</th>';
		$c .= '<th>date d\'enregistrement</th>';
		$c .= '<th>Actions</th>';
	$c .= '</tr>';
	
	//requête de selection pour affichage :

	$c .= '<h2>Gestion des Commandes</h2>';
	$c .=  "Nombre de Commandes : " . $r->rowCount();
	
	while ($details_commande = $r->fetch(PDO::FETCH_ASSOC)) {

		// $res = executeRequete("SELECT s.titre FROM produit p, salle s WHERE p.id_produit = :id_produit",array(
		// 				':id_produit' => '$details_commande[id_produit]'
		// 			));

		// 			$nom_salle = $res->fetch(PDO::FETCH_ASSOC);
		$c .= '<tr>';
			$c .= '<td>'. $details_commande['id_commande'] .'</td>';
			$c .= '<td>'. $details_commande['id_membre'] .' - '. $details_commande['email'] .'</td>';
			$c .= '<td>'. $details_commande['id_produit']/* .' - '. $nom_salle['titre']*/ .' - du '. $details_commande['date_arrivee'] .' au '. $details_commande['date_depart'] .'</td>';
			$c .= '<td>'. $details_commande['prix'] .'</td>';
			$c .= '<td>'. $details_commande['date_enregistrement'] .'</td>';
			$c .= '<td><a href="?action=supprimer&id_commande=' . $details_commande['id_commande'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette commande? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a></td>';
		$c .= '</tr>';
		//debug($res);
		//debug($details_commande);
		//debug($nom_salle);
	} 
$c .= '</table>';
// Affiche des données de la table commande


// Suppression de commande en BDD

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_commande'])){ // si on a action = supprimer dans l'URL (en GET) on attrape l'id
    // on fait la requête avec l'ID du GET
        executeRequete("DELETE FROM commande WHERE id_commande = :id_commande", array(':id_commande' => $_GET['id_commande']));
		$c .= '<div class="bg-success">Commande brillament supprimée!</div>';  
		
		header('location:gestionCommande.php');
		exit();
}


// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

