<?php ob_start();
use App\core\Router; ?>

<h1>Login</h1>

<?php if(!empty($errors)): ?>
    <div class="">
        <?php foreach($errors as $error): ?>
            <p> <?=$error?> </p>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php Router::includePartial("form", $user->getLoginForm());?>