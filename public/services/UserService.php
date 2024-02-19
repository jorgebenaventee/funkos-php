<?php

namespace services;

use Exception;
use models\User;
use PDO;

class UserService
{
    private PDO $db;
    private static $instance;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public static function getInstance(PDO $pdo): UserService
    {
        if (!self::$instance) {
            self::$instance = new UserService($pdo);
        }
        return self::$instance;
    }

    public function authenticate($username, $password): ?User
    {

        $user = $this->findUserByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        throw new Exception('Usuario o contraseña no válidos');
    }

    private function findUserByUsername($username): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userRow) {
            return null;
        }

        $stmtRoles = $this->db->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = :user_id");
        $stmtRoles->bindParam(':user_id', $userRow['id']);
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);

        return new User(
            $userRow['id'],
            $userRow['username'],
            $userRow['password'],
            $roles
        );
    }

    public function register($username, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->execute();
        $userRolStmt = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, 2)");
        $lastInsertId = $this->db->lastInsertId();
        $userRolStmt->bindParam(':user_id', $lastInsertId);
        $userRolStmt->execute();
    }
}