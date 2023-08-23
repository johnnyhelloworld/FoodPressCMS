<h5>Créer une page de blog</h5>
<span style="color:green"><?= isset($success) ? $success :  '' ?></span>
<form action="" method="POST">
    <input type="text" name="page_title" required placeholder="Titre de la page" required /><br>
    <select name="type" id="page_type" required>
        <option value="">Choisir une page</option>
        <option value="presentation">Page présentation</option>
        <option value="article">Page blog</option>
        <option value="contact">Page contact</option>
        <option value="about">Page à propos</option>
    </select>
    <br>
    <select name="page_role" required>
        <option value="">Rôle autorisé pour cette page</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
        <option value="public">Public</option>
    </select>
    <br>
    <button type="submit" name="submit_add_page">Valider</button>
</form>
<span><?= isset($message) ? $message : '' ?></span>