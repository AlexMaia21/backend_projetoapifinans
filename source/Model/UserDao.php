<?php

namespace source\Model;

use Exception;
use PDO;
use source\Model\ConnectionDB;

class UserDao
{
    /**
     * @var PDO
     */
    private $pdo;
    public function __construct()
    {
        $this->pdo = ConnectionDB::getConnection();
    }
    public function selectUserById($id)
    {
        try {
            $sql = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id');
            $sql->bindValue(':id', $id);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                return $sql->fetch(\PDO::FETCH_ASSOC);
            }
            return [];
        } catch (\PDOException $e) {
            throw new Exception('Internal Server Error: Database Error' . $e->getMessage(), 500);
        }
    }
    public function selectUserByEmail($email): array
    {
        try {
            $sql = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $sql->bindValue(':email', $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                return $sql->fetch(\PDO::FETCH_ASSOC);
            }
            return [];
        } catch (\PDOException $e) {
            throw new Exception('Internal Server Error: Database Error' . $e->getMessage(), 500);
        }
    }
    public function createUser($id, $username, $email, $password_hash): void
    {
        try {
            $sql = $this->pdo->prepare('INSERT INTO users (id_user, username, email, password_hash) VALUES (:id_user, :username, :email, :password_hash)');
            $sql->bindValue(':id_user', $id);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':password_hash', $password_hash);
            $sql->execute();
            $sql->errorInfo();
        } catch (\PDOException $e) {
            $codeError = $sql->errorInfo()[1];
            switch ($codeError) {
                case 1062:
                    throw new Exception('E-mail already registered', 422);
                default:
                    throw new Exception('Internal server error: ' . $e->getMessage(), 500);
            }
        }
    }
}
