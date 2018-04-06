<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

// Traitement du $_POST:

if (!empty($_POST)) { // Si le formulaire est posté, $_POST est remplie (pas vide)

    //Validation du formulaire :
        
	if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20) {
		// Verification présence et longeur des champs
		$c .= '<div class="bg-danger"> le pseudo doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 20) {
		$c .= '<div class="bg-danger"> le mot de passe doit contenir entre 4 et 20 caractères.</div>';
	}
    
    if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 3 || strlen($_POST['prenom']) > 20) {
        $c .= '<div class="bg-danger"> le prénom doit contenir entre 4 et 20 caractères.</div>';
    }

	if (!isset($_POST['nom']) || strlen($_POST['nom']) < 3 || strlen($_POST['nom']) > 20) {
		$c .= '<div class="bg-danger"> le nom doit contenir entre 4 et 20 caractères.</div>';
	}

	if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		// filter_var() permet ici de valider ici le format de type email : retourne true si c'est ok sinon false. Note : ici on verifie la négation qu'il ne s'agit pas d'un email (d'où le "!")
		$c .= '<div class="bg-danger">L\'email est incorrect.</div>';
	}

	if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')) {
		$c .= '<div class="bg-danger">La civilite est incorrecte.</div>';
    }
    // Fin de vérification des champs
    if (empty($c)){ // si aucun contenu c'est qu'il n'y a pas de message d'erreur donc que les champs son bien remplis alors je peux passer à l'enregistrement en base

        // dispo du pseudo :
        $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo'])); // requete en base pour savoir si ce pseudo est déja enregistré

        if($membre->rowcount() > 0) { // si une ligne existe c'est que le pseudo existe deja alors message :
            $c .= '<div class="bg-danger">Pseudo indisponible, veuillez en choisir un autre !</div>';
        } else { // sinon c'est bon on peut l'enregistrer en base avec toutes les autres données nécessaires à la table

            $mdp = md5($_POST['mdp']); // on commence par encrypter le code

            executeRequete( // on balance toutes les infos du memebre en base à l'aide de la fonction executeRequete (fonction qui créer des requêtes préprarées ) 
                "INSERT INTO membre (pseudo, mdp, prenom, nom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :prenom, :nom, :email, :civilite, 0, NOW())",
                array(
                    ':pseudo' 		=> $_POST['pseudo'],
                    ':mdp' 			=> $mdp,
                    ':prenom' 		=> $_POST['prenom'],
					':nom' 			=> $_POST['nom'],
					':email' 		=> $_POST['email'],
					':civilite' 	=> $_POST['civilite']
                )
            ); // fin fonction

            $c .= '<div class="bg-success">Votre inscription est validée, vous pouvez <a href="connexion.php">cliquez ici</a> pour vous connecter.</div>';

        } // fin du else

    } // fin du if (empty($contenu))

} // fin du if (!empty($_POST))


// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;
?>

<h3>S'inscrire</h3>

<form method="post" action=""> <!-- On passe tous ce formulaire en POST-->

	<label for="pseudo"></label>
	<input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" value="<?php echo $_POST['pseudo'] ?? ''; ?>"><br><br>
    <!-- nouvelle fonction de php7 le chainage avec ??, pour remplir le champ en value on met la valeur enregistrée dans le POST ou un champ vide '' le chainage selectionne dans la liste la première valeur existante. -->

	<label for="mdp"></label>
	<input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" value="<?php echo $_POST['mdp'] ?? ''; ?>"><br><br>

	<label for="prenom"></label>
	<input type="text" id="prenom" name="prenom" placeholder="Votre prénom" value="<?php echo $_POST['prenom'] ?? ''; ?>"><br><br>

	<label for="nom"></label>
	<input type="text" id="nom" name="nom" placeholder="Votre nom" value="<?php echo $_POST['nom'] ?? ''; ?>"><br><br>

	<label for="email"></label>
	<input type="text" id="email" name="email" placeholder=" votre email" value="<?php echo $_POST['email'] ?? ''; ?>"><br><br>

    <label></label>
    <select name="civilite" id="">
        <option value="m">Homme</option>
        <option value="f" <?php if (isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'selected'; ?> >Femme</option> 
    </select> <br><br>

	<input type="submit" name="inscription" value="inscription" class="btn">



</form>

<?php

require_once('../inc/bas.inc.php');

