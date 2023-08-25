<?php ob_start();

use App\core\Router; ?>

<h1>Créer une recette</h1>

<?php Router::includePartial("form", $recipe->getRecipeForm(null)) ?>

<?php if (isset($result)) : ?>
    <?php foreach ($result as $key => $value) : ?>
        <p><?= $value ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php $content = ob_get_clean(); ?>
<?php require('../base/base.php'); ?>