<?php 

require_once('inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------
// 1 - Affichage des catégories de produit


 // Selecteur de catégorie :
    $c_gauche .= '<label for="categorie"><h3>Catégorie</h3></label><br>';

$r = executeRequete("SELECT DISTINCT categorie FROM salle"); 
    while ($salle = $r->fetch(PDO::FETCH_ASSOC)){
        $c_gauche .= '<input type="checkbox" name="categorie[]" value="'. $salle['categorie'] .'"> '.$salle['categorie'];
        $c_gauche .= '<br><br>';
    }
    
// Selecteur de ville :
    $c_gauche .= '<label for="ville"><h3>Ville</h3></label><br>';

$r = executeRequete("SELECT DISTINCT ville FROM salle");
    while ($salle = $r->fetch(PDO::FETCH_ASSOC)){
        $c_gauche .= '<input type="checkbox" name="ville[]" value="'. $salle['ville'] .'"> '.$salle['ville']; 
        $c_gauche .= '<br><br>';
    }

// Selecteur capacité

    $c_gauche .= '<label for="capacite"><h3>Capacité</h3></label><br>';
    $c_gauche .= '<select name="capacite[]">';
for ($i = 25; $i > 0; $i-- ) {
        $c_gauche .= '<option>'. $i*10 .'</option>';
}
    $c_gauche .= '</select><br><br>';

// Selecteur de prix 
    $c_gauche .= '<label for="prix"><h3>Prix</h3></label><br><br>';
    $c_gauche .= '<input type="hidden" class="input" name="prix[]" value="1000" /><br><br>';

// Selecetion période :
    $c_gauche .= '<h3>Période</h3><br>';

        $c_gauche .= '<label for="date_depart"><h4>Date d\'arrivée</h4></label><br>';
        $c_gauche .= '<input type="text" class="datepicker" name="date_arrivee[]" id="date_arrivee" placeholder="date d\'arrivée"><br><br>';

        $c_gauche .= '<label for="date_arrivee"><h4>Date de départ</h4></label><br>';
        $c_gauche .= '<input type="text" class="datepicker" name="date_depart[]" id="date_depart" placeholder="date de départ"><br><br>';

    //$c_gauche .= '<input type="submit" value="Rechercher" class="btn"><br><br>';



// ----------------------- AFFICHAGE ----------------------------------------

require_once('inc/haut.inc.php');
echo $c;

?>
<form class="col-md-3" method="post" action="#">
<?php 
echo $c_gauche;
?>
</form>    
<div class="col-md-9" id="selection">
<?php 
echo $c_droit;
?>
</div>
<a href="">Voir plus</a>


<script>
    $(function(){

    // 3 - fonction callback
        function reponse(retourPHP) {
            $("#selection").html(retourPHP); // On affiche le HTML envoyé en réponse par le serveur
        }

    // 1 - fonction d'envoie de la requête au serveur en AJAX :
        function envoi_ajax() {
            var donnees = $('form').serialize(); // transforme les données du formulaire en string avant envoi vers le serveur en AJAX, string formaté pour pouvoir remplir l'array $_POST automatiquement.

            $.post('selectionAjax.php', donnees, reponse, 'html');
            /* idem que pour $.get() il y a 4 arguments :
                    - url de destination
                    - données envoyées (objet OU string)
                    - callback de traitement de la réponse serveur,
                    - format de retour = on atttend du HTML */
        }

    // 2 - appel de notre fonction :
        envoi_ajax(); // pour afficher tout de suite tous les produits disponibles
        $("form").change(envoi_ajax); // Si les valeurs du formulaire changent, on appelle de nouveau la fonction pour mettre a jour la selection

    }); // Fin du document ready 
</script>

<?php

require_once('inc/bas.inc.php');

