<?php

class CreateController extends Controller
{
    public function index()
    {
        $this->requireVerify(); // Require verified user to create content
        
        // Récupérer les dernières photos de l'utilisateur pour les miniatures
        $postModel = $this->model('Post');
        $userId = $_SESSION['user_id'];
        
        // Récupérer les 8 dernières photos de l'utilisateur
        $userPosts = $postModel->getUserPosts($userId, 8, 0);
        
        require_once '../app/views/create.php';
    }


}