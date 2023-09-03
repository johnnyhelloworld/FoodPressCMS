<?php foreach ($reports as $report) : ?>
    <span>Commentaire n° : <?= $report->getCommentId() ?></span><br>
    <span>Utilisateur : <?= $report->getEmail() ?></span><br>
    <span>Message concerné : <?= $report->getMessage() ?></span><br>
    <span>Date du message : <?= $report->getCreatedAt() ?></span><br>
    <hr><br>
<?php endforeach ?>