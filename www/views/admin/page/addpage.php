<?php ob_start(); ?>

<div style="display:flex; align-items:center">
    <h1>Gérer mes pages</h1><br><br>
</div>

<div style="padding:20px 10px;">
    <table class="table" style="margin:0 auto;background:white">
        <thead>
            <tr>
                <th>Titre de la page</th>
                <th>Description</th>
                <th>Visualiser en direct</th>
                <th>Editer</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page) : ?>
                <tr>
                    <td><?= $page['title'] ?></td>
                    <td>Description</td>
                    <td> <a href="<?= $page['link'] ?>"><?= $page['title'] ?></a> </td>
                    <td>
                        <a href="/editpage?page=<?= strtolower($page['title']) ?>">
                            <img src="../../../public/assets/images/edit.svg" alt="" width="20" height="20" />
                        </a>
                    </td>
                    <td>
                        <a href="/deletepage?page=<?= $page['title'] ?>&id=<?= $page['id'] ?>" onclick="confirm('confirmer la suppression?')">
                            <img src="../../../public/assets/images/trash.svg" alt="" width="20" height="20" />
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<br>
<div style="padding:20px 10px;border:1px solid grey;background:white;">

    <div style="display:flex; align-items:center">
        <h3>Créer une nouvelle page</h3><br><br>
    </div>

    <span style="color:green"><?= isset($success) ? $success :  '' ?></span>
    <form action="" method="POST">
        <div class="ligne">
            <p class="encare">
                <label>Choisir un titre</label>
            </p>
            <input type="text" name="page_title" required placeholder="Titre de la page" required class="input-form" /><br>
        </div>
        <div class="ligne">
            <p class="encare">
                <label>Choisir une page</label>
            </p>
            <select name="type" id="page_type" class="input-form" required>
                <option value="">Choisir une page</option>
                <option value="presentation">Page présentation</option>
                <option value="article">Page blog</option>
                <option value="contact">Page contact</option>
                <option value="about">Page 'A propos'</option>
                <option value="new">Page vierge</option>
            </select>
        </div>
        <br>
        <div class="ligne">
            <p class="encare">
                <label>Role autorisé</label>
            </p>
            <select name="page_role" class="input-form" required>
                <option value="">Rôle autorisé pour cette page</option>
                <option value=" admin">Admin</option>
                <option value="user">User</option>
                <option value="public">Public</option>
            </select>
        </div>

        <br>
        <button type="submit" name="submit_add_page" class="button button--form">Valider</button>
    </form>
    <span><?= isset($message) ? $message : '' ?></span>
</div>
<style>
    th,
    td {
        padding: 15px;
        border: 1px solid grey;
        margin: 0;
        text-align: center;
    }

    thead,
    tbody,
    table {
        border-collapse: collapse;
    }

    .input-form {
        margin-top: 5px;
        width: 30%;
        padding: 10px 10px;
    }
</style>


<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>