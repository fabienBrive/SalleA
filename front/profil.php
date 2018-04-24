<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

//requête pour affichage table commande du profil :


// Préparation de l'affichage des ifos profil et de la table des commande du memebre :
    //debug($_SESSION['membre']);
    if ($_SESSION){ // Si une session est ouverte alors le mmebre est connecté j'affiche sa page profil

        if (internauteEstConnecteEtEstAdmin() && isset($_GET['action']) && $_GET['action'] == 'voir' && isset($_GET['id_membre'] )){

            $res = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre",array(
                        ':id_membre' => $_GET['id_membre']
            ));
                $details_membre = $res->fetch(PDO::FETCH_ASSOC);


            $c .= '<h2>Vous êtes sur le profil de '. $details_membre['pseudo'] .'</h2>';
            $c .= '<h3>Toutes ses informations : </h3>';
            $c .= '<p><span>Nom : </span>'. $details_membre['nom'] .' </p><br>';
            $c .= '<p><span>Prenom : </span>'. $details_membre['prenom'] .' </p><br>';
            $c .= '<p><span>Email : </span>'. $details_membre['email'] .' </p><br>';
            $c .= '<p><span>Civilité : </span>'. $details_membre['civilite'] .' </p><br>';
            $c .= '<p><span>Statut : </span>'. $details_membre['statut'] .' </p><br>';

        }
            else{
        
            $c .= '<h2>Bonjour '. $_SESSION['membre']['pseudo'] .'</h2>';
            $c .= '<h3>Voila les informations de votre profil : </h3>';
            $c .= '<p><span>Nom : </span>'. $_SESSION['membre']['nom'] .' </p><br>';
            $c .= '<p><span>Prenom : </span>'. $_SESSION['membre']['prenom'] .' </p><br>';
            $c .= '<p><span>Email : </span>'. $_SESSION['membre']['email'] .' </p><br>';
            $c .= '<p><span>Civilité : </span>'. $_SESSION['membre']['civilite'] .' </p><br>';
            $c .= '<p><span>Statut : </span>'. $_SESSION['membre']['statut'] .' </p><br>';
        } 

        $c .= '<h3>Voila l\'historique de vos commandes : </h3>';
        $c .= '<table class="table">';
        $c .= '<tr>';
        $c .= '<th>Compteur</th>'; 
        $c .= '<th>id Commande</th>';
        $c .= '<th>id Produit</th>';
        $c .= '<th>Prix</th>';
        $c .= '<th>Dated\'enregistrement</th>';
        $c .= '</tr>';
       
        
            if (internauteEstConnecteEtEstAdmin() && isset($_GET['action']) && $_GET['action'] == 'voir' && isset($_GET['id_membre'] )){
                $marqueur = $_GET['id_membre']; // si je suis l'admin je veux voir le profil d'un memebre le marqueur prend la valeur du $_GET
            }else{
                $marqueur = $_SESSION['membre']['id_membre']; // sinon si je suis membre et je veux voir mon profil, le marqueur prend la veleur du $_SESSION
            }
            
            $r = executeRequete("SELECT c.id_commande, c.id_produit, p.id_produit, DATE_FORMAT(c.date_enregistrement, '%d-%m-%Y %H:%i') AS date_enregistrement, p.prix FROM commande c, produit p WHERE c.id_produit = p.id_produit AND c.id_membre = :id_membre",array(
                    ':id_membre' => $marqueur
                    ));

    while($commandes_membre = $r->fetch(PDO::FETCH_ASSOC)){

        $compteur = $compteur + 1; // pour la colone compteur de commande (numérotation)

        $c .= '<tr>';
            $c .= '<td>'. $compteur .'</td>';
            $c .= '<td>'. $commandes_membre['id_commande'] .'</td>';
            $c .= '<td>'. $commandes_membre['id_produit'] .'</td>';
            $c .= '<td>'. $commandes_membre['prix'] .'</td>';
            $c .= '<td>'. $commandes_membre['date_enregistrement'] .'</td>';
        $c .= '</tr>';
    }
$c .= '</table>';

}else{ // Sinon pas connecté donc renvoyé vers connexion.php
    header('location:connexion.php');
    exit();
}// fin du if ($_SESSION)














// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

