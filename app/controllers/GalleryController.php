<?php

// Démarrer la session pour l'état des likes
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class GalleryController extends Controller
{
	public function gallery() {
		$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
		$perPage = 5;
		$offset = ($page - 1) * $perPage;
	
		$postModel = $this->model('Post');
		
		// Vérifier si l'utilisateur est connecté pour l'état des likes
		$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		$posts = $postModel->getPaginated($perPage, $offset, $userId);
		$total = $postModel->countAll();
		$pages = ceil($total / $perPage);
	
		require_once "../app/views/gallery.php";
	}
	
}