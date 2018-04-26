<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1-on vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()) {
	header('location:../connexion.php');
	exit();
}

// Affichage des Avis insérés en base
$r = executeRequete("SELECT a.id_avis, a.id_membre, m.email, a.id_salle, s.titre, a.commentaire, a.note, DATE_FORMAT(a.date_enregistrement, '%d-%m-%Y %H:%i') AS date_enregistrement FROM avis a, salle s, membre m WHERE a.id_membre = m.id_membre AND a.id_salle = s.id_salle");

if($_POST){
  executeRequete("REPLACE INTO avis(id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_avis, :id_membre, :id_salle, :commentaire, :note, :date_enregistrement)", array(
                  ':id_avis'  => $_POST['id_avis'],
                  ':id_membre'  => $_POST['id_membre'],
                  ':id_salle'  => $_POST['id_salle'],
                  ':commentaire' => $_POST['commentaire'],
                  ':note'  => $_POST['note'],
                  ':date_enregistrement'  => $_POST['date_enregistrement']
  ));

  header('location:gestionAvis.php');
}

// préparation de l'affichage avec la variable $c
  $c .= '<tbody>';

    while ($avis = $r->fetch(PDO::FETCH_ASSOC)){
      
      $c .= '<tr>';
        $c .= '<td>'. $avis['id_avis'] .'</td>';
        $c .= '<td>'. $avis['id_membre'] .'-'. $avis['email'] .'</td>';
        $c .= '<td>'. $avis['id_salle'] .'-'. $avis['titre'] .'</td>';
        $c .= '<td>'. $avis['commentaire'] .'</td>';
        $c .= '<td>'. $avis['note'] .'</td>';
        $c .= '<td>'. $avis['date_enregistrement'] .'</td>';
        $c .= '<td><a href="?action=supprimer&id_avis=' . $avis['id_avis'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer ce commentaire? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a>|';

        $c .= '<a href="?action=modifier&id_avis=' . $avis['id_avis'] . '" onclick="return(confirm(\' Voulez vous modérer le contenu de ce commentaire? \'));"><span><img src="../img/glyphicons/crayon.png" alt="crayon" title="modifier"></span></a></td>';
      $c .= '</tr>';
    }

  $c .= '</tbody>';
$c .= '</table>';

// Suppression de commande en BDD

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_avis'])){ // si on a action = supprimer dans l'URL (en GET) on attrape l'id
    // on fait la requête avec l'ID du GET
        executeRequete("DELETE FROM avis WHERE id_avis = :id_avis", array(':id_avis' => $_GET['id_avis']));
		$c .= '<div class="bg-success">Avis brillament supprimée!</div>';  
		
		header('location:gestionAvis.php');
		exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_avis'])){
        $r = executeRequete("SELECT * FROM avis WHERE id_avis = :id_avis", array(':id_avis' => $_GET['id_avis']));
        
        $modifAvis = $r->fetch(PDO::FETCH_ASSOC); 


        $c .= '<form method="post">';
        $c .= '<label for="id_avis"></label>';
        $c .= '<input type="hidden" name="id_avis" id="id_avis" value="'. $modifAvis['id_avis'] .'"></input>';

        $c .= '<label for="id_membre"></label>';
        $c .= '<input type="hidden" name="id_membre" id="id_membre" value="'. $modifAvis['id_membre'] .'"></input>';

        $c .= '<label for="id_salle"></label>';
        $c .= '<input type="hidden" name="id_salle" id="id_salle" value="'. $modifAvis['id_salle'] .'"></input>';

        $c .= '<label for="commentaire"></label>';
        $c .= '<input type="hidden" name="commentaire" id="commentaire" value="'. $modifAvis['commentaire'] .'"></input>';

        $c .= '<label for="note"></label>';
        $c .= '<input type="hidden" name="note" id="note" value="'. $modifAvis['note'] .'"></input>';

        $c .= '<label for="date_enregistrement"></label>';
        $c .= '<input type="hidden" name="date_enregistrement" id="date_enregistrement" value="'. $modifAvis['date_enregistrement'] .'"></input>';

        $c .= '<label for="commentaire" >commentaire</label><br>';
        $c .= '<textarea id="commentaire" name="commentaire" rows="10" cols="70"> '. $modifAvis['commentaire'] .'</textarea><br><br>';
       
        $c .= '<input type="submit" value="Modifier"></input>';
        $c .= '</form>';

}







// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');


?>
<!-- En tête de ma table de présentation -->
<h2>Gestion des Avis</h2>
<table class="table">
  <thead>
    <tr>
      <th>id Avis</th>
      <th>id membre</th>
      <th>id salle</th>
      <th>Commentaire</th>
      <th>Note</th>
      <th>date d'enregistrement</th>
      <th>actions</th>
    </tr>
  </thead>
  
<?php echo $c; ?>





<!-- Notation mode étoile voir pour débuger le CSS -->
<!-- <div class="star-ratings-css">
  <div class="star-ratings-css-top" style="width: 84%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
</div> -->

<?php
require_once('../inc/bas.inc.php');

