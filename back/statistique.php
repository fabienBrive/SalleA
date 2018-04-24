<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) { // par sécurité on vérifie que l'internaute est bien admin
	header('location:../connexion.php');
	exit();
}


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

echo $contenu;

require_once('../inc/bas.inc.php');



