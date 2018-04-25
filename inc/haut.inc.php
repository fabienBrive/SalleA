<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title>SalleA location de salle pour vos evenements sallea</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet"  href="<?php echo RACINE_SITE . 'inc/css/bootstrap.min.css'; ?>">
    <!-- lien style jquerry -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE . 'inc/css/jquery-ui.min.css'; ?>">
	<!-- boostrap shop homepage -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE . 'inc/css/shop-homepage.css'; ?>">
    <!-- css sortie star-rating  -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE . 'inc/css/star-rating.css'; ?>">
    <!-- CSS de mon range -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE . 'inc/css/jquery.range.css'; ?>">
    <!-- CSS bootstrap fontawesome -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- CSS de mes notation etoiles -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE . 'inc/css/fontawesome-stars.css'; ?>">

    <script src="<?php echo RACINE_SITE . 'inc/js/jquery.js'; ?>"></script>
    <!-- Jquerry UI -->
    <script src="<?php echo RACINE_SITE . 'inc/js/jquery-ui.min.js'; ?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo RACINE_SITE . 'inc/js/bootstrap.min.js'; ?>"></script>
    <!-- script range -->
    <script src="<?php echo RACINE_SITE . 'inc/js/jquery.range.js'; ?>"></script>
    
    <!-- script notation étoiles -->
    <script src="<?php echo RACINE_SITE . 'inc/js/jquery-bar-rating.js'; ?>"></script>

</head>

<body>
    <!-- Navbar theme bootstrap  -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
        <!-- Brand, toggle pour l'affichage en version mobile -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo RACINE_SITE . 'index.php';?>">SalleA</a>
            </div>
    <!-- Liens de navigation, formulaires et autres -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                    <li class="navbar-link"><a href="#" title="Lien actif">Contact</a></li>

                    <?php
                         if (internauteEstConnecte()){
                            // Si connecté affiche lien vers page Profil:
                            echo '<li><a href="'. RACINE_SITE_FRONT .'profil.php">Profil</a></li>';
                        }else{
                            //si non connecté affiche liens vers formulaire d'inscription
                            echo '<li><a href="'. RACINE_SITE_FRONT .'inscription.php">Inscription</a></li>'; 
                        }

                        // Pour l'admin on ajoute les liens de back-office :
                    if (internauteEstConnecteEtEstAdmin()){
                        echo 
                        '<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion Boutique <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="'. RACINE_SITE_BACK .'gestionSalle.php">Gestion salles</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="'. RACINE_SITE_BACK .'gestionProduit.php">Gestion produits</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="'. RACINE_SITE_BACK .'gestionMembre.php">Gestion membres</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="'. RACINE_SITE_BACK .'gestionAvis.php">Gestion Avis</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="'. RACINE_SITE_BACK .'gestionCommande.php">Gestion commandes</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="'. RACINE_SITE_BACK .'statistique.php">Statistiques</a></li>
                            </ul>
                        </li>';
                    }

                echo '</ul>
                
                    <ul class="nav navbar-nav navbar-right">';

                if (internauteEstConnecte()){
                    echo '<li><a href="'. RACINE_SITE_FRONT .'connexion.php?action=deconnexion" title="Lien à droite">Se deconnecter</a></li>';
                } else {
                    echo '<li><a href="'. RACINE_SITE_FRONT .'connexion.php" title="Lien à droite">Connexion</a></li>';
                }

                ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav> 

    <!-- Page Content -->
    <div class="container" style="min-height: 80vh;">