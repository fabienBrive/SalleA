<?php 

require_once( '../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------


// 2 - deconnexion de l'internaute

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy(); // si le lien deconnexion est cliqué -> 'deconnexion' passe en GET alors on détruit la session EN FIN DE SCRIPT
}


// 3- Verif connection :

if(internauteEstConnecte()){
    // A ce moment du script la session n'etant pas encore détruite l'internaute est toujours connecté on le fait donc sortir de la page via header location direction profil -> la session sera alors détruite il ne pourra donc pas accéder a son profil et sera redirigé vers cette page de connexion
    
    header('location:profil.php'); // renvoi vers profil
    exit(); // on sort du script et on détruit la session
}


// 1 - traitement du formulaire

if ($_POST){

    // Contrôle du formulaire
    if(!isset($_POST['pseudo']) || empty($_POST['pseudo'])){ // test 1-si pseudo n'existe pas (!isset) dans $_POST 2-si sa valeur n'est pas vide. SI 1 ou 2 est vrai alors on entre dans la boucle et on envoie un message d'erreur.
        $c .= '<div class="bg-danger">Le pseudo est requis.</div>';
    }

    if (!isset($_POST['mdp']) || empty($_POST['mdp'])){
		$c .= '<div class="bg-danger">Le mot de passe est requis.</div>';
    }
    
    if(empty($c)){ // si pas de message c que les 2 champs existent et sont remplis, on vérifie alors la validité du couple login/mdp en base

        $mdp = md5($_POST['mdp']); // comparaison de login crypté en md5

        $r = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo AND mdp = :mdp", array(':pseudo' => $_POST['pseudo'], ':mdp' => $mdp));
        //requête en base dans la table memebre pour tous pseudo  = a celui entré en post et tout mdp = a $mdp (mot de passe crypté)

        if ($r->rowCount() != 0) { // si il y a une ligne c que le profil memebre existe 
            $membre = $r->fetch(PDO::FETCH_ASSOC); // création de l'array membre

            $_SESSION['membre'] = $membre; // on met toutes les informations du membre dans la session ouverte dans l'init.

            debug($_SESSION); // un petit control de $_SESSION qu'il faudrat penser a effacer.

            header('location:profil.php'); // renvoie vers la page profil
            exit(); // quitte le script

        } else { // si pas de ligne pas de correspondance login/mdp donc pas de connexion donc message d'erreur
            $c .= '<div class="bg-danger">Erreur de saisie du pseudo ou du mot de passe.</div>';
        }

    }// fin du empty($contenu)

} // Fin du if ($_POST)

// ----------------------- AFFICHAGE ----------------------------------------

require_once( '../inc/haut.inc.php');

echo $c;
?>
<h3>Se Connecter</h3>

<form method="post" action="">
	
	<label for="pseudo"></label><br>
	<input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo"><br><br>

	<label for="mdp"></label><br>
	<input type="password" name="mdp" id="mdp" placeholder="Votre mot de passe"><br><br>

	<input type="submit" value="Connexion" class="btn">

</form>

<?php

require_once( '../inc/bas.inc.php');

