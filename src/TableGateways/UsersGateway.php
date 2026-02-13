<?php
namespace Src\TableGateways;

class UsersGateway {
    private $db = null;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $statement = "
            SELECT 
                id, email, password_hash, created_at
            FROM
                users;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id) {
        $statement = "
            SELECT 
                id, email, password_hash, created_at
            FROM
                users
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input) {
        $statement = "
            INSERT INTO users 
                (email, password_hash)
            VALUES
                (:email, :password_hash);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'email' => $input['email'],
                'password_hash' => $input['password_hash'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input) {
        if (isset($input['password_hash'])) {
            $statement = "
                UPDATE users
                SET 
                    email = :email,
                    password_hash = :password_hash
                WHERE id = :id;
            ";
        } else {
            $statement = "
                UPDATE users
                SET 
                    email = :email
                WHERE id = :id;
            ";
        }

        try {
            $statement = $this->db->prepare($statement);

            $params = array(
                'id' => (int) $id,
                'email' => $input['email'],
            );

            if (isset($input['password_hash'])) {
                $params['password_hash'] = $input['password_hash'];
            }

            $statement->execute($params);
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id) {
        $statement = "
            DELETE FROM users
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}