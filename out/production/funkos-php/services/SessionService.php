<?php

namespace services;

use models\User;

require_once __DIR__ . '/../models/User.php';

class SessionService
{
    private static ?SessionService $instance = null;
    private static $expirationTime = 3600;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->checkSession();
        $this->initSession();
    }

    private function checkSession(): void
    {
        if (isset($_SESSION['last_activity'])) {
            $inactiveSeconds = time() - $_SESSION['last_activity'];
            if ($inactiveSeconds >= self::$expirationTime) {
                $this->logout();
            }
        }
    }

    public static function getInstance(): SessionService
    {
        if (self::$instance === null) {
            self::$instance = new SessionService();
        }
        return self::$instance;
    }


    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    private function initSession()
    {
        $_SESSION['visits'] ??= 0;
        $_SESSION['loggedIn'] ??= false;
        $_SESSION['last_activity'] = time();
        $_SESSION['isAdmin'] ??= false;
        $_SESSION['username'] ??= null;
        $_SESSION['lastLoginDate'] ??= null;

        $_SESSION['visits']++;

        $this->refreshLastActivity();
    }

    public function login(User $user)
    {
        $_SESSION['loggedIn'] = true;
        $_SESSION['lastLoginDate'] = date('Y-m-d H:i:s');
        $_SESSION['username'] = $user->username;
        $_SESSION['isAdmin'] = in_array('ADMIN', $user->roles);
        $this->refreshLastActivity();
    }

    private function refreshLastActivity()
    {
        $_SESSION['last_activity'] = time();
    }


    public function logout(): void
    {
        session_unset();
        session_destroy();
        $_SESSION = [];
        header('Location: /index.php');
        exit;
    }
}