
<?php ob_start(); ?>


<h1>Recipes</h1>

<?php if (isset($allRecipe)) : ?>

    <?php foreach ($allRecipe as $recipe => $value) : ?>
        <h1><?= ucfirst($value['title']) ?></h1>
        <p><?= substr($value['content'], 0, 40) . '...' ?></p>
        <hr>
    <?php endforeach ?>
<?php endif; ?>

<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '../../base/base.php'); ?>