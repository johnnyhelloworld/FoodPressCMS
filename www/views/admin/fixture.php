<?php ob_start(); ?>

<h1>Fixtures</h1>

<p>Attention! Le chargement des fixtures va effacer le contenu de la base de la base de donnée.</p>

<form action="/devfixtures" method='POST'>
    <label> Lancer les fixtures?</label>
    <button type="submit" name="start-fixtures">valider</button>
</form>
<span style="color:green"><?= isset($message) ? $message : '' ?></span>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/base/base.php'); ?>