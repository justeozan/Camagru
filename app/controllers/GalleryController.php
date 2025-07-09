<?php

class GalleryController extends Controller
{
	// Méthode par défaut : redirige vers gallery
	public function index() {
		$this->gallery();
	}

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

	// Exemple de méthode avec gestion 404 : voir un post spécifique
	public function view($postId = null) {
		if (!$postId || !is_numeric($postId)) {
			$this->notFound("ID de post invalide");
			return;
		}

		$postModel = $this->model('Post');
		$post = $postModel->getById($postId);

		if (!$post) {
			$this->notFound("Post #$postId introuvable");
			return;
		}

		// Si le post existe, on peut l'afficher (pour une future page de détail)
		require_once "../app/views/post_detail.php";
	}
}