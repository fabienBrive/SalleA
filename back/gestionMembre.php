<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}

// Vérification du post et enregistrement du membre :
if (!empty($_POST)) { // Si le formulaire est posté, $_POST est remplie

	//Validation du formulaire :
	if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20) {
		// Si le pseudo n'existe pas, ou sa longeur est inférieur 4 char ou superieur a 20 char
		$c .= '<div class="bg-danger"> le pseudo doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 20) {
		$c .= '<div class="bg-danger"> le mot de passe doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['nom']) || strlen($_POST['nom']) < 4 || strlen($_POST['nom']) > 20) {
		$c .= '<div class="bg-danger"> le nom doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 3 || strlen($_POST['prenom']) > 20) {
		$c .= '<div class="bg-danger"> le prénom doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		// filter_var() permet ici de valider ici le format de type email : retourne true si c'est ok sinon false. Note : ici on verifie la négation qu'il ne s'agit pas d'un email (d'où le "!")
		$c .= '<div class="bg-danger">L\' Email est incorrect.</div>';
	}

	if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')) {
		$c .= '<div class="bg-danger">La civilite est incorrecte.</div>';
	}


	// Si pas d'erreur dans $contenu, on verifie l'unicité du pseudo en base de données puis on fait l'inscription :
	if (empty($c)){
		//si contenu est vide c'est qu'il n'y a pas d'erreur

		$membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo'])); // On fait cette requête pour vérifier la disponiblité du pseudo

		//debug($membre->rowCount());

		// Test pseudo
		if($membre->rowcount() > 0 && $_GET['action'] != 'modifier' ) { // si la requête retourne au moins une ligne c'est que le pseudo existe déjà mais si on est en mode modifier le pseudo sera déjà en base donc dans ce cas on accepte de le modifier
			
			$c .= '<div class="bg-danger">Pseudo indisponible, veuillez en choisir un autre !</div>';
		
		}else {
			// Sinon on peut inscrire le membre en BDD :
			$mdp = md5($_POST['mdp']); // si nous crypton le mot de passe avec la fonction prédéfinie md5(), il faudra egalement le faire sur la page de connexion pour comparer 2 mdp cryptés

			executeRequete(
			"REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())", 
				array(
					':id_membre'	=> $_POST['id_membre'],
					':pseudo' 		=> $_POST['pseudo'],
					':mdp' 			=> $mdp,
					':nom' 			=> $_POST['nom'],
					':prenom' 		=> $_POST['prenom'],
					':email' 		=> $_POST['email'],
					':civilite' 	=> $_POST['civilite'],
					':statut' 		=> $_POST['statut']
				)
			);

			$c .= '<div class="bg-success">Vous avez inscris un nouveau membre. <a href="../front/profil.php?id_membre=">Cliquez ici pour voir sa fiche profil</a></div>';
		}
	}
}// fin du if (!empty($_POST))


// 3- Suppression d'un membre :
if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_membre'])){ // suppression dans le GET
	// on ne peut pas supprimer son propre profil :
	if ($_SESSION['membre']['id_membre'] != $_GET['id_membre']) {
		executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
	} else {
		$c .= '<div class="bg-danger">Vous ne pouvez pas supprimer votre propre profil ! </div>';
	}
	
}



// 4 Modification du membre :

if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_membre'])){

	if ($_GET['id_membre'] != $_SESSION['membre']['id_membre']) {

		$r = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre",array(':id_membre' => $_GET['id_membre']));

		$membre_actuel = $r->fetch(PDO::FETCH_ASSOC);

	} else {
		$c .= '<div class="bg-danger">Vous ne pouvez pas modifier votre propre statut ! </div>';	
	}
}


// 2. Prépa de l'affichage de la table :

$r = executeRequete("SELECT id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, DATE_FORMAT(date_enregistrement, '%d-%m-%Y %H:%i') AS date_enregistrement FROM membre");
$c .= '<h2>Gestion des membres</h2>';
$c .=  "Nombre de membre(s) : " . $r->rowCount();

