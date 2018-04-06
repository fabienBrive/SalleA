</div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                <p><a href="" class="<?php RACINE_SITE_FRONT .'conditionVente.php'?>">Conditions générales de Vente</a> |
                    <a href="" class="<?php RACINE_SITE_FRONT .'mentionLegale.php'?>">Mentions légales.</a> <br>
                    Copyright &copy; SalleA - Site Pédagogique IFOCOP - Fabien Brive - 2018</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->
    

</body>
<script>
$( function() {

    $( ".datepicker" ).datepicker({
        dateFormat: "dd-mm-yy"
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

});
</script>

<script>
d = new Date();
d = d->format('DD-MM-YYYY');
$("#date_arrivee").val(d);
</script>

</html>
    