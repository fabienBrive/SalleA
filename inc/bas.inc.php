</div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                <p class="text-center"><a href="<?php echo RACINE_SITE_FRONT .'conditionVente.php'?>" >Conditions générales de Vente</a> |
                    <a href="<?php echo RACINE_SITE_FRONT .'mentionLegale.php'?>" >Mentions légales.</a> <br>
                    Copyright &copy; SalleA - Site Pédagogique IFOCOP - Fabien Brive - 2018</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->
    

<script>
$( function() {

    $( "#date_arrivee" ).datepicker({
        dateFormat: "dd-mm-yy",
    });
    $( "#date_depart" ).datepicker({
        dateFormat: "dd-mm-yy",
        defaultDate : 31,
    });
        
    $('.input').jRange({
        from: 0,
        to: 1000,
        step: 10,
        scale: [0,250,500,750,1000],
        format: '%s',
        width: 150,
        showLabels: true,
        isRange : true,
        theme : "theme-blue"
    });
    
   
    $('#note').barrating({
    theme: 'fontawesome-stars'
    });

    // 3 - fonction callback
        function reponse(retourPHP) {
            $("#selection").html(retourPHP); // On affiche le HTML envoyé en réponse par le serveur
        }

    // 1 - fonction d'envoie de la requête au serveur en AJAX :
        function envoi_ajax() {
            var donnees = $('form').serialize(); // transforme les données du formulaire en string avant envoi vers le serveur en AJAX, string formaté pour pouvoir remplir l'array $_POST automatiquement.
console.log(donnees);
            $.post('selection.php', donnees, reponse, 'html');
            /* idem que pour $.get() il y a 4 arguments :
                    - url de destination
                    - données envoyées (objet OU string)
                    - callback de traitement de la réponse serveur,
                    - format de retour = on atttend du HTML */
        }

    // 2 - appel de notre fonction :
        envoi_ajax(); // pour afficher tou de suite tous les produits disponibles
        $("form").change(envoi_ajax); // Si les valeurs du formulaire changent, on appelle de nouveau la fonction pour mettre a jour la selection


});
</script>
</body>


</html>
    