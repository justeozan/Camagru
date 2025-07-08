<?php

// Démarrer la session pour toutes les méthodes
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PostController extends Controller
{
    public function like()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        $action = $_POST['action'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$postId || !$action) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }

        $postModel = $this->model('Post');

        try {
            if ($action === 'like') {
                $postModel->addLike($postId, $userId);
                $liked = true;
            } else {
                $postModel->removeLike($postId, $userId);
                $liked = false;
            }

            $likesCount = $postModel->getLikesCount($postId);

            echo json_encode([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function comment()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        $userId = $_SESSION['user_id'];

        if (!$postId || !$content) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }

        // 🔒 SÉCURITÉ: Validation du contenu des commentaires
        if (strlen($content) > 500) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Commentaire trop long (max 500 caractères)']);
            return;
        }
        
        // Filtrer le contenu pour éviter les injections HTML/JS
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Validation supplémentaire - interdire certains patterns dangereux
        $dangerousPatterns = ['<script', 'javascript:', 'onclick=', 'onerror='];
        foreach ($dangerousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Contenu non autorisé']);
                return;
            }
        }

        $postModel = $this->model('Post');

        try {
            $result = $postModel->addComment($postId, $userId, $content);
            
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function comments()
    {
        // Debug: Vérification méthode
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée - Méthode reçue: ' . $_SERVER['REQUEST_METHOD']]);
            return;
        }

        // Debug: Vérification des paramètres
        $postId = $_GET['post_id'] ?? null;
        
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Post ID manquant - Paramètres reçus: ' . json_encode($_GET)]);
            return;
        }

        $postModel = $this->model('Post');

        try {
            $comments = $postModel->getComments($postId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'comments' => $comments
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }

    // Version alternative pour tester
    public function getComments()
    {
        // Forcer le content-type JSON
        header('Content-Type: application/json');
        
        // Parser manuellement les paramètres GET depuis REQUEST_URI
        $requestUri = $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($requestUri);
        $queryParams = [];
        
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }
        
        // Vérifier la méthode
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        // Récupérer le post_id depuis les paramètres parsés manuellement
        $postId = isset($queryParams['post_id']) ? (int)$queryParams['post_id'] : null;
        
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Post ID manquant']);
            exit;
        }

        try {
            $postModel = $this->model('Post');
            $comments = $postModel->getComments($postId);
            
            echo json_encode([
                'success' => true,
                'comments' => $comments
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
        exit;
    }

    public function upload()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non connecté']);
            return;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors du téléchargement']);
            return;
        }

        $caption = trim($_POST['caption'] ?? '');
        $userId = $_SESSION['user_id'];

        // 🔒 SÉCURITÉ: Validation stricte des fichiers
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB max
        
        // Vérifier la taille
        if ($_FILES['photo']['size'] > $maxFileSize) {
            http_response_code(400);
            echo json_encode(['error' => 'Fichier trop volumineux (max 5MB)']);
            return;
        }
        
        // Vérifier le type MIME réel du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['photo']['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Type de fichier non autorisé. Seules les images sont acceptées.']);
            return;
        }
        
        // Déterminer l'extension sécurisée basée sur le MIME type
        $extensionMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png', 
            'image/gif' => 'gif'
        ];
        $safeExtension = $extensionMap[$mimeType];

        // Vérifier que le dossier uploads existe
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom de fichier sécurisé
        $fileName = uniqid('post_') . '.' . $safeExtension;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
            $postModel = $this->model('Post');
            $imagePath = '/uploads/' . $fileName;
            
            if ($postModel->create($userId, $imagePath, $caption)) {
                echo json_encode(['success' => true, 'message' => 'Photo publiée avec succès !']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors de la sauvegarde en base de données']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement du fichier']);
        }
    }

    // Méthode de test pour vérifier le routage
    public function test()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'PostController::test() fonctionne !',
            'method' => $_SERVER['REQUEST_METHOD'],
            'get_params' => $_GET,
            'post_params' => $_POST
        ]);
    }
} 