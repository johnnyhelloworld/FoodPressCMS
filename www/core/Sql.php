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
        $sql = "DELETE FROM {$this->table} CASCADE WHERE title = ?";
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
        $sql = "SELECT b.id as blockId, f.id as formId, b.position, b.title, b.fp_page_id, s.id as textId, s.fp_block_id, s.content, f.title  as formTitle
            FROM fp_block as b 
            LEFT JOIN fp_text as s ON s.fp_block_id = b.id
            LEFT JOIN fp_form as f ON f.fp_block_id = b.id
            WHERE fp_page_id = ? 
            ORDER BY position";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$pageId]);

        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBlock($position, $title, $page_id)
    {
        $sql = "INSERT INTO {$this->table} (position, title, page_id) VALUES (?, ?, ?)";
        $this->pdo->databasePrepare($sql, [$position, $title, $page_id]);
    }

    public function getFormInputs($formId)
    {
        $sql = "SELECT * FROM fp_input as i
                LEFT JOIN fp_form as f ON i.fp_form_id = f.id
                WHERE fp_form_id = ?";
        $queryPrepared = $this->pdo->databasePrepare($sql, [$formId]);

        return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dropDatabase(): void
    {
        $sql = "DROP DATABASE esgi;";
        $queryPrepared = $this->pdo->databasePrepare($sql);
        die();
    }

    public function createDatabase()
    {
        $sql = "CREATE DATABASE {$_SESSION['temp_dbName']};";
        $queryPrepared = $this->pdo->databasePrepare($sql);
    }

    public function createDatabaseTestDatas()
    {
        $sql = "CREATE DATABASE `mvc` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $queryPrepared = $this->pdo->databasePrepare($sql);
    }

    public function createtablesDevTestDatas()
    {
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_user
        (
            id serial NOT NULL,
            firstname character varying(45) NOT NULL,
            lastname character varying(120) NOT NULL,
            email character varying(320) NOT NULL,
            status boolean NOT NULL,
            password character varying(255) NOT NULL,
            token character varying(255),
            role character varying(50),
            date_created timestamp NOT NULL,
            date_updated timestamp NOT NULL,
            CONSTRAINT fp_user_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_passwordreset
        (
            id serial NOT NULL,
            token character varying(255),
            tokenexpiry character varying(255),
            fp_user_id serial NOT NULL,
            CONSTRAINT fp_passwordreset_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_recipe
        (
            id serial NOT NULL,
            title character varying(50),
            content text,
            position integer,
            block_id integer,
            slug character varying(255),
            image bytea,
            date_created timestamp NOT NULL,
            date_updated timestamp NOT NULL,
            fp_category_id serial NOT NULL,
            CONSTRAINT fp_recipe_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_category
        (
            id serial NOT NULL,
            name character varying(50),
            description text,
            image bytea,
            slug character varying(255),
            CONSTRAINT fp_category_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_comment
        (
            id serial NOT NULL,
            title character varying(50),
            content text,
            parent_id integer,
            author_id integer,
            date_created timestamp NOT NULL,
            fp_recipe_id serial NOT NULL,
            CONSTRAINT fp_comment_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_like
        (
            id serial NOT NULL,
            fp_user_id serial NOT NULL,
            fp_recipe_id serial NOT NULL,
            CONSTRAINT fp_like_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_block
        (
            id serial NOT NULL,
            title character varying(50),
            position integer,
            fp_page_id serial NOT NULL,
            CONSTRAINT fp_block_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_page
        (
            id serial NOT NULL,
            title character varying(50),
            position integer,
            link character varying(255),
            type character varying(255),
            fp_theme_id serial NOT NULL,
            CONSTRAINT fp_page_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_theme
        (
            id serial NOT NULL,
            name character varying(50),
            description text,
            domain character varying(255),
            image bytea,
            CONSTRAINT fp_theme_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_report
        (
            id serial NOT NULL,
            message text,
            email character varying(255),
            has_read boolean NOT NULL,
            date_created timestamp NOT NULL,
            fp_comment_id serial NOT NULL,
            CONSTRAINT fp_report_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_menuitem
        (
            id serial NOT NULL,
            name character varying(50),
            link character varying(255),
            js_class character varying(255),
            js_id character varying(255),
            position integer,
            CONSTRAINT fp_menuitem_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_contact
        (
            id serial NOT NULL,
            message text,
            email character varying(320),
            date_created timestamp NOT NULL,
            CONSTRAINT fp_contact_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_input
        (
            id serial NOT NULL,
            placeholder character varying(255),
            name character varying(50),
            value character varying(255),
            label character varying(255),
            type character varying(255),
            js_class character varying(255),
            js_id character varying(255),
            fp_form_id serial NOT NULL,
            CONSTRAINT fp_input_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_form
        (
            id serial NOT NULL,
            title character varying(50),
            fp_block_id serial NOT NULL,
            CONSTRAINT fp_form_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.fp_text
        (
            id serial NOT NULL,
            content text,
            fp_block_id serial NOT NULL,
            CONSTRAINT fp_text_id PRIMARY KEY (id)
        );";
        $queryPrepared = $this->pdo->databasePrepare($sql);
    }

    public function createTables()
    {
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_user
        (
            id serial NOT NULL,
            firstname character varying(45) NOT NULL,
            lastname character varying(120) NOT NULL,
            email character varying(320) NOT NULL,
            status boolean NOT NULL,
            password character varying(255) NOT NULL,
            token character varying(255),
            role character varying(50),
            date_created timestamp NOT NULL,
            date_updated timestamp NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_user_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_passwordreset
        (
            id serial NOT NULL,
            token character varying(255),
            tokenexpiry character varying(255),
            {$_SESSION['temp_prefix']}_user_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_passwordreset_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_recipe
        (
            id serial NOT NULL,
            title character varying(50),
            content text,
            position integer,
            block_id integer,
            slug character varying(255),
            date_created timestamp NOT NULL,
            date_updated timestamp NOT NULL,
            {$_SESSION['temp_prefix']}_category_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_recipe_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_category
        (
            id serial NOT NULL,
            name character varying(50),
            description text,
            image bytea,
            slug character varying(255),
            CONSTRAINT {$_SESSION['temp_prefix']}_category_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_comment
        (
            id serial NOT NULL,
            title character varying(50),
            content text,
            parent_id integer,
            author_id integer,
            date_created timestamp NOT NULL,
            {$_SESSION['temp_prefix']}_recipe_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_comment_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_like
        (
            id serial NOT NULL,
            {$_SESSION['temp_prefix']}_user_id serial NOT NULL,
            {$_SESSION['temp_prefix']}_recipe_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_like_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_block
        (
            id serial NOT NULL,
            title character varying(50),
            position integer,
            {$_SESSION['temp_prefix']}_page_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_block_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_page
        (
            id serial NOT NULL,
            title character varying(50),
            position integer,
            link character varying(255),
            type character varying(255),
            {$_SESSION['temp_prefix']}_theme_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_page_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_theme
        (
            id serial NOT NULL,
            name character varying(50),
            description text,
            domain character varying(255),
            image image bytea,
            CONSTRAINT {$_SESSION['temp_prefix']}_theme_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_report
        (
            id serial NOT NULL,
            message text,
            email character varying(255),
            has_read boolean NOT NULL,
            date_created timestamp NOT NULL,
            {$_SESSION['temp_prefix']}_comment_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_report_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_menuitem
        (
            id serial NOT NULL,
            name character varying(50),
            link character varying(255),
            js_class character varying(255),
            js_id character varying(255),
            position integer,
            CONSTRAINT {$_SESSION['temp_prefix']}_menuitem_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_contact
        (
            id serial NOT NULL,
            message text,
            email character varying(320),
            date_created timestamp NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_contact_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_input
        (
            id serial NOT NULL,
            placeholder character varying(255),
            name character varying(50),
            value character varying(255),
            label character varying(255),
            type character varying(255),
            js_class character varying(255),
            js_id character varying(255),
            {$_SESSION['temp_prefix']}_form_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_input_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_form
        (
            id serial NOT NULL,
            title character varying(50),
            {$_SESSION['temp_prefix']}_block_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_form_id PRIMARY KEY (id)
        );";
        $sql = "CREATE TABLE IF NOT EXISTS public.{$_SESSION['temp_prefix']}_text
        (
            id serial NOT NULL,
            content text,
            {$_SESSION['temp_prefix']}_block_id serial NOT NULL,
            CONSTRAINT {$_SESSION['temp_prefix']}_text_id PRIMARY KEY (id)
        );";
        $queryPrepared = $this->pdo->databasePrepare($sql);
    }
}
