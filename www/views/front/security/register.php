<?php ob_start();
use App\core\Router; ?>

<img class="logo__register" src="../../../public/assets/images/FoodPress.png" alt="logo">

<?php Router::includePartial("form", $user->getRegisterForm(null)) ?>
<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '../../base/base.php'); ?>