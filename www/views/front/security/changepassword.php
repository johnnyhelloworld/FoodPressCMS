<?php ob_start();
use App\core\Router; ?>
<h1>Change password</h1>

<?php Router::includePartial("form", $user->getChangePasswordForm());?>