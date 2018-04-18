<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
	if (!internauteEstConnecteEtEstAdmin()) {
		header('location:../connexion.php');
		exit();
	}
	
	
	// Suppression de la salle + traitement photo :
	if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_salle'])){
		// Si les indices "action" et "id_produit", c'est que l'url est complète
		
		$resultat = executeRequete("SELECT photo FROM salle WHERE id_salle = :id_salle", array('id_salle'=> $_GET['id_salle']));
		
		if ($resultat->rowCount() == 1) {
			// Ici le produit existe
			$photo_a_supprimer = $resultat->fetch(PDO::FETCH_ASSOC);
			if (!empty($photo_a_supprimer['photo'])){
				// Si il y a une photo dans la bdd on peut la supprimer photo physique:

				$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . $photo_a_supprimer['photo'];
				// ce qui donne : c:/wamp64/www/PHP/08-site/photo/nomphoto.jpg
				
				if(file_exists($chemin_photo_a_supprimer)) unlink($chemin_photo_a_supprimer);
			}
			executeRequete("DELETE FROM salle WHERE id_salle = :id_salle", array(':id_salle' => $_GET['id_salle']));
			$c .= '<div class="bg-success">Produit supprimé !</div>';
		}
		
	}
	
	// 4 Modification de la salle :
	
	if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_salle'])){
	
			$r = executeRequete("SELECT * FROM salle WHERE id_salle = :id_salle",array(':id_salle' => $_GET['id_salle']));
	
			$salle_actuel = $r->fetch(PDO::FETCH_ASSOC);
	
	}
	
	//traitement du POST: 
	if (!empty($_POST)){
		
		if (!isset($_POST['titre']) || strlen($_POST['titre']) < 5 || strlen($_POST['titre']) > 200) {
			$c .= '<div class="bg-danger"> le titre doit contenir entre 5 et 200 caractères.</div>';
		}
		if (!isset($_POST['pays']) || strlen($_POST['pays']) < 4 || strlen($_POST['pays']) > 20) {
			$c .= '<div class="bg-danger"> le pays doit contenir entre 4 et 20 caractères.</div>';
		}
	
		if (!isset($_POST['ville']) || strlen($_POST['ville']) < 3 || strlen($_POST['ville']) > 20) {
			$c .= '<div class="bg-danger"> la ville doit contenir entre 4 et 20 caractères.</div>';
		}
	
		if (!isset($_POST['capacite']) || !preg_match('#^[0-9]{1,3}$#', $_POST['capacite'])) { // Expression rationnelle entre '#' débute par '^' et termine par '$' on admet les caratères chiffres de 0 à 9 ([0-9]) au nombre précis de 5 ({5}).
		// preg_match() retourne 1 = true si le code correspond à l'expression rationnelle sinon 0 = false.
			 $c .= '<div class="bg-danger"> Le code postal est incorrecte.</div>';
		}
	
		if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 4 || strlen($_POST['adresse']) > 50 ) { // on peut limiter ici a 50 charactères car la bdd en accepte 50.
			$c .= '<div class="bg-danger"> l\'adresse doit contenir entre 4 et 20 caractères.</div>';
		}
	
		if (!isset($_POST['cp']) || !preg_match('#^[0-9]{5}$#', $_POST['cp'])) { // Expression rationnelle entre '#' débute par '^' et termine par '$' on admet les caratères chiffres de 0 à 9 ([0-9]) au nombre précis de 5 ({5}).
		// preg_match() retourne 1 = true si le code correspond à l'expression rationnelle sinon 0 = false.
			 $c .= '<div class="bg-danger"> Le code postal est incorrecte.</div>';
		}

		// Déclaration de la variable en dehors de la condition
		$photo =''; 
		$photo_tmp = '';
		$photo_dur='';

		// 5-Traitement de la photo lors de la modif:
		if (isset($_GET['action']) && $_GET['action'] == 'modification') {
			// si je modifie le produit, je prend la photo actuelle que je remet en BDD :
			$photo_bdd = $_POST['photo_actuelle'];
		} 

		if(!empty($_FILES['photo']['name'])){ // traitement photo : création chemin copy photo physique de dossier tmp vers dossier site :

				$photo = 'img/' . $_POST['ville'] . '_' . $_FILES['photo']['name']; // On renomme la photo uploadée avec le nom de la ville pour limiter le risque de doublon dans les noms. On ajoute le chemin d'acces au dossier /img/.

				$photo_tmp = $_FILES['photo']['tmp_name']; // emplacement dans le fichier tmp du serveur (emplacement standard aprés upload)

				$photo_dur = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . $photo; // chemin final complet depuis c: jusqu'a img.

				copy($photo_tmp, $photo_dur);// bascule de la photo du fichier temporaire au fichier img.
		}
		
	
		// Si pas d'erreur dans $contenu, on verifie l'unicité du pseudo en base de données puis on fait l'inscription :
		if (empty($c)){
			//si contenu est vide c'est qu'il n'y a pas d'erreur
	
			$salle = executeRequete("SELECT * FROM salle WHERE titre = :titre", array(':titre' => $_POST['titre'])); // On fait cette requête pour vérifier la disponiblité du titre
	
			//debug($salle->rowCount());
	
			if(($salle->rowcount() === 0) || (($salle->rowcount() == 1) && (isset($_GET['action'])) && ($_GET['action'] == 'modifier'))) {
				//si la requête retourne une ligne c'est que le pseudo existe déjà, on autorise cela uniquement si on est en modification.
					
				executeRequete(
				"REPLACE INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:id_salle, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)", 
					array(
						':id_salle'			=> $_POST['id_salle'],
						':titre' 			=> $_POST['titre'],
						':description' 		=> $_POST['description'],
						':photo' 			=> $photo,
						':pays' 			=> $_POST['pays'],
						':ville' 			=> $_POST['ville'],
						':adresse' 			=> $_POST['adresse'],
						':cp' 				=> $_POST['cp'],
						':capacite' 		=> $_POST['capacite'],
						':categorie' 		=> $_POST['categorie']
					)
				);
	
				$c .= '<div class="bg-success">La salle a bien été enregistrée.</div>';
	
			} else {
				$c .= '<div class="bg-danger">Titre indisponible, veuillez en choisir un autre !</div>';
			} // fin rowcount()

		} // fin du if (empty($c)) 
	
	} // fin du if (!isset($_POST)) 
	

	
	
	// Préparation de l'affichage de la table:

