<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------


// Si il y a id_produit en GET
if (isset($_GET['id_produit'])) { // Je vais chercher les infos du produit en base pour réaliser un affichages des caractèristiques du produit
    $r = executeRequete("SELECT * FROM produit p, salle s WHERE p.id_salle = s.id_salle AND p.id_produit = :id_produit", array(
            ':id_produit' => $_GET['id_produit']
    ));
    //debug($r->rowcount());
    if ($r->rowcount() != 1){ // si la base renvoie plus ou moins d'une ligne de résultat c'est qu'il y a un problème j'affiche donc un message d'erreur
        $c .= '<p>un problème est survenu avec ce produit vous ne pouvez actuellement pas laisser d\'avis à son sujet</p>';

        $c .= '<a href="ficheProduit.php?id_produit='. $_GET['id_produit'] .'">Retour Produit</a>';
    } else { // je fais mon fetch et affiche les infos souhaitées

    $detailsProduit = $r->fetch(PDO::FETCH_ASSOC);
    //debug($detailsProduit);

    $c .= '<h2>Votre avis sur cette salle</h2>';

    $c .= '<p><span>Salle : </span>'. $detailsProduit['titre'] .'</p>';
    $c .= '<p><span>Ville : </span>'. $detailsProduit['ville'] .'</p>';
    $c .= '<p><span>Catégorie : </span>'. $detailsProduit['categorie'] .'</p>';
    $c .= '<p><span>Prix : </span>'. $detailsProduit['prix'] .' €</p>';
    $c .= '<img src="../'. $detailsProduit['photo'] .'" alt="photo salle">';
    }

} else {
    header('location:../index.php'); // si pas d'id_produit en GET je suis redirigé vers l'index
}


if ($_POST){ // Si mon post est rempli j'envoie les produits en BDD

    //!!!!!!!!!!!!!!!!!! Controle des champs du $_POST a faire !!!!!!!!!!!!!!!!!!!!!

    debug($_POST);
    $r = executeRequete("INSERT INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :commentaire, :note, NOW())",array(
                         ':id_membre'       =>  $_SESSION['membre']['id_membre'],
                         ':id_salle'        =>  $detailsProduit['id_salle'],
                         ':commentaire'     =>  $_POST['commentaire'],
                         ':note'            =>  $_POST['note']
    ));

    header('location:../index.php');
}










// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c; // aprés mon affichage des caractéristiques produit j'affiche mon formulaire de commentaire (avec note étoile)
?>
<div class="col-md-12">
    <h3>Formulaire</h3>
    <form method="post">

        <label for="id_membre"></label>
        <input type="hidden" id="id_membre" name="id_membre" value="<?php echo $_SESSION['membre']['id_membre'] ?>">

        <label for="note">Note du produit</label><br>
            <select name="note" id="note">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br>

        <label for="commentaire">Commentaire</label><br>
        <textarea name="commentaire" id="commentaire" cols="60" rows="5" placeholder="Ici votre commentaire"></textarea><br><br>

        <input type="submit" value="envoyer">

    </form>
</div>


<?php
require_once('../inc/bas.inc.php');

