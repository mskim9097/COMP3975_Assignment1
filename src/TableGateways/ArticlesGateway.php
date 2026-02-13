<?php
namespace Src\TableGateways;

class ArticlesGateway {
    private $db = null;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $statement = "
            SELECT 
                id, title, content, created_at, updated_at
            FROM
                articles;
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
                id, title, content, created_at, updated_at
            FROM
                articles
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
            INSERT INTO articles 
                (title, content)
            VALUES
                (:title, :content);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'title'   => $input['title'],
                'content' => $input['content'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input) {
        $statement = "
            UPDATE articles
            SET 
                title = :title,
                content = :content
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id'      => (int) $id,
                'title'   => $input['title'],
                'content' => $input['content'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id) {
        $statement = "
            DELETE FROM articles
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