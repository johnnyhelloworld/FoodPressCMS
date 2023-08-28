<?php ob_start(); ?>

<img src="../../../public/assets/images/500.jpg" alt="500" style="width: 100%; height: auto;">
<h2>Veuillez nous excuser pour ce petit problème technique... <br>
Nous travaillons actuellement à sa résolution</h2>
<a href="/dashboard">Retour</a>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>