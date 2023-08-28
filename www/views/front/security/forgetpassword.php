<?php ob_start();
use App\core\Router; ?>

<h1>Mot de passe oublié ?</h1>
<?php
    if(isset($error)):
    ?>
        <p style="color: red;font-size: 0.8em;"><?=$error?></p>
    <?php
    endif;
?>
<?php Router::includePartial("form", $user->getForgetPasswordForm());?>