<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}


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
				foreach ($salle as $indice => $information){

					if ($indice != 'mdp') $c .=  '<td>' . $information . '</td>';
			
				}
				$c .= '<td>';

				$c .=  '<a href="?action=modifier&id_salle=' . $salle['id_salle'] . '"><span><img src="../img/glyphicons/crayon.png" alt="crayon" title="modifier"></span></a>';
				
				$c .=  '<a href="?action=supprimer&id_salle=' . $salle['id_salle'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette salle? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a>';
				
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
		<input type="hidden" id="id_salle" name="id_salle" value="<?php echo $salle_actuel['id_salle'] ?? 0; ?>" >

		<label for="titre">Titre</label><br>
		<input type="text" id="titre" name="titre" <?php 
		if (isset($salle_actuel['titre'])){
			echo 'value="'. $salle_actuel['titre'].'"'; 
		} else {
			echo 'placeholder="Titre de la salle"';
		}?>><br><br>

		<label for="description">Description</label><br>
		<textarea id="description" name="description" rows="4" cols="40" <?php 
		if (isset($salle_actuel['description'])){
			echo 'value="'. $salle_actuel['description'].'"'; 
		} else {
			echo 'placeholder="Description de la salle"';
		}?>></textarea>
		<br><br>

		<label for="photo">Photo</label><br>
		<input type="file" id="photo" name="photo" <?php 
		if (isset($salle_actuel['photo'])){
			echo 'value="'. $salle_actuel['photo'].'"'; 
		} else {
			echo 'placeholder="Prenom du membre"';
		}?>><br><br>

		<label for="capacite">Capacité</label><br>
		<select id="capacite" name="capacite"> 
		<?php 
		for ($i = 1; $i * 5 <= 300; $i++ ){
			echo '<option value="'. $i*5 .'"';
			if (isset($salle_actuel['capacite']) && ($i*5 == $salle_actuel['capacite'])){
				echo 'selected'; 
			}
			echo '>'. $i * 5 .'</option>';
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
		<textarea id="adresse" name="adresse" rows="4" cols="40" <?php 
		if (isset($salle_actuel['adresse'])){
			echo 'value="'. $salle_actuel['adresse'].'"'; 
		} else {
			echo 'placeholder="Adresse de la salle"';
		}?>></textarea>
		<br><br>


		<input type="submit" class="btn" value="Enregistrer">
	</div>
</form>

<?php

require_once('../inc/bas.inc.php');


