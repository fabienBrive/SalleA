<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) { // par sécurité on vérifie que l'internaute est bien admin
	header('location:../connexion.php');
	exit();
}


if (isset($_GET['stat']) && $_GET['stat'] == 'noteSalle'){
		$requete = 'SELECT s.titre, s.id_salle, ROUND(AVG(a.note),2) AS statistique FROM avis a LEFT JOIN salle s ON a.id_salle = s.id_salle GROUP BY a.id_salle ORDER BY statistique DESC LIMIT 0,5';
}
elseif (isset($_GET['stat']) && $_GET['stat'] == 'commandeSalle'){
		$requete = 'SELECT s.titre,s.id_salle, COUNT(s.id_salle) AS statistique FROM commande c, produit p, salle s WHERE c.id_produit = p.id_produit AND p.id_salle = s.id_salle GROUP BY s.id_salle ORDER BY statistique DESC LIMIT 0,5';
}
elseif (isset($_GET['stat']) && $_GET['stat'] == 'membreAchat'){
		$requete = 'SELECT m.prenom, m.nom, m.pseudo, m.id_membre, COUNT(c.id_membre) AS statistique FROM commande c, membre m WHERE c.id_membre = m.id_membre GROUP BY c.id_membre ORDER BY statistique DESC LIMIT 0,5';
}
elseif (isset($_GET['stat']) && $_GET['stat'] == 'notePrix'){
		$requete = 'SELECT m.prenom, m.nom, m.pseudo, m.id_membre, SUM(p.prix) AS statistique FROM commande c, membre m, produit p WHERE c.id_membre = m.id_membre AND c.id_produit = p.id_produit GROUP BY c.id_membre ORDER BY statistique DESC LIMIT 0,5';
}else{

	$c .= '<span>Choisissez une statiqtique à afficher.</span>';
}


$c .= '<ul>';


if (isset($_GET['stat']) && ($_GET['stat'] == 'noteSalle' || $_GET['stat'] == 'commandeSalle')){
	
	global $r;
	$r = ExecuteRequete($requete);

	while ($stat = $r->fetch(PDO::FETCH_ASSOC)){
		
		//debug($stat);
		$c .= '<li>'. $stat['id_salle'] .' - '. $stat['titre'] .' - '. $stat['statistique'] .'</li>';
		
	}
}elseif(isset($_GET['stat']) && ($_GET['stat'] == 'membreAchat' || $_GET['stat'] == 'notePrix')){

	global $r; 
	$r = ExecuteRequete($requete);

	while ($stat = $r->fetch(PDO::FETCH_ASSOC)){
		
		//debug($stat);
		$c .= '<li>'. $stat['id_membre'] .' - '. $stat['pseudo'] .' - '. $stat['statistique'] .'</li>';
	}
}

$c .= '</ul>';

/* 
Top 5 des salles les mieux notées :
"SELECT *, ROUND(AVG(note),2) AS note_moyenne FROM avis LEFT JOIN salle ON avis.id_salle = salle.id_salle GROUP BY avis.id_salle ORDER BY note_moyenne DESC LIMIT 0,5"

Top 5 des salles les plus commandées :
"SELECT s.titre,s.id_salle, COUNT(s.id_salle) AS nb_cmd_par_salle FROM commande c, produit p, salle s WHERE c.id_produit = p.id_produit AND p.id_salle = s.id_salle GROUP BY s.id_salle ORDER BY nb_cmd_par_salle DESC LIMIT 0,5"

Top 5 des membres qui achètent le plus :
"SELECT m.prenom, m.nom, m.pseudo, COUNT(c.id_membre) AS nb_cmd_par_membre FROM commande c, membre m WHERE c.id_membre = m.id_membre GROUP BY c.id_membre ORDER BY nb_cmd_par_membre DESC LIMIT 0,5"

Top 5 des membres qui achètent le plus cher :
"SELECT m.prenom, m.nom, m.pseudo, SUM(p.prix) AS valeur_de_cde_par_membre FROM commande c, membre m, produit p WHERE c.id_membre = m.id_membre AND c.id_produit = p.id_produit GROUP BY c.id_membre ORDER BY valeur_de_cde_par_membre DESC LIMIT 0,5"
*/



// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');
?>

<h2>Statistiques du site</h2>

<div>
	<ul>
		<li><a href="?stat=noteSalle">Top 5 des salles les mieux noté</a></li><br>
		<li><a href="?stat=commandeSalle">Top 5 des salles les plus commandées</a></li><br>
		<li><a href="?stat=membreAchat">Top 5 des membres qui achètent le plus (en terme de quantité) </a></li><br>
		<li><a href="?stat=notePrix">Top 5 des membres qui achètent le plus cher (en terme de prix)</a></li><br>
	</ul>
</div>

<?php
echo $c;

require_once('../inc/bas.inc.php');



