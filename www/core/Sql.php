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

    public function deleteComments($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE recipe_id = ?";
        $this->pdo->databasePrepare($sql, [$id]);
    }

    public function getCommentsByRecipe($id)
    {
        $sql = "SELECT c.id, c.parent_id, c.author_id, c.title, c.content, c.date_created, u.firstname, u.lastname, u.id
        FROM {$this->table} as c
        JOIN fp_user as u
        ON u.id = c.author_id
        WHERE c.fp_recipe_id = ? 
        AND c.parent_id IS NULL
        ORDER BY c.date_created DESC";

        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countComments($id)
    {
        $sql = "SELECT count(id) 
        FROM {$this->table}
        WHERE fp_recipe_id = ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetch();
    }


    public function getRepliesByComment($id)
    {
        $sql = "SELECT c.id, c.parent_id, c.author_id, c.title, c.content, c.date_created, u.firstname, u.lastname, u.id
        FROM {$this->table} as c
        JOIN fp_user as u
        ON u.id = c.author_id
        WHERE c.fp_recipe_id = ? 
        AND c.parent_id IS NOT NULL
        ORDER BY c.date_created DESC";

        $queryPrepared = $this->pdo->databasePrepare($sql, [$id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLikeByRecipe($user_id, $recipe_id)
    {
        $sql = "SELECT l.id
        FROM fp_like as l
        JOIN fp_user as u 
        ON l.fp_user_id = u.id
        JOIN fp_recipe as r 
        ON l.fp_recipe_id = r.id
        WHERE u.id = ?
        AND r.id = ?";
        
        $queryPrepared = $this->pdo->databasePrepare($sql, [$user_id, $recipe_id]);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }


    public function toggleLikes(int $user_id, int $recipe_id)
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
        $sql = 'SELECT count(l.id) as "likes" FROM fp_like as "l"
        JOIN fp_recipe as "r"
        ON l.fp_recipe_id = r.id
        AND r.id = ?';
        $queryPrepared = $this->pdo->databasePrepare($sql, [$recipe_id]);
        return $queryPrepared->fetch();
    }

    public function getReportNotifications()
    {
        $sql = "SELECT * FROM " . $this->table . " as r WHERE r.has_read = false ";
        $queryPrepared = $this->pdo->databasePrepare($sql, []);
        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }


    public function truncate($table)
    {
        $sql = "TRUNCATE TABLE fp_" . $table . " CASCADE";
        $queryPrepared = $this->pdo->databasePrepare($sql, []);
    }
    
    public function getAllByPosition()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY position ';
        $queryPrepared = $this->pdo->databasePrepare($sql);

        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateItemPosition($position, $block_id)
    {
        $sql = "UPDATE " . $this->table . " SET position = ?  WHERE id= ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$block_id, $position]);
    }

    public function getBlockByPosition($pageId)
    {
        $sql = "SELECT b.id as blockId, f.id as formId, b.position, b.title, b.page_id, s.id as textId, s.block_id, s.content, f.title  as formTitle
                FROM " . DB_PREFIX . "_blocks as b 
                LEFT JOIN " . DB_PREFIX . "_texts as s ON s.block_id = b.id
                LEFT JOIN " . DB_PREFIX . "_forms as f ON f.block_id = b.id
                WHERE page_id = ? 
                ORDER BY position";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$pageId]);

        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBlock($position, $title, $page_id)
    {
        $sql = "INSERT INTO {$this->table} (position, title, page_id) VALUES (?, ?, ?)";
        $this->pdo->databasePrepare($sql, [$position, $title, $page_id]);
    }
}
