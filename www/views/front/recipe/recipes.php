<?php ob_start(); ?>

<div style="display:flex; align-items:center">
    <h1>Nos recettes(<?= count($allRecipe) ?>)</h1>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="text-decoration:none;border-radius:50%; background:grey;padding:5px 10px;color:white;" href="/createrecipe">+</a>
</div>

<?php if (isset($allRecipe)) : ?>

    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Visualiser en ligne</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allRecipe as $recipe => $value) : ?>
                <tr>
                    <td><?= ucfirst($value['title']) ?></td>
                    <td><?= substr($value['content'], 0, 50) . '...' ?></td>
                    <td>
                        <a href="/detailsRecipe?slug=<?= $value['slug'] ?>">
                            <img src="../../../public/assets/images/eye.svg" alt="" width="20" height="20">
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif; ?>


<style>
    th,
    td {
        padding: 15px;
        border: 1px solid grey;
        margin: 0;
        text-align: center;
    }

    table {
        width: 100%;
    }

    thead,
    tbody,
    table {
        border-collapse: collapse;
    }
</style>

<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '../../base/base.php'); ?>