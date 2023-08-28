<header>
    <div class="search">
        <!-- <a href="#"> <img src="../../../public/assets/images/search.png" alt=""></a>
        <input type="text" class="large" placeholder="Rechercher">
        <input type="text" class="small" placeholder="Rechercher"> -->
    </div>
    <div class="profil">
        <a href=""><img src="../../../public/assets/images/vector.png" alt="Mail"> <span class="t-contact">Contact</span></a>
        <span class="line"></span>
        <a href="">
            <p>Johnny Chen</p>
            <img src="../../../public/assets/images/unsplash.png" alt="photo profil"></a>
        </a>
    </div>

    <div class="report-notifications" style="position:relative">
        <a href="/reports">
            <img src="../../../public/assets/images/bell.svg" alt="" width="25" height="25">
        </a>
        <?php if ($_SESSION['report'] > 0) : ?>
            <span style="display:block;padding:2px 7px;border-radius:50%;color:white;background:red;position:absolute;top:-4px;left:15px">
                <?= $_SESSION['report'] ?>
            </span>
        <?php endif  ?>
    </div>

    <div class="l-navbar" id="navbar">
        <nav class="nav">
            <div>
                <div class="nav__brand">

                    <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                    <a href="#" class="nav__logo">FoodPress<span id="t-cms">CMS</span></a>
                </div>
                <div class="nav__list">
                    <a href="/dashboard" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/dashboard' ? 'active-link' : '' ?>">
                        <ion-icon name="home-outline" class="nav__icon "></ion-icon>
                        <span class="nav__name active">Dashboard</span>
                    </a>
                    <a href="/editMenu" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/editMenu' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mon menu</span>
                    </a>
                    <a href="/addpage" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/addpage' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes pages</span>
                    </a>

                    <a href="/recipes" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/recipes' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes recettes</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes categories</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="pie-chart-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Statistiques</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="settings-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Paramètres</span>
                    </a>
                </div>
            </div>

            <a href="#" class="nav__link">
                <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                <span class="nav__name">Déconnexion</span>
            </a>
        </nav>
    </div>
</header>