$r = executeRequete("SELECT * FROM salle");
$c .= '<h2>Gestion des salles</h2>';
$c .= 'Nombre de salle(s) en BDD : ' . $r->rowCount();

$c .=  '<table class="table"> 
			<tr>';
		// Affichage des entêtes :
		for($i = 0; $i < $r->columnCount(); $i++){
			
			$colonne = $r->getColumnMeta($i);

			$c .= '<th>' . $colonne['name'] . '</th>'; // pas d'affichage de la colonne mdp
		}

		$c .=  '<th> Actions </th>
			</tr>';

		// Affichage des lignes :
		while ($salle = $r->fetch(PDO::FETCH_ASSOC)){
			$c .=  '<tr>';
				foreach ($salle as $indice => $info){

					if ($indice == 'mdp') {
			
					} elseif ($indice == 'photo') {
						$c .= '<td><img src="../'. $info .'" width="90" height="75"></td>';
					}else{
						$c .=  '<td>' . $info . '</td>';
					}
				}	
				$c .= '<td>';

				$c .=  '<a href="?action=modifier&id_salle=' . $salle['id_salle'] . '"><span><img src="../img/glyphicons/crayon.png" alt="crayon" title="modifier"></span></a>|';
				
				$c .=  '<a href="?action=supprimer&id_salle=' . $salle['id_salle'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette salle? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a>';
				
				$c .=  '</td>';
				
			$c .=  '</tr>';
		}
$c .=  '</table>';





// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

?>
<form action="#" method="post" enctype="multipart/form-data">

	<div class="formulaireGauche col-md-6">
		<input type="hidden" id="id_salle" name="id_salle" value="<?php echo $salle_actuel['id_salle'] ?? 0; ?>" >

		<label for="titre">Titre</label><br>
		<input type="text" id="titre" name="titre" <?php 
		if (isset($salle_actuel['titre'])){
			echo 'value="'. $salle_actuel['titre'].'"'; 
		} else {
			echo 'placeholder="Titre de la salle"';
		}?>><br><br>

		<label for="description">Description</label><br>
		<textarea id="description" name="description" rows="4" cols="40"> <?php 
		if (isset($salle_actuel['description'])){
			echo $salle_actuel['description']; 
		} else {
			echo 'Description de la salle';
		}?></textarea>
		<br><br>

		<label for="photo">Photo</label><br>
		<input type="file" id="photo" name="photo"><br><br>
		<?php 
			if (isset($salle_actuel['photo'])) {
				// En cas de modification on affiche la photo actuellement en BDD :
			echo '<i>Vous pouvez uploader une nouvelle photo.</i><br>';
			echo '<p>Photo actuelle : </p>';
			echo '<img src="../'. $salle_actuel['photo'] .'" width="90" height="90"><br>';
			echo '<input type="hidden" name="photo_actuelle" value="'. $salle_actuel['photo'] .'"><br>';// Renseigne $_POST['photo_actuelle'] qui remplace en BDD l'ancienne photo
			}
			 ?>

		<label for="capacite">Capacité</label><br>
		<select id="capacite" name="capacite"> 
		<?php 
		for ($i = 1; $i <= 125; $i++ ){
			echo '<option value="'. $i .'"';
			if (isset($salle_actuel['capacite']) && ($i == $salle_actuel['capacite'])){
				echo 'selected'; 
			}
			echo '>'. $i .'</option>';
		}?>
		</select>
		<br><br>

		<label for="categorie">Catégorie</label><br>
		<select id="categorie" name="categorie"> 

			<option value="réunion">Réunion</option>

			<option value="bureau" 
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['categorie'] == 'bureau') echo 'selected'; ?> 
			>Bureau</option>

			<option value="formation" 
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['categorie'] == 'formation') echo 'selected'; ?> 
			>Formation</option>

		</select><br><br>


	</div>

	<div class="formulaireDroit col-md-6">

		<label for="pays">Pays</label><br>
		<select id="pays" name="pays"> 

			<option>France</option>

		</select><br><br>

	<label for="ville">Ville</label><br>
		<select id="ville" name="ville"> 

			<option>Paris</option>

			<option
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['ville'] == 'Lyon') echo 'selected'; ?> 
			>Lyon</option>

			<option
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['ville'] == 'Marseille') echo 'selected'; ?> 
			>Marseille</option>

			<option
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['ville'] == 'Bordeaux') echo 'selected'; ?> 
			>Bordeaux</option>

			<option
			<?php if (isset($salle_actuel['categorie']) && $salle_actuel['ville'] == 'Toulouse') echo 'selected'; ?> 
			>Toulouse</option>

		</select><br><br>


		<label for="adresse">Adresse</label><br>
		<textarea id="adresse" name="adresse" rows="4" cols="40"> <?php 
		if (isset($salle_actuel['adresse'])){
			echo $salle_actuel['adresse']; 
		} else {
			echo 'Adresse de la salle';
		}?></textarea>
		<br><br>


		<label for="cp">Code postal</label><br>
	<input type="text" id="cp" name="cp" <?php 
		if (isset($salle_actuel['cp'])){
			echo 'value="'. $salle_actuel['cp'].'"'; 
		} else {
			echo 'placeholder="Code Postal de la salle"';
		}?>><br><br>


		<br><input type="submit" class="btn" value="Enregistrer">
	</div>
</form>

<?php

require_once('../inc/bas.inc.php');


