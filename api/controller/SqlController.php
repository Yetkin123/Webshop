<?php

namespace Controller;

use \Resources\Config\Database;
use \PDO;
use \PDOException;

class SqlController {

    private static $pdo;
    private $stmt;
    private $sql;
    private $params = [];

    public static function setup(): SqlController
    {
        return new SqlController;
    }

    public function __construct()
    {
        $this->connectDatabase();
    }

    public function sql($sql): SqlController{
        $this->sql = $sql;
        return $this;
    }

    public function params($params): SqlController{
        $this->params = $params;
        return $this;
    }

    public function run($expectResult = true){
        $this->stmt = self::$pdo->prepare($this->sql);
        $this->stmt->execute($this->params);

        if ($expectResult){
            $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
            return $data;
        }
    }

    private function connectDatabase()
    {
        if (self::$pdo === null){
            $conn = 'mysql:host=' . Database::DB_HOST . ';dbname=' . Database::DB_NAME;

            try {
                self::$pdo = new PDO($conn, Database::DB_USER, Database::DB_PASS);
            } catch (PDOException $e) {
                var_dump($e->getMessage());
            }
        }
    }
}
