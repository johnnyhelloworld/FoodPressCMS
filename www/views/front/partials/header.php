<?php
    use App\models\MenuItem;
?>

<header id="site-header">
    <div class="container">
        <!-- Si admin: lien de redirection au dashboard -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') : ?>
            <a href="/dashboard">Revenir au dashboard</a>
        <?php endif ?>
        <button id="menu-button"></button>
        <nav id="site-nav">
            <ul>
                <?php foreach ((new MenuItem())->getAllByPosition() as $link) : ?>
                    <li><a href="<?= $link['link'] ?>"><?= $link['name'] ?></a></li>
                <?php endforeach ?>
                <li id="se_connecter"><a href="" class="button">Se connecter</a></li>
            </ul>
        </nav>
    </div>
</header>