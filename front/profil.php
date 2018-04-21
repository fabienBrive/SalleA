<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

debug($_SESSION['membre']);
if ($_SESSION){
$c .= '<h2>Bonjour '. $_SESSION['membre']['pseudo'] .'</h2>';
$c .= '<h3>Voila les informations de ton profil : </h3>';
$c .= '<p><span>Nom : </span>'. $_SESSION['membre']['nom'] .' </p><br>';
$c .= '<p><span>Prenom : </span>'. $_SESSION['membre']['prenom'] .' </p><br>';
$c .= '<p><span>Email : </span>'. $_SESSION['membre']['email'] .' </p><br>';
$c .= '<p><span>Civilité : </span>'. $_SESSION['membre']['civilite'] .' </p><br>';
$c .= '<p><span>Statut : </span>'. $_SESSION['membre']['statut'] .' </p><br>';


}else{
    header('location:index.php');
    exit();
}














// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

