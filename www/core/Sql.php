<?php

namespace App\core;

abstract class Sql
{
    private $table;
    private $pdo;
    
    public function __construct()
    {
        try{
            $this->pdo = new \PDO(DB_DRIVER.":host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME , DB_USER , DB_PWD
            , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
        }catch(\Exception $e){
            die('Error sql' . $e->getMessage());
        }

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

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['id' => $id]);

        return $queryPrepared->fetchObject(get_called_class());
    }

    protected function save():void
    {
        $colums = get_object_vars($this); 
        $columnsToDelete = get_class_vars(get_class());
        $colums = array_diff_key($colums, $columnsToDelete);

        if($colums['id'] === null){
            $colums = array_diff($colums, [$colums['id']]);
            $sql = 'INSERT INTO ' . $this->table  . ' (' . implode(',', array_keys($colums)) . ') VALUES (:'. implode(',:', array_keys($colums)) . ')';
        }else{
            $update = [];
            foreach($colums as $key => $value){
                $update[] = $key . '=:' . $key;
            }
            $sql = "UPDATE ".$this->table." SET ". implode(',', $update) . " WHERE id=:id";
        }
        
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($colums);
    }
}