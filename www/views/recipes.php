<h1>Affichage recettes</h1>

<?php if(isset($allRecipe)) : ?>
    <?php foreach ($allRecipe as $recipe => $value) : ?>
        <p><?= $value['title'] ?></p>
    <?php endforeach; ?>
<?php endif; ?>