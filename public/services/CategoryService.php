<?php

namespace services;

use PDO;

class CategoryService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function getCategories($includeInactives = false): array
    {
        $query = "SELECT * FROM categories";
        if (!$includeInactives) {
            $query .= " WHERE is_deleted = false";
        }
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function getCategory(string $name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE name = :name");
        $stmt->bindValue('name', $name);
        $stmt->execute();
        return $stmt->fetchObject('models\Category');
    }

    public function createCategory(string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindValue('name', $name);
        $stmt->execute();
    }

    public function deleteCategory(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function updateCategory(int $id, string $name): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->bindValue('name', $name);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_deleted = true WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

}