<?php

namespace services;

use config\Config;
use models\Funko;
use PDO;

require_once __DIR__ . '/../models/Funko.php';

class FunkoService
{
    private PDO $pdo;
    private static ?FunkoService $instance = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function getFunkos(string $search = null): array
    {
        $sql = "SELECT f.*, c.name as category_name FROM funkos f JOIN categories c ON f.category_id = c.id order by f.id";
        if ($search) {
            $sql .= " WHERE lower(f.name) LIKE %lower(:search)%";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($search) {
            $stmt->bindValue('search', "%$search%");
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Funko::class);
        return $stmt->fetchAll();
    }

    public static function getInstance(PDO $db): FunkoService
    {
        if (self::$instance == null) {
            self::$instance = new FunkoService($db);
        }
        return self::$instance;
    }

    public function create(Funko $funko): void
    {
        $sql = "INSERT INTO funkos (name, price, stock, category_id, image) VALUES (:name, :price, :stock, :category_id, :image)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('name', $funko->name);
        $stmt->bindValue('price', $funko->price);
        $stmt->bindValue('stock', $funko->stock);
        $stmt->bindValue('category_id', $funko->category_id);
        $stmt->bindValue('image', Funko::DEFAULT_IMAGE);
        $stmt->execute();
    }

    public function update(Funko $funko): void
    {
        $sql = "UPDATE funkos SET name = :name, price = :price, stock = :stock, category_id = :category_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('name', $funko->name);
        $stmt->bindValue('price', $funko->price);
        $stmt->bindValue('stock', $funko->stock);
        $stmt->bindValue('category_id', $funko->category_id);
        $stmt->bindValue('id', $funko->id);
        $stmt->execute();
    }


    public function existsById($id)
    {
        $sql = "SELECT * FROM funkos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }


    public function updateImage(Funko $funko, $image)
    {
        $uploadDir = Config::getInstance()->uploadPath;
        $imageName = $funko->id;

        if (move_uploaded_file($image['tmp_name'], $uploadDir . $imageName)) {
            $sql = "UPDATE funkos SET image = :image WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue('image', $imageName);
            $stmt->bindValue('id', $funko->id);
            $stmt->execute();
            return true;
        } else {
            return false;
        }
    }


    public function remove(string $id): bool
    {
        $sql = "DELETE FROM funkos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getFunkoById(string $id): ?Funko
    {
        $sql = "SELECT f.*, c.name as category_name FROM funkos f JOIN categories c ON f.category_id = c.id WHERE f.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Funko::class);
        return $stmt->fetch();
    }
}
