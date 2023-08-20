<h1>Editer une recette</h1>

<?php $this->includePartial("form", $recipe->getRecipeForm($params)); ?>

<?php if (isset($result)) : ?>
    <?php foreach ($result as $key => $value) : ?>
        <p><?= $value ?></p>
    <?php endforeach; ?>
<?php endif; ?>