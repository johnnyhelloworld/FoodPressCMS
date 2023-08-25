<?php ob_start(); ?>

<h5>Liste de mes pages</h5>
<?php foreach ($pages as $page) : ?>
    <div class="page_edit">
        <span>
            <a href="<?= $page['link'] ?>"><?= $page['title'] ?></a>
        </span>
        &nbsp;
        <span>
            <a href="/editpage?page=<?= $page['title'] ?>">
                <img src="/public/assets/images/edit.svg" alt="" width="20" height="20" />
            </a>
        </span>
        &nbsp;
        <span>
            <a href="/deletepage?page=<?= $page['title'] ?>&id=<?= $page['id'] ?>" onclick="confirm('confirmer la suppression?')">
                <img src="/public/assets/images/trash.svg" alt="" width="20" height="20" />
            </a>
        </span>
    </div>
<?php endforeach ?>

<h5>Créer une page de blog</h5>
<span style="color:green"><?= isset($success) ? $success :  '' ?></span>
<form action="" method="POST">
    <input type="text" name="page_title" required placeholder="Titre de la page" required /><br>
    <select name="type" id="page_type" required>
        <option value="">Choisir une page</option>
        <option value="presentation">Page présentation</option>
        <option value="article">Page blog</option>
        <option value="contact">Page contact</option>
        <option value="about">Page à propos</option>
    </select>
    <br>
    <select name="page_role" required>
        <option value="">Rôle autorisé pour cette page</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
        <option value="public">Public</option>
    </select>
    <br>
    <button type="submit" name="submit_add_page">Valider</button>
</form>
<span><?= isset($message) ? $message : '' ?></span>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/base/base.php'); ?>
