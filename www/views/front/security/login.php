<?php ob_start();
use App\core\Router; ?>

<?php if(!empty($errors)): ?>
    <div class="">
        <?php foreach($errors as $error): ?>
            <p> <?=$error?> </p>
        <?php endforeach ?>
    </div>
<?php endif ?>
<img class="logo__login" src="../../../public/assets/images/Foodpress.png" alt="Logo Foodpress">

<?php Router::includePartial("form", $user->getLoginForm(null));?>
<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>