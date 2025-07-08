<?php

// Démarrer la session pour vérifier l'état de connexion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class HomeController extends Controller
{
    public function index()
    {
        // Pagination selon l'exigence : au moins 5 éléments par page
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;
        
        // Récupérer les posts pour la page d'accueil
        $postModel = $this->model('Post');
        
        // Vérifier si l'utilisateur est connecté pour l'état des likes
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        // Utiliser la pagination au lieu de getAll()
        $posts = $postModel->getPaginated($perPage, $offset, $userId);
        $total = $postModel->countAll();
        $pages = ceil($total / $perPage);
        
        require_once '../app/views/home.php';
    }

    public function about()
    {
        // Code pour la page "À propos"
    }

}