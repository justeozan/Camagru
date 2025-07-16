<?php

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

    public function loadMorePosts()
    {
        // Endpoint AJAX pour pagination infinie
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        // Récupération et validation des paramètres
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 5; // Respecter l'exigence minimum 5 éléments par page
        $offset = ($page - 1) * $perPage;
        
        // Vérifier si l'utilisateur est connecté pour l'état des likes
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        try {
            $postModel = $this->model('Post');
            
            // Récupérer les posts avec pagination
            $posts = $postModel->getPaginated($perPage, $offset, $userId);
            $total = $postModel->countAll();
            $hasMore = ($offset + $perPage) < $total;
            
            // Génération du HTML pour chaque post
            $postsHtml = [];
            foreach ($posts as $post) {
                ob_start();
                $this->renderPostCard($post, $userId);
                $postsHtml[] = ob_get_clean();
            }
            
            echo json_encode([
                'success' => true,
                'posts' => $postsHtml,
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalPosts' => $total
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }
    
    private function renderPostCard($post, $userId)
    {
        // Template HTML pour un post (extraction du code existant)
        ?>
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" data-post-id="<?= $post['id'] ?>">
            <!-- Post Header -->
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            <?= strtoupper(substr($post['username'], 0, 1)) ?>
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">@<?= htmlspecialchars($post['username']) ?></p>
                        <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($post['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Post Image -->
            <div class="cursor-pointer" onclick="openModal('<?= htmlspecialchars($post['image_path']) ?>', '<?= htmlspecialchars($post['username']) ?>')">
                <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                     alt="Photo de <?= htmlspecialchars($post['username']) ?>" 
                     class="w-full h-auto object-cover">
            </div>

            <!-- Post Actions -->
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-4">
                        <?php if ($userId): ?>
                            <button onclick="toggleLike(<?= $post['id'] ?>)" 
                                    class="like-btn flex items-center space-x-1 text-gray-700 hover:text-red-500 transition"
                                    data-post-id="<?= $post['id'] ?>"
                                    data-liked="<?= $post['user_has_liked'] ? 'true' : 'false' ?>">
                                <svg class="w-6 h-6 <?= $post['user_has_liked'] ? 'text-red-500 fill-current' : 'text-gray-700' ?>" 
                                     fill="<?= $post['user_has_liked'] ? 'currentColor' : 'none' ?>" 
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        <?php else: ?>
                            <button onclick="showLoginAlert()" class="flex items-center space-x-1 text-gray-700 hover:text-red-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($userId): ?>
                            <button onclick="focusComment(<?= $post['id'] ?>)" class="flex items-center space-x-1 text-gray-700 hover:text-blue-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </button>
                        <?php else: ?>
                            <button onclick="showLoginAlert()" class="flex items-center space-x-1 text-gray-700 hover:text-blue-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Likes and Comments Count -->
                <div class="mb-3">
                    <p class="likes-count font-semibold text-sm text-gray-900" data-post-id="<?= $post['id'] ?>">
                        <?= $post['likes_count'] ?> J'aime
                    </p>
                </div>

                <!-- Caption -->
                <?php if (!empty($post['caption'])): ?>
                    <div class="text-sm">
                        <span class="font-semibold">@<?= htmlspecialchars($post['username']) ?></span>
                        <span class="ml-1"><?= htmlspecialchars($post['caption']) ?></span>
                    </div>
                <?php endif; ?>
                
                <!-- Comments -->
                <button onclick="toggleComments(<?= $post['id'] ?>)" class="text-gray-500 text-sm mt-1 hover:text-gray-700">
                    <?php if ($post['comments_count'] > 0): ?>
                        Voir les <?= $post['comments_count'] ?> commentaires
                    <?php else: ?>
                        Voir les commentaires
                    <?php endif; ?>
                </button>
                
                <!-- Comments section (hidden by default) -->
                <div id="comments-<?= $post['id'] ?>" class="comments-section hidden mt-3 pt-3 border-t border-gray-100">
                    <div class="space-y-2 mb-3" id="comments-list-<?= $post['id'] ?>">
                        <!-- Comments will be loaded here -->
                    </div>
                </div>
                
                <!-- Add comment -->
                <?php if ($userId): ?>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <input type="text" 
                                   id="comment-input-<?= $post['id'] ?>" 
                                   placeholder="Ajouter un commentaire..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   onkeypress="handleCommentSubmit(event, <?= $post['id'] ?>)">
                            <button onclick="submitComment(<?= $post['id'] ?>)" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                Publier
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

}