<?php 

require_once('../inc/init.inc.php');

// ----------------------- TRAITEMENT ----------------------------------------

$c .= '<h2>Pour nous contacter</h2>';

$c .= '<form >

            <label for="sujet">Sujet</label><br>
            <select name="sujet">
                <option value="1">Problème d\'inscription à notre plateforme</option>
                <option value="2">Problème de connexion</option>
                <option value="3">Problème à propos d\'une commande, d\'un produit</option>
                <option value="4">Problème sur votre profil</option>
                <option value="5">tout autre problème</option>
            </select><br><br>
            
            <label for="message">Message</label><br>
            <textarea name="message" placeholder="Ici votre message" rows="25" cols="80"></textarea><br><br>

            <input type="submit" value="Envoyer">
        </form>';













// ----------------------- AFFICHAGE ----------------------------------------

require_once('../inc/haut.inc.php');

echo $c;

require_once('../inc/bas.inc.php');

