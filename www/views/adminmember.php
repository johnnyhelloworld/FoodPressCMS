h1>Gestion des membres</h1>

<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Status</th>
        <th>Date de création</th>
        <th>Date de modification</th>
        <th>Update role</th>
        <th>Action</th>
	</tr>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user["lastname"]; ?></td>
            <td><?= $user["firstname"]; ?></td>
            <td><?= $user["email"]; ?></td>
            <td><?= $user["status"] == 1 ? "actif" : "inactif" ?></td>
            <td><?= (new \datetime($user["date_created"]))->format('d-m-Y') ; ?></td>
            <td><?= (new \datetime($user["$date_updated"]))->format('d-m-Y') ; ?></td>

            <td>
                <form action="/editUserRole" method="POST">
                <select id="role" name="role">
                    <option value="<?= $user["role"] ?>"><?=$user["role"]?></option>
                    <option value="super_admin">Chef</option>
                    <option value="admin">Abonné</option>
                    <option value="user">Membre</option>
                </select>
            </td>
            <td>
                <span style="margin:0 20px;padding:4px;background:lightgrey">
                    <button type="submit" style="font-size:11px;text-decoration:none;color:blue" onclick="confirm('Confirmer la modification du rôle ?')">Update</button>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                </span>
                </form>
            </td>
            <td>
                <span style="margin:0 20px;padding:4px;background:lightgrey">
                    <a href="/deleteUser?id=<?= $user['id'] ?>" style="font-size:11px;text-decoration:none;color:blue" onclick="confirm('Confirmer la suppression ?')">Supprimer</a>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
</table>