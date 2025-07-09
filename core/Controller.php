<?php

class Controller {
    
    public function __construct() {
        // Démarrer la session seulement si elle n'est pas déjà active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /user/login");
            exit();
        }
    }

    protected function requireVerify() {
        $this->requireAuth(); // First ensure user is logged in
        
        // Get user data to check verification status
        require_once '../app/models/User.php';
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        
        if (!$user || !$user['is_verified']) {
            header("Location: /user/confirm");
            exit();
        }
    }

    public function model($model) {
        require_once "../app/models/$model.php";
        return new $model();
    }

    // Déclencher une erreur 404
    protected function notFound($message = null)
    {
        Router::notFound($message);
    }
}
