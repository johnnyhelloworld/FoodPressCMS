<?php ob_start(); ?>

<h2>Ooops! Page non trouvée, elle a peut-être été supprimée ou est temporairement indisponible<br>
</h2>
<a href="/dashboard">Retour au menu</a>
<img src="../../../public/assets/images/4041.jpeg" alt="404" width="100%" height="100%">

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>