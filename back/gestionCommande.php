<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}














// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $contenu;

require_once('../inc/bas.inc.php');