$c .=  '<table class="table"> 
			<tr>';
		// Affichage des entêtes :
		for($i = 0; $i < $r->columnCount(); $i++){
			
			$colonne = $r->getColumnMeta($i);

			if ( $colonne['name'] != 'mdp') $c .= '<th>' . $colonne['name'] . '</th>'; // pas d'affichage de la colonne mdp
		}
		
		$c .=  '<th> Actions </th>
			</tr>';

		// Affichage des lignes :
		while ($membre = $r->fetch(PDO::FETCH_ASSOC)){
			$c .=  '<tr>';
				foreach ($membre as $indice => $information){

					if ($indice != 'mdp') $c .=  '<td>' . $information . '</td>';
			
				}
				$c .= '<td>';
				$c .=  '<a href="../front/profil.php?action=voir&id_membre=' . $membre['id_membre'] . '&statut='. $membre['statut'] .'"><span><img src="../img/glyphicons/loupe.png" alt="loupe" title="Voir profil"></span></a>';
				
				$c .=  '<a href="?action=modifier&id_membre=' . $membre['id_membre'] . '" onclick="return(confirm(\' Etes-vous certain de vouloir modifier ce membre? Cela implique nécessairement de définir un nouveau mot de passe. \'));"><span><img src="../img/glyphicons/crayon.png" alt="crayon" title="modifier"></span></a>';
				
				$c .=  '<a href="?action=supprimer&id_membre=' . $membre['id_membre'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer ce membre? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a>';
				
				$c .=  '</td>';
				
			$c .=  '</tr>';
		}
$c .=  '</table>';
// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

?>
<form action="#" method="post">

	<div class="formulaireGauche col-md-6">
		<input type="hidden" id="id_membre" name="id_membre" value="<?php echo $membre_actuel['id_membre'] ?? 0; ?>" >

		<label for="pseudo">Pseudo</label><br>
		<input type="text" id="pseudo" name="pseudo" <?php 
		if (isset($membre_actuel['pseudo'])){
			echo 'value="'. $membre_actuel['pseudo'].'"'; 
		} else {
			echo 'placeholder="Pseudo du membre"';
		}?>><br><br>

		<label for="mdp">Mot de passe</label><br>
		<input type="password" id="mdp" name="mdp" placeholder="Mot de passe membre"><br><br>

		<label for="nom">Nom</label><br>
		<input type="text" id="nom" name="nom" <?php 
		if (isset($membre_actuel['nom'])){
			echo 'value="'. $membre_actuel['nom'].'"'; 
		} else {
			echo 'placeholder="Nom du membre"';
		}?>><br><br>

		<label for="prenom">Prenom</label><br>
		<input type="text" id="prenom" name="prenom" <?php 
		if (isset($membre_actuel['prenom'])){
			echo 'value="'. $membre_actuel['prenom'].'"'; 
		} else {
			echo 'placeholder="Prenom du membre"';
		}?>><br><br>
	</div>

	<div class="formulaireDroit col-md-6">
		<label for="email">Email</label><br>
		<input type="email" id="email" name="email" <?php 
		if (isset($membre_actuel['email'])){
			echo 'value="'. $membre_actuel['email'].'"'; 
		} else {
			echo 'placeholder="email du membre"';
		}?>><br><br>

		<label for="civilite">Civilité</label><br>
		<select name="civilite" id="civilite">
			<option value="m">Homme</option>
			<option value="f" 
			<?php if (isset($membre_actuel['civilite']) && $membre_actuel['civilite'] == 'f') echo'selected';?>
			>Femme</option>
		</select><br><br>

		<label for="statut">Statut</label><br>
		<select name="statut" id="statut">
			<option value="0">Membre</option>
			<option value="1" 
			<?php 
			if (isset($membre_actuel['statut']) && $membre_actuel['statut'] == '1') echo'selected';
			?>
			>Admin</option>
		</select><br><br>

		<input type="submit" class="btn" value="Enregistrer">
	</div>
</form>

<?php

require_once('../inc/bas.inc.php');

