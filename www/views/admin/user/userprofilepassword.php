<?php ob_start();
use App\core\Router; ?>

<ul>
  <li><a class="" href="/userprofile">Profil</a></li>
  <li><a class="active" href="/userprofilepassword">Changer le mot de passe</a></li>
</ul>
<h1> Mot de passe </h1>

<?php Router::includePartial("form", $user->getUserPasswordForm(null)) ?>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>