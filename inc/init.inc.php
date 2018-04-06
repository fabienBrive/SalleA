<?php 

// CE fichier sera inclus dans TOUS les scripts du site (hors les fichiers inc eux-mêmes). Ainsi, les paramètres qui y sont définis seront disponibles


// Connexion à la BDD :
$pdo = new PDO(
		'mysql:host=localhost;dbname=bdd_sallea',
		'root',
		'',
		array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		)
);

//session :
session_start();

// chemin du site :
define('RACINE_SITE', '/sallea/'); // chemin absolue du site à partir de localhost. Utile pour faire des liens dynamiques selon que le fichier source qui les contient sont dans le dossier /admin/ (back-office)
define('RACINE_SITE_BACK','/sallea/back/');
define('RACINE_SITE_FRONT','/sallea/front/');

// Variables d'affichage :
$c = ''; // c pour Contenu
$c_gauche = ''; // contenu gauche de la page
$c_droit = ''; // contenu droit de la page
$compteur = ''; // compteur boutique

// Inclusion du fichier de fonctions :
require_once('fonctions.inc.php');