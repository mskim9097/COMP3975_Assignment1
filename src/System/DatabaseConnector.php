<?php
namespace Src\System;

class DatabaseConnector {
    private $dbConnection = null;

    public function __construct() {
        try {
            $dbPath = __DIR__ . '/../../cms-db.sqlite';

            $this->dbConnection = new \PDO('sqlite:' . $dbPath);

            $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection() {
        return $this->dbConnection;
    }
}