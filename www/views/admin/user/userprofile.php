<?php ob_start();
use App\core\Router; ?>

<h1> Votre profil </h1>

<?php Router::includePartial("form", $user->getUserProfileForm(null)) ?>
<?php $content = ob_get_clean(); ?>
<a class="" href="/userprofilepassword">Changer le mot de passe</a>
<?php require(__DIR__ . '/../base/base.php'); ?>