<?php ob_start();
use App\core\Router; ?>

<img class="logo__register" src="../../../public/assets/images/FoodPress.png" alt="logo Foodpress">

<?php Router::includePartial("form", $user->getRegisterForm(null)) ?>

<p><span> <?= isset($_SESSION['success'])? $_SESSION['success'] : '' ?></span></p>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>
		<p><?= $error ?></p>
	<?php endforeach; ?>
<?php endif; ?>

<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '../../base/base.php'); ?>