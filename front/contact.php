<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------


// envoi de mail de contact

// S'il y des données de postées
if ($_SERVER['REQUEST_METHOD']=='POST') {

  // (1) Code PHP pour traiter l'envoi de l'email

  // Récupération des variables et sécurisation des données
  $nom     = htmlentities($_POST['nom']); // htmlentities() convertit des caractères "spéciaux" en équivalent HTML
  $email   = htmlentities($_POST['email']);
  $message = htmlentities($_POST['message']);
  $sujet   = htmlentities($_POST['sujet']);

  // Variables concernant l'email

  $destinataire = 'postmaster@fabienbrive.fr'; // Adresse email du webmaster (à personnaliser)
  $sujet = 'le sujet relatif à : '.$sujet; // Titre de l'email
  $contenu = '<html><head><title>Message de la part de Contact salleA</title></head><body>';
  $contenu .= '<p>Bonjour, vous avez reçu un message à partir de votre site web.</p>';
  $contenu .= '<p><strong>Nom</strong>: '.$nom.'</p>';
  $contenu .= '<p><strong>Email</strong>: '.$email.'</p>';
  $contenu .= '<p><strong>Message</strong>: '.$message.'</p>';
  $contenu .= '</body></html>'; // Contenu du message de l'email (en XHTML)

  // Pour envoyer un email HTML, l'en-tête Content-type doit être défini
  $headers = 'MIME-Version: 1.0'."\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";

  // Envoyer l'email
  mail($destinataire, $sujet, $contenu, $headers); // Fonction principale qui envoi l'email
  $c .= '<h2>Message envoyé!</h2>'; // Afficher un message pour indiquer que le message a été envoyé
  // (2) Fin du code pour traiter l'envoi de l'email
}



// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');
?>

<h1>Pour nous contacter :</h1>
<!-- Ceci est un commentaire HTML. Le code PHP devra remplacé cette ligne -->
<form method="post" action="<?php echo strip_tags($_SERVER['REQUEST_URI']); ?>">

  <p>Votre nom et prénom <span style="color:#ff0000;">*</span>: <input type="text" name="nom" size="30" requierd></p>

  <p>Votre email <span style="color:#ff0000;">*</span>: <input type="text" name="email" size="30" requierd></p>

  <p>Votre sujet <span style="color:#ff0000;">*</span>:</p><select name="sujet" id="sujet">
  <option value="pb connexion">Problèmes pour vous connecter</option>
  <option value="pb inscription">Problèmes pour vous inscrire</option>
  <option value="pb commande">Problèmes avec une commande</option>
  <option value="autre">Autre</option>
  </select>

  <p>Message <span style="color:#ff0000;">*</span>:</p>

  <textarea name="message" cols="60" rows="10" requierd></textarea>

  <p><input type="submit" name="submit" value="Envoyer" /></p>
</form>

<?php

echo $c;
require_once('../inc/bas.inc.php');

