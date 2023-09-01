<?php ob_start(); ?>

<style>
    .unliked {
        color: grey;
    }

    .liked {
        color: red;
    }
</style>

<?php if (isset($_SESSION['admin'])) : ?>
    <a href="/admin">Back</a>
<?php else : ?>
    <a href="/recipes">Back</a>
<?php endif ?>

<div class="container">
    <div class="block-recipe" style="min-height:250px;width:500px;margin:0 auto">
        <h1><?= $recipe->getTitle() ?></h1>
        <small>Posté par Admin - Catégorie : <?= $category->getName() ?> -
            <span id="heart_like" style="cursor:pointer;font-size:20px" data-recipe="<?= $recipe->getId() ?>">♥</span>
            <span id="count_like"><?= $total_likes ?></span> -
        </small>
        <small>commentaires(<?= $countComments['count'] ?>)</small>
        <p><?= $recipe->getContent() ?></p>
    </div>

    <div class="block-comment" style="margin:100px auto 15px auto; width:500px">
        <button type=" button" id="postComment">Laisser un commentaire</button><br>
        <form id="formComment" style="width:450px;margin-top:80px;display:none" data-recipe="<?= $recipe->getId() ?>">
            <span id="alertComment" style="height:50px"></span>
            <input style="display:block;width:450px" id="titleComment" /><br>
            <textarea style="display:block;width:450px;height:160px" id="contentComment"></textarea><br>
            <button style="display:block;width:100px;margin:0 auto" type="button" id="send">Poster</button>
        </form>
    </div>


    <div class=" comments" style="width:500px;margin:0 auto">
        <?php if (isset($comments)) : ?>
            <?php foreach ($comments as $comment) : ?>
                <hr>
                <div style="display:flex; justify-content:space-between">
                    <small style=font-weight:bold;font-style:italic>Commenté par <?= $comment['firstname'] . ' ' . $comment['lastname'] ?> le
                        <?= substr($comment['date_created'], 0, 10) ?> à <?= substr($comment['date_created'], -9, 18) ?>
                    </small>
                    <a title="signaler" href="/reportComment?id=<?= $comment['id'] ?>">
                        <img src="../../../public/assets/images/warning.svg" alt="" width="19" height="19">
                    </a>
                </div>
                <div style="display:flex;align-items:center;margin-top:10px;">
                    <span style="padding:10px;border-radius:50%;background:grey;color:white">
                        <?= ucfirst(substr($comment['firstname'], 0, 1)) . ucfirst(substr($comment['lastname'], 0, 1)) ?>
                    </span>
                    &nbsp;&nbsp;
                    <h3 style="margin-top:15px"><?= $comment['title'] ?></h3>
                    &nbsp;&nbsp;
                    <button type="button" class="reply" data-replyer="1" data-parentid="<?= $comment['id'] ?>" data-recipe="<?= $recipe->getId() ?>">
                        reply
                    </button>
                </div>
                <span class="contentCommentSpan">
                    <?= $comment['content'] ?>
                </span>
                <?php if (isset($replies)) : ?>
                    <div class="replyComments-block" id="replyComment<?= $comment['id'] ?>">
                        <?php foreach ($replies as $reply) : ?>
                            <?php if ($reply['parent_id'] == $comment['id']) : ?>
                                <br>
                                <br>
                                <div style="margin-left:50px;padding:8px 0; border-top:1px solid black">
                                    <div style="display:flex; justify-content:space-between">
                                        <small style="font-weight:bold;font-style:italic">répondu par <?= $reply['firstname'] . ' ' . $reply['lastname'] ?> le
                                            <?= substr($reply['date_created'], 0, 10) ?> à <?= substr($reply['date_created'], -9, 18) ?>
                                        </small>
                                        <a title="signaler" href="/reportComment?id=<?= $reply['id'] ?>">
                                            <img src="../../../public/assets/images/warning.svg" alt="" width="19" height="19">
                                        </a>
                                    </div>
                                    <div style="display:flex;align-items:center;margin-top:10px;">
                                        <span style="padding:5px;border-radius:50%;background:grey;color:white">
                                            <?= ucfirst(substr($reply['firstname'], 0, 1)) . ucfirst(substr($reply['lastname'], 0, 1)) ?>
                                        </span>
                                        &nbsp;
                                        <small class="contentCommentSpan">
                                            <?= $reply['content'] ?>
                                        </small>
                                        &nbsp;
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script type="text/javascript" src="../../../public/src/js/comment.js"></script>
<script type="text/javascript" src="../../../public/src/js/like.js"></script>

<?php $base = ob_get_clean(); ?>
<?php require(__DIR__ . '../../base/base.php'); ?>
