<?php

namespace source\Model;

use Exception;
use PDO;
use PDOException;
use source\Model\ConnectionDB;

class TransactionsDao
{
    /**
     * @var PDO
     */
    private $pdo;
    public function __construct()
    {
        $this->pdo = ConnectionDB::getConnection();
    }
    public function readAllTransactions($idUser): array
    {
        try {
            $query = "SELECT * FROM transactions WHERE id_user = :idUser";
            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':idUser', $idUser);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        } catch (PDOException $e) {
            throw new Exception('Internal server error, Database Error: ' . $e->getMessage(), 500);
        }
    }
    public function readRevenuesOrExpenses($idUser, $typeTransaction): array
    {
        try {
            $query = "SELECT * FROM transactions WHERE id_user = :idUser AND type_transaction = :typeTransaction";
            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':idUser', $idUser);
            $sql->bindValue(':typeTransaction', $typeTransaction);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        } catch (PDOException $e) {
            throw new Exception('Internal server error, Database Error: ' . $e->getMessage(), 500);
        }
    }
    public function addTransaction($idUser, $typeTransaction, $category, $valueTransaction, $descriptionTransaction, $timestampTransaction): void
    {
        try {
            $query = "INSERT INTO transactions (id_user, type_transaction, category, `value`, `description`, created_at) VALUES (:idUser, :typeTransaction, :category, :valueTransaction, :descriptionTransaction, :timestampTransaction)";
            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':idUser', $idUser);
            $sql->bindValue(':typeTransaction', $typeTransaction);
            $sql->bindValue(':category', $category);
            $sql->bindValue(':valueTransaction', $valueTransaction);
            $sql->bindValue(':descriptionTransaction', $descriptionTransaction);
            $sql->bindValue(':timestampTransaction', $timestampTransaction);
            $sql->execute();
        } catch (PDOException $e) {
            throw new Exception('Internal server error, Database Error: ' . $e->getMessage(), 500);
        }
    }
    public function editTransaction($idUser, $idTransaction, $category, $description, $value): void
    {
        try {
            $query = "UPDATE transactions SET category = :category, `value` = :valueTransaction, `description` = :descriptionTransaction WHERE id_user = :idUser AND id_transaction = :idTransaction";
            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':idUser', $idUser);
            $sql->bindValue(':idTransaction', $idTransaction);
            $sql->bindValue(':category', $category);
            $sql->bindValue(':descriptionTransaction', $description);
            $sql->bindValue(':valueTransaction', $value);
            $sql->execute();
        } catch(PDOException $e){
            throw new Exception('Internal server error, Database Error: ' . $e->getMessage(), 500);
        }
    }
    public function deleteTransaction($idUser, $idTransaction): void
    {
        try {
            $query = 'DELETE FROM transactions WHERE id_user = :idUser AND id_transaction = :idTransaction';
            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':idUser', $idUser);
            $sql->bindValue(':idTransaction', $idTransaction);
            $sql->execute();
        } catch(PDOException $e){
            throw new Exception('Internal server error, Database Error: ' . $e->getMessage(), 500);
        }
    }
}
