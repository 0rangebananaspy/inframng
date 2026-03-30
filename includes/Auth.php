<?php
class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($username, $password) {
        $users = $this->db->read('users');
        
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['last_activity'] = time();
                    return true;
                }
            }
        }
        return false;
    }

    public function logout() {
        session_destroy();
        session_start();
    }

    public function isLoggedIn() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }
        
        $config = require __DIR__ . '/../config/config.php';
        if (time() - $_SESSION['last_activity'] > $config['session_timeout']) {
            $this->logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }

    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function isOperator() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'operator';
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $users = $this->db->read('users');
        foreach ($users as $user) {
            if ($user['id'] === $_SESSION['user_id']) {
                return $user;
            }
        }
        return null;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
}

function requireAuth() {
    $auth = new Auth();
    if (!$auth->isLoggedIn()) {
        header('Location: /modules/auth/login.php');
        exit;
    }
}

function requireAdmin() {
    $auth = new Auth();
    if (!$auth->isAdmin()) {
        header('Location: /dashboard/index.php');
        exit;
    }
}
