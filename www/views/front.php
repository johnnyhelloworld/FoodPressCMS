<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Template front</title>
        <meta name="description" content="mon super site en MVC from scratch">
        <link rel="stylesheet" href="../public/dist/main.css">
        <script src="../public/src/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="../public/src/js/main.js"></script>
    </head>
    <body>
        <header id="site-header">
            <div class="container">
                <button id="menu-button"></button>
                <nav id="site-nav">
                    <ul>
                        <li><a href="">Tarification</a></li>
                        <li><a href="">À propos de nous</a></li>
                        <li><a href="">Contact</a></li>
                        <li id="se_connecter"><a href="/login" class="button">Se connecter</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <?php include $this->view; ?>
        <footer>
            <div class="container">
                <ul>
                    <li><a href="#">Termes et condition</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="https://github.com/johnnyhelloworld/FoodPressCMS">Documentation</a></li>
                    <li><a href="/">FoodPress<span class="bottom-title-cms">CMS</span></a></li>
                </ul>
            </div>
        </footer>
    </body>
</html>