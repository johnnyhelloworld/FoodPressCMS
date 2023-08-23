<?php

namespace App\core;

use App\core\Database;
use PDO;

abstract class Sql
{
    private $table;
    private $pdo;

    public function __construct()
    {

        $this->pdo = Database::connect();

        // Récupération de la classe appelée (insertion dans la bonne base)
        $getCalledClass = explode('\\', strtolower(get_called_class()));

        // Création du nom de la table avec son préfix
        $this->table = DB_PREFIX . end($getCalledClass);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function setId(?int $id):self
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=:id';

        $queryPrepared = $this->pdo->databasePrepare($sql, ['id' => $id]);

        return $queryPrepared->fetchObject(get_called_class());
    }

    protected function save():void{
        $colums = get_object_vars($this);
        $columnsToDelete = get_class_vars(get_class());
        $colums = array_diff_key($colums, $columnsToDelete);

        if($colums['id'] === null){
            $colums = array_diff($colums, [$colums['id']]);
            $sql = 'INSERT INTO ' . $this->table  . ' (' . implode(',', array_keys($colums)) . ') VALUES (:' . implode(',:', array_keys($colums)) . ')';
        }else{
            $update = [];
            foreach ($colums as $key => $value) {
                $update[] = $key . '=:' . $key;
            }
            $sql = "UPDATE " . $this->table . " SET " . implode(',', $update) . " WHERE id=:id";
        }

        $this->pdo->databasePrepare($sql, $colums);
    }

    public function getOneBy($entry)
    {
        $values = [];
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . array_keys($entry)[0] . '=:' . array_keys($entry)[0];
        $queryPrepared = $this->pdo->databasePrepare($sql, $entry);

        while ($row = $queryPrepared->fetchObject(get_called_class())) {
            array_push($values, $row);
        }
        return $values;
    }

    public function getBy($entry)
    {
        $values = [];
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ';
        foreach ($entry as $key => $data) {
            if(end($entry) != $data) {
                $sql .= $key . '=:' . $key . ' and ';
            }else{
                $sql .= $key . '=:' . $key;
            }
        }
        $queryPrepared = $this->pdo->databasePrepare($sql, $entry);
        while ($row = $queryPrepared->fetchObject(get_called_class())) {
            array_push($values, $row);
        }
        return $values;
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $queryPrepared = $this->pdo->databasePrepare($sql);

        return $queryPrepared->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->pdo->databasePrepare($sql, [$id]);
    }

    public function deletePage($page)
    {
        $sql = "DELETE FROM {$this->table} WHERE title = ?";
        $this->pdo->databasePrepare($sql, [$page]);
    }

    public function deleteBlock($page)
    {
        $sql = "DELETE FROM {$this->table} WHERE page_id = ?";
        $this->pdo->databasePrepare($sql, [$page]);
    }

    public function getCommentsByRecipe($id)
    {
        $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.date_created,u.firstname, u.lastname, u.id as 'idUser'
        FROM {$this->table} as c
        JOIN `fp_user`as u
        ON u.id = c.author_id
        WHERE c.recipe_id = ?
        AND c.parent_id IS NULL
        ORDER BY c.date_created DESC";

        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countComments($id)
    {
        $sql = "SELECT count(id) as 'count' FROM {$this->table}
        WHERE recipe_id = ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetch();
    }


    public function getRepliesByComment($id)
    {
        $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.date_created,u.firstname, u.lastname, u.id as 'idUser'
        FROM {$this->table} as c
        JOIN `fp_user`as u
        ON u.id = c.author_id
        WHERE c.recipe_id = ?
        AND c.parent_id IS NOT NULL
        ORDER BY c.date_created DESC";

        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLikeByRecipe($user_id, $recipe_id)
    {
        $sql = "SELECT l.id as 'like' FROM `fp_like` as l
        JOIN `fp_user`as u
        ON l.user_id = u.id
        JOIN `fp_recipe`as r
        ON l.recipe_id = r.id
        WHERE u.id = ?
        AND r.id = ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$user_id, $recipe_id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }


    public function toggleLikes($user_id, $recipe_id)
    {
        $likes = $this->getUserLikeByRecipe($user_id, $recipe_id);

        if (count($likes) == 0) {
            $sql = "INSERT INTO fp_like (user_id, recipe_id) VALUES (?, ?)";
            $this->pdo->databasePrepare($sql, [$user_id, $recipe_id]);
        } else {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND recipe_id = ?";
            $this->pdo->databasePrepare($sql, [$user_id, $recipe_id]);
        }
    }

    public function countAllLikesByRecipe($recipe_id)
    {
        $sql = "SELECT count(l.id) as 'likes' FROM `fp_like` as l
        JOIN `fp_recipe`as r
        ON l.recipe_id = r.id
        AND r.id = ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$recipe_id]);
        return $queryPrepared->fetch();
    }
}
