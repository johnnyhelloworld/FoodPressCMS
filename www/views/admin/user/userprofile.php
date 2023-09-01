<?php ob_start();
use App\core\Router; ?>

<ul>
  <li><a class="active" href="/userprofile">Profil</a></li>
  <li><a class="" href="/userprofilepassword">Changer le mot de passe</a></li>
</ul>
<h1> Votre profil </h1>

<?php Router::includePartial("form", $user->getUserProfileForm(null)) ?>
<p><span> <?= isset($_SESSION['succes'])? $_SESSION['succes'] : '' ?></span></p>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>