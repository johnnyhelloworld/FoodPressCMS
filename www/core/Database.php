<?php

namespace App\core;

class Database
{
    private $PDOInstance = null;
    public static $connection;

    private function __construct()
    {
        try {
            $this->PDOInstance = new \PDO(
                DB_DRIVER . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PWD,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]
            );
        } catch (\Exception $e) {
            die('Error sql' . $e->getMessage());
        }
    }
    public static function connect()
    {
        if (!isset(self::$connection)) {
            self::$connection = new Database();
        }
        return self::$connection;
    }
    public function databasePrepare($sql, $array = [])
    {
        $stmt = $this->PDOInstance->prepare($sql);
        $stmt->execute($array);
        return $stmt;
    }
}
