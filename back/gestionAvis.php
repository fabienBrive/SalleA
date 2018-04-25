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




while ($avis = $r->fetch(PDO::FETCH_ASSOC)){
  $c .= '<tbody>';
    $c .= '<tr>';
      $c .= '<td>'. $avis['id_avis'] .'</td>';
      $c .= '<td>'. $avis['id_membre'] .'-'. $avis['email'] .'</td>';
      $c .= '<td>'. $avis['id_salle'] .'-'. $avis['titre'] .'</td>';
      $c .= '<td>'. $avis['commentaire'] .'</td>';
      $c .= '<td>'. $avis['note'] .'</td>';
      $c .= '<td>'. $avis['date_enregistrement'] .'</td>';
      $c .= '<td><a href="?action=supprimer&id_membre=' . $avis['id_avis'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer ce membre? \'));"><span><img src="../img/glyphicons/poubelle.png" alt="poubelle" title="supprimer"></span></a>|';

      $c .= '<a href="?action=modifier&id_membre=' . $avis['id_avis'] . '" onclick="return(confirm(\' Etes-vous certain de vouloir modifier ce membre? Cela implique nécessairement de définir un nouveau mot de passe. \'));"><span><img src="../img/glyphicons/crayon.png" alt="crayon" title="modifier"></span></a></td>';
    $c .= '</tr>';
  $c .= '</tbody>';


}









// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');


?>

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

</table>
<?php
require_once('../inc/bas.inc.php');

