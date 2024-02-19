<?php

namespace services;
require_once __DIR__ . '/../models/Category.php';

use models\Category;
use PDO;

class CategoryService
{
    private PDO $pdo;

    private static $instance;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public static function getInstance(PDO $pdo): CategoryService
    {
        if (!self::$instance) {
            self::$instance = new CategoryService($pdo);
        }
        return self::$instance;
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



    public function getCategoryById(string $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Category::class);
        return $stmt->fetchObject();
    }


    public function hasFunkos(string $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM funkos WHERE category_id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function createCategory(string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindValue('name', $name);
        $stmt->execute();
    }

    public function deleteCategory(string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function updateCategory(string $id, string $name): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->bindValue('name', $name);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function softDelete(string $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_deleted = true WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function restore(string $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_deleted = false WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public function exists(string $name): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = :name");
        $stmt->bindValue('name', $name);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


}