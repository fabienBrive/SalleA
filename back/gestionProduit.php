<?php 

require_once('../inc/init.inc.php');

//////////////////////////////////////TRAITEMENT /////////////////////////////////////////////


// ********************* on vérifie que le membre est admin :************************************
if (!internauteEstConnecteEtEstAdmin()) { // par sécurité on vérifie que l'internaute est bien admin
	header('location:../connexion.php');
	exit();
}


// **************SUPPRESSION DU PRODUIT EN BDD ********************:

if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_produit'])){ // si on a action = suppression dans l'URL (en GET) on attrape l'id
	// on fait la requête avec l'ID du GET
	$r = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit'=>$_GET['id_produit']));
    // Si il y a bien une ligne on la delete sinon message erreur.
	if ($r->rowCount() == 1) {
		// Ici le produit existe
		$supprime = $r->fetch(PDO::FETCH_ASSOC); // Pas de boucle car je suis sur de n'avoir qu'un seul produit (selection par l'id)

		executeRequete("DELETE FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
		$c .= '<div class="bg-success">Produit supprimé !</div>';
	} 
	else {
			// ici le produit n'existe pas
			$c .='<div class="bg-danger">Produit inexistant !</div>';
	}
	$_GET['action'] = 'affichage'; // afficher automatiquement le tableau des produits aprés suppression
}


// *************** traitement du formulaire (affichage en HTML dans la partie d'affichage) *****************************

if ($_POST){ // if seul remplace le if(isset()) car renvoie lui aussi true ou false en fonction de l'existence de $_POST
    // On entre donc dans cette condition que si le formulaire a été posté

//  !!!!!!!!!!!!!!!!!!!!   Sécurisation du post a terminer pour dates et selecteur dynamique !!!!!!!!!!!!!!!!!!!!!!!!!!!!
    if(isset($_POST['date_arrivee'])){
        $date_arrivee = new DateTime($_POST['date_arrivee']);
        $date_arrivee = $date_arrivee->format('Y-m-d');
    }
    if (isset($_POST['date_depart'])) {
        $date_depart = new DateTime($_POST['date_depart']);
        $date_depart = $date_depart->format('Y-m-d');
    }

    if (!isset($_POST['prix']) || !preg_match('#^[0-9]{1,3}$#', $_POST['prix'])) { // expression rationelle je ne veut pour le prix que des chiffres et j'en veux entre 1 et 3. 
	 	$c .= '<div class="bg-danger"> Le prix est incorrecte.</div>';
	}
    
    if (empty($c)){
    
        // Si pas d'erreur on envoie les données du POST en BDD :
        executeRequete(
                "REPLACE INTO produit (id_produit, id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_produit, :id_salle, :date_arrivee, :date_depart, :prix, 'libre' )", 
                    array(
                        ':id_produit'       => $_POST['id_produit'],
                        ':id_salle' 		=> $_POST['id_salle'],
                        ':date_arrivee' 	=> $date_arrivee,
                        ':date_depart' 		=> $date_depart,
                        ':prix' 		    => $_POST['prix'],
                    ));
    }  // !!!!!!!!!!!!!! En modification le produit redeviens automatiquement libre attention a voir control de cohérence !!!!!!!!!!!

} // fin du if ($_POST)


// ****************** table des produits envoyés dans $c pour affichage : ************************

$r = executeRequete("SELECT * FROM produit"); // requête pour selectionner toutes les données de produit en base et les afficher dans un tableau

$c .= '<h2>Gestion des Produits</h2>
            <p>Il y a actuellement ' . $r->rowcount() . ' produit en base de donnée.</p>' ;

// tableau affichage produit :
$c .= '<table class="table table-dark">';

//affichage des en-têtes :
    $c .='<tr>';
			for($i=0; $i < $r->columnCount(); $i++){
				$colonne = $r->getColumnMeta($i);
                $c .= '<th scope="col">' . $colonne['name'] . '</th>'; 
			}
			$c .= '<th scope="col">Actions</th>';
    $c .='</tr>';
    
// Affichage des lignes :
    while($ligne = $r->fetch(PDO::FETCH_ASSOC)){ // on transforme le resultat de la requête (objet PDOStatement) en un array que l'on parcours ligne par ligne avec la boucle while
        $c .= '<tr>';
            foreach ($ligne as $indice => $info){
                if ($indice == 'id_produit'){
						$c .= '<th scope="col">'. $info .'</th>';
					} else {
                        $c .= '<td>' . $info . '</td>';
                }
            }
            // pour chaque ligne de produit 
            $c .= '<td>
                        
                        <a href="../front/ficheProduit.php?action=voir&id_produit='. $ligne['id_produit'] .'"> Voir </a>
                        |
                        <a href="?action=suppression&id_produit='. $ligne['id_produit'] .'" onclick="return(confirm(\' Etes-vous certain de vouloir supprimer ce produit ? \'))"> supprimer </a>
                        |
                        <a href="?action=modification&id_produit='. $ligne['id_produit'] .'"> modifier </a>

                    </td>';

        $c .= '</tr>';
    }                    

$c .= '</table>';



/////////////////////// AFFICHAGE ///////////////////////////

require_once('../inc/haut.inc.php');

echo $c;
//debug ($_POST);
//debug($produit_actuel);


// ********* Remplissage des champs en cas de Modification **************
if (isset($_GET['action']) && $_GET['action'] == 'modification' && isset($_GET['id_produit'])){ // Si j'ai l'action = modification dans le GET alors ...


	$r = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
			
    $produit_actuel = $r->fetch(PDO::FETCH_ASSOC); // pas de while car un seul produit
}

// ********************Affichage du formulaire **********************
?>

<form method="post" action="#"> 

    <!-- on insère en iden l'id du produit bien que autoincrémenté et non modifiable pour le récupérer dans le post et traiter les infos aprés -->
    
    <input type="hidden" id="id_produit" name="id_produit" value="<?php echo $produit_actuel['id_produit'] ?? ''; ?>">
            <?php 
            debug($_POST); 
            ?>
    <label for="date_arrivee">Date d'arrivée</label><br>
    <input type="text" class="datepicker" name="date_arrivee"  value="<?php echo $produit_actuel['date_arrivee'] ?? ''; ?>"><br><br>
            <?php 
            debug($date_arrivee); 
            ?>
    <label for="date_depart">Date de départ</label><br>
    <input type="text" class="datepicker" name="date_depart"  value="<?php echo $produit_actuel['date_depart'] ?? ''; ?>"><br><br>
            <?php debug($date_depart); ?>
    <label for="id_salle">Salle</label><br>
    <select name="id_salle">
        <?php 
        $r = executeRequete("SELECT * FROM salle");

        while ( $salle = $r->fetch(PDO::FETCH_ASSOC) ){
            echo '<option value="'. $salle['id_salle'] .'">'. $salle['id_salle'] .' - '. $salle['titre'] .' - '. $salle['adresse'] .' '. $salle['cp'] .' '. $salle['ville'] .' - '. $salle['capacite'] .' pers </option>';
        }
        ?>
    </select><br><br>

    <label for="prix">Tarif</label><br>
    <input type="text" id="prix" name="prix" value="<?php echo $produit_actuel['prix'] ?? ''?>"><br><br>

    <input type="submit" class="btn btn-outline-dark" value="Enregistrer">

</form>
<?php

require_once('../inc/bas.inc.php');


