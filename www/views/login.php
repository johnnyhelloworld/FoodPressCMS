<h1>Login</h1>

<?php if(!empty($result)): ?>
        <div class="">
            <?php foreach($result as $error): ?>
                <p> <?=$error?> </p>
            <?php endforeach ?>
        </div>
<?php endif ?>

<?php $this->includePartial("form", $user->getLoginForm());?>