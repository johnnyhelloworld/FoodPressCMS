<?php ob_start(); ?>
<h1>Signalements</h1>

<?php if(isset($reports)) :?>
    <?php foreach ($reports as $report) : ?>
        <span>Commentaire n° : <?= $report->getCommentId() ?></span><br>
        <span>Utilisateur : <?= $report->getEmail() ?></span><br>
        <span>Message concerné : <?= $report->getMessage() ?></span><br>
        <span>Date du message : <?= $report->getCreatedAt() ?></span><br>
        <hr><br>
    <?php endforeach ?>
<?php else :?>
    <p>Il n'y a pas signalement</p>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../base/base.php'); ?>