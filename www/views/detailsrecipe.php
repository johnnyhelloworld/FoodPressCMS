<h1>Détails recette</h1>
<a href="/recipes">Retour</a>

<h1><?= $recipe->getTitle() ?></h1>
<small>Posté par Admin - Catégorie : <?= $category->getName() ?> - <a href="#">Likes(0)</a></small>
<p><?= $recipe->getContent() ?></p>
<button type="button" id="postComment">Laisser un commentaire</button>
<form id="formComment" style="width:450px;margin-top:80px;display:none">
    <span id="errorComment" style="color:red;height:50px"></span>
    <input style="display:block;width:450px" id="titleComment" /><br>
    <textarea style="display:block;width:450px;height:160px" id="contentComment"></textarea><br>
    <button style="display:block;width:100px;margin:0 auto" type="button" id="send">Poster</button>
</form>