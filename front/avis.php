<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

if (isset($_GET['id_produit'])) {
    $r = executeRequete("SELECT * FROM produit p, salle s WHERE p.id_produit = :id_produit", array(
            ':id_produit' => $_GET['id_produit']
    ));
    if (rowcount($r)) !== 1){
        $c .= '<p>un problème est survenu avec ce produit vous ne pouvez actuellement pas laisser d\'avis à son sujet</p>';

        $c .= '<a href="ficheProduit.php?id_produit='. $_GET['id_produit'] .'">Retour Produit</a>';
    } else {

    $detailsProduit = $r->fetch(PDO::FETCH_ASSOC);
    debug($detailsProduit);

    $c .= '<h2>Votre avis sur ce produit</h2>';

    $c .= '<p><span>Salle : </span>'. $detailsProduit['titre'] .'</p>';
    $c .= '<p><span>VIlle : </span>'. $detailsProduit['ville'] .'</p>';
    $c .= '<p><span>Categorie : </span>'. $detailsProduit['categorie'] .'</p>';
    $c .= '<p><span>Prix : </span>'. $detailsProduit['prix'] .'</p>';
    }

} else {
    header('location:index.php');
}













// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;
?>

<form action="" method="post">

<label for="id_membre"></label>
<input type="hidden" id="id_membre" name="id_membre" value="<?php $_SESSION['id_membre'] ?>">

<select id="example">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
</select>

<label for="commentaire">Commentaire</label>
<textarea name="commentaire" id="commentaire" cols="30" rows="10"></textarea>

</form>





<?php
require_once('../inc/bas.inc.php');

