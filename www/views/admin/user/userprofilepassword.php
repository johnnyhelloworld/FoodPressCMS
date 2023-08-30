<?php ob_start();
use App\core\Router; ?>

<h1> Votre profil </h1>

<?php Router::includePartial("form", $user->getUserPasswordForm(null)) ?>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>