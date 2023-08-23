<h1>Tableau de bord</h1>
Welcome <?= $firstname?>
<?php if (false) : ?>
    <h2>Salut</h2>
<?php endif;?>

<hr>
<h1>Gérer mes pages</h1>
<a href="/addpage">Ajouter une page</a>

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
<hr>
<h1>Gérer mon menu</h1>
<a href="/editMenu">Get started</a>