<?php ob_start(); ?>


<img src="../../../public/assets/images/403.png" alt="403" style="width: 100%; height: auto;">
<h2>Vous ne disposez pas des droits nécessaires pour consulter cette ressource.</h2>
<a href="/home">Retour</a>


<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>