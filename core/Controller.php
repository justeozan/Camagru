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

    // reusable error handling methods
    protected function setToastError($message, $redirectTo = null) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => $message];
        if ($redirectTo) {
            header("Location: $redirectTo");
            exit();
        }
    }

    protected function setToastSuccess($message, $redirectTo = null) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => $message];
        if ($redirectTo) {
            header("Location: $redirectTo");
            exit();
        }
    }

    public function model($model) {
        require_once "../app/models/$model.php";
        return new $model();
    }

    protected function notFound($message = null) {
        Router::notFound($message);
    }
}
