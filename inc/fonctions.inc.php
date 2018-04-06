<?php 

function debug($var){
	echo '<div style="border: 1px solid orange">';
		echo '<pre>'; print_r($var); echo '</pre>';
	echo '</div>';
}

//---------------------------------------------------
// Fonctions liées au membre :

// Fonction pour déterminer si un membre est connecté
function internauteEstConnecte() {
	return (isset($_SESSION['membre']));
 }
	


// Fonction pour déterminer si un membre est connecté et qu'il est administrateur :
function internauteEstConnecteEtEstAdmin(){
	return (internauteEstConnecte() && $_SESSION['membre']['statut'] == 1);
}


//----------------------------------------------------
// Fonction pour executer des requêtes :

function executeRequete($req, $param = array()) {
	if (!empty($param)) {
	
		// si j'ai recu des valeurs associées aux marqueurs, je fais un htmlspecialchars pour les échapper = convertir les caractères spéciaux en entité HTML
		foreach ($param as $indice => $valeur) {
			$param[$indice] = htmlspecialchars($valeur, ENT_QUOTES); // On prend la valeur de $param que l'on traite par htmlspecialchars et que l'on remet à son indice, c'est-à-dire exactemant à la même place). Permet d'éviter les injections XSS et CSS.
		}

	}
	global $pdo; // Permet d'avoir acces a la variable $pdo défni dans l'espace global, à l'intérieur de l'espace local de la fonction executeRequete

	$r = $pdo->prepare($req); // on prépare la resuête reçue en argument

	$r->execute($param); // On execute la requête fournie en passant l'array $param qui associe les marqueurs aux variables

	return $r; // on retourne l'objet PDOStatement à l'endroit où la fonction executeRequete est appelé (utile aux SELECT)
}

function compareDate($date1, $date2){
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$interval = $datetime1->diff($datetime2);
	return $interval->format('%R%a days');
}
