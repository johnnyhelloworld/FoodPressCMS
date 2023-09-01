<?php ob_start(); ?>

<img src="../../../public/assets/images/404.jpeg" alt="404" style="width: 100%; height: auto;">
<h2>Ooops! Page non trouvée, elle a peut-être été supprimée ou est temporairement indisponible<br>
</h2>
<a href="/dashboard">Retour au menu</a>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>