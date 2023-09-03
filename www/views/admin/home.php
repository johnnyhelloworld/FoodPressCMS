<?php ob_start(); ?>
<section class="stats">
    <h1>Statistiques</h1>
    <div class="date">
        <h3>Du</h3>
        <div>15/07/2023</div>
        <h3>au</h3>
        <div>15/08/2023</div>
    </div>
</section>
<div class="containerDashboard">
    <div>
        <h2 class="titleStats">Nombre d’utilisateurs</h2>
        <div class="graph">
            <canvas id="chartU"></canvas>
        </div>
    </div>
    <div>
        <h2 class="titleStats">Nombre de nouveaux recettes</h2>
        <div class="graph">
            <canvas id="chartL"></canvas>
        </div>
    </div>
</div>
<div class="containerDashboard">
    <div>
        <h2 class="titleStats">Calendrier</h2>
        <div class="diary">
        <div class="month-year">Juillet 2023</div>
        <div class="days">
            <div>LUN</div>
            <div>MAR</div>
            <div>MER</div>
            <div>JEU</div>
            <div>VEN</div>
            <div>SAM</div>
            <div>DIM</div>
        </div>
        <div class="days-week">
            <div>
            <a href="#">10</a>
            <div class="container-label">
                <div class="label label--green"><div></div>15h45/16h45 : U-16</div>
                <div class="label"><div></div>17h00/18h30 : U-18</div>
                <div class="label label--blue"><div></div>18h45/20h15 : U-20</div>
                <div class="label label--purple"><div></div>20h30/21h30 : U-40</div>
                <div class="label label--yellow"><div></div>21h45/23h00 : Libre</div>
            </div>
        </div>
        <div>
            <a href="#">11</a>
            <div class="container-label">
                <div class="label label--yellow"><div></div>17h00/20h15 : Libre</div>
            </div>
        </div>
        <div>
            <a href="#">12</a>
            <div class="container-label">
                <div class="label label--green"><div></div>19h00/20h30 : U-16</div>
            </div>
        </div>
        <div>
            <a href="#">13</a>
            <div class="container-label">
                <div class="label"><div></div>19h00/20h30 : U-18</div>
            </div>
        </div>
        <div>
            <a href="#">14</a>
            <div class="container-label">
                <div class="label label--purple"><div></div>19h00/20h30 : U-40</div>
            </div>
        </div>
        <div>
            <a href="#">15</a>
            <div class="container-label">
                <div class="label label--orange"><div></div>19h00/20h30 : U-10</div>
            </div>
        </div>
        <div>
            <a href="#">16</a>
                <div class="container-label">
            </div>
        </div>
        </div>
    </div>
    <h2 class="titleStats">Derniers enregistrements</h2>
    <div class="last-register">
        <div class="title">
            <div class="firstname">Nom</div>
            <div class="lastname">Prénom</div>
            <div class="mail">Email</div>
            <div class="actif">Compte actif</div>
        </div>
        <div class="scroll">
            <div class="box box--grey">
                <div class="firstname">Johnny</div>
                <div class="lastname">CHEN</div>
                <div class="mail">johnny.chen@hotmail.fr</div>
                <div class="actif">Oui</div>
            </div>
            <div class="box">
                <div class="firstname">Thomas</div>
                <div class="lastname">RIEHL</div>
                <div class="mail">thomas.riehl@gmail.com</div>
                <div class="actif">Oui</div>
            </div>
        </div>
    </div>
    <div>
        <h2 class="titleStats">Messages</h2>
        <div class="msg">
            <div class="title">
                <span class="user-info">Prénom</span>
                <span class="user-msg">Dernier message</span>
            </div>
            <div class="box box--grey">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile.svg" alt="">
                    </div>
                    Johnny
                </span>
                <span class="user-msg unread">
                    Lorem ipsum dolor sit amet.
                    <div class="btn-unread"></div>
                </span>
            </div>
            <div class="box">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile.svg" alt="">
                    </div>
                    Thomas
                </span>
                <span class="user-msg unread">
                    Lorem ipsum dolor sit amet.
                    <div class="btn-unread"></div>
                </span>
            </div>
            <div class="box"></div>
            <div class="box box--grey"></div>
            <div class="box"></div>
        </div>
    </div>
</div>
<div class="containerDashboard">
    <div>
        <h2 class="titleStats">Taux de présence semaine</h2>
        <div class="graph">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <div>
        <h2 class="titleStats">Informations diverses</h2>
        <section class="various-info">
            <div>
                <div class="img-container"><img src="assets/images/templates.svg" alt=""></div>
                <span>Nombre de templates créés : 3</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/page-visited.svg" alt=""></div>
                <span>Nombre de pages visitées aujourd’hui : 326</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/present.svg" alt=""></div>
                <span>Nombre de présents aujourd’hui : 25</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/letter.svg" alt=""></div>
                <span>Nouvelle demande de contact : 13</span>
            </div>
        </section>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/base/base.php'); ?>