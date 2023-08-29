<?php ob_start();
use App\core\Router; ?>

<img class="logo__login" src="../../../public/assets/images/Foodpress.png" alt="Logo Foodpress">

<?php if(!empty($errors)): ?>
    <div class="">
        <?php foreach($errors as $error): ?>
            <p> <?=$error?> </p>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php Router::includePartial("form", $user->getLoginForm(null));?>
<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>