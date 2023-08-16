<?php

namespace App\core;

use App\core\Database;

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
        $queryPrepared = $this->pdo->prp($sql, $entry);

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
}
