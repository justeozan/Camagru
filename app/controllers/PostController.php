<?php

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
            $count = $postModel->getLikesCount($postId);
            echo json_encode(['success'=>true,'liked'=>$liked,'likes_count'=>$count]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success'=>false,'message'=>'Erreur serveur']);
        }
    }

    public function comment()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success'=>false,'message'=>'Non connecté']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD']!=='POST') {
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
            return;
        }
        $postId  = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        $userId  = $_SESSION['user_id'];
        if (!$postId || !$content) {
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>'Données manquantes']);
            return;
        }
        if (strlen($content)>500) {
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>'Commentaire trop long']);
            return;
        }
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        foreach (['<script','javascript:','onclick=','onerror='] as $pat) {
            if (stripos($content,$pat)!==false) {
                http_response_code(400);
                echo json_encode(['success'=>false,'message'=>'Contenu non autorisé']);
                return;
            }
        }
        $postModel = $this->model('Post');
        try {
            $ok = $postModel->addComment($postId,$userId,$content);
            if ($ok) {
                $this->sendCommentNotification($postId,$userId,$content);
                echo json_encode(['success'=>true]);
            } else {
                http_response_code(500);
                echo json_encode(['success'=>false,'message'=>'Erreur ajout']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success'=>false,'message'=>'Erreur serveur']);
        }
    }

    private function sendCommentNotification($postId, $commenterId, $commentContent)
    {
        try {
            $userModel  = $this->model('User');
            $author     = $userModel->getPostAuthor($postId);
            if (!$author || $author['id']==$commenterId || !$author['notify_on_comment']) {
                return;
            }
            $commenter = $userModel->getById($commenterId);
            if (!$commenter) return;
            $subject = "💬 Nouveau commentaire sur votre photo - Camagru";
            $link    = "http://localhost:8080/#post-".$postId;
            
            // Template HTML pour l'email
            $msg = "
            <!DOCTYPE html>
            <html lang=\"fr\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Nouveau commentaire - Camagru</title>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                    
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        line-height: 1.6;
                        color: #374151;
                        background-color: #f9fafb;
                    }
                    
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 12px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    
                    .header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 30px;
                        text-align: center;
                    }
                    
                    .header h1 {
                        font-size: 28px;
                        font-weight: 700;
                        margin-bottom: 8px;
                    }
                    
                    .header p {
                        font-size: 16px;
                        opacity: 0.9;
                    }
                    
                    .content {
                        padding: 40px 30px;
                    }
                    
                    .greeting {
                        font-size: 18px;
                        font-weight: 500;
                        margin-bottom: 20px;
                        color: #1f2937;
                    }
                    
                    .notification-box {
                        background-color: #f0f9ff;
                        border: 1px solid #e0f2fe;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 25px 0;
                    }
                    
                    .commenter {
                        display: flex;
                        align-items: center;
                        margin-bottom: 15px;
                    }
                    
                    .commenter-avatar {
                        width: 40px;
                        height: 40px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: 600;
                        font-size: 16px;
                        margin-right: 12px;
                    }
                    
                    .commenter-info {
                        flex: 1;
                    }
                    
                    .commenter-name {
                        font-weight: 600;
                        color: #1f2937;
                        margin-bottom: 2px;
                    }
                    
                    .comment-time {
                        font-size: 12px;
                        color: #6b7280;
                    }
                    
                    .comment-content {
                        background-color: #ffffff;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 15px;
                        margin-top: 15px;
                        font-size: 15px;
                        line-height: 1.5;
                    }
                    
                    .cta-button {
                        display: inline-block;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        padding: 14px 28px;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 16px;
                        margin: 25px 0;
                        transition: transform 0.2s;
                    }
                    
                    .cta-button:hover {
                        transform: translateY(-1px);
                    }
                    
                    .footer {
                        background-color: #f9fafb;
                        padding: 25px 30px;
                        text-align: center;
                        border-top: 1px solid #e5e7eb;
                    }
                    
                    .footer p {
                        font-size: 14px;
                        color: #6b7280;
                        margin-bottom: 10px;
                    }
                    
                    .footer a {
                        color: #667eea;
                        text-decoration: none;
                    }
                    
                    .social-links {
                        margin-top: 20px;
                    }
                    
                    .social-links a {
                        display: inline-block;
                        margin: 0 10px;
                        color: #9ca3af;
                        text-decoration: none;
                        font-size: 14px;
                    }
                    
                    @media (max-width: 600px) {
                        .container {
                            margin: 0;
                            border-radius: 0;
                        }
                        
                        .content {
                            padding: 25px 20px;
                        }
                        
                        .header {
                            padding: 25px 20px;
                        }
                        
                        .header h1 {
                            font-size: 24px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"header\">
                        <h1>📸 Camagru</h1>
                        <p>Nouveau commentaire sur votre photo</p>
                    </div>
                    
                    <div class=\"content\">
                        <div class=\"greeting\">
                            Bonjour " . htmlspecialchars($author['username']) . " ! 👋
                        </div>
                        
                        <p>Bonne nouvelle ! Quelqu'un a commenté votre photo sur Camagru.</p>
                        
                        <div class=\"notification-box\">
                            <div class=\"commenter\">
                                <div class=\"commenter-avatar\">
                                    " . strtoupper(substr($commenter['username'], 0, 1)) . "
                                </div>
                                <div class=\"commenter-info\">
                                    <div class=\"commenter-name\">" . htmlspecialchars($commenter['username']) . "</div>
                                    <div class=\"comment-time\">" . date('d/m/Y à H:i') . "</div>
                                </div>
                            </div>
                            
                            <div class=\"comment-content\">
                                💬 \"" . htmlspecialchars($commentContent) . "\"
                            </div>
                        </div>
                        
                        <div style=\"text-align: center;\">
                            <a href=\"" . $link . "\" class=\"cta-button\">
                                👀 Voir le commentaire
                            </a>
                        </div>
                        
                        <p style=\"margin-top: 25px; color: #6b7280; font-size: 14px;\">
                            Vous recevez cet email car vous avez activé les notifications pour les commentaires. 
                            Vous pouvez désactiver ces notifications dans vos 
                            <a href=\"http://localhost:8080/user/account\" style=\"color: #667eea;\">paramètres de compte</a>.
                        </p>
                    </div>
                    
                    <div class=\"footer\">
                        <p>
                            Cet email a été envoyé automatiquement par <strong>Camagru</strong><br>
                            Votre réseau social de partage de photos
                        </p>
                        
                        <div class=\"social-links\">
                            <a href=\"http://localhost:8080/\">🏠 Accueil</a>
                            <a href=\"http://localhost:8080/create\">📸 Créer</a>
                            <a href=\"http://localhost:8080/user/account\">⚙️ Paramètres</a>
                        </div>
                        
                        <p style=\"margin-top: 15px; font-size: 12px; color: #9ca3af;\">
                            © 2024 Camagru - Tous droits réservés
                        </p>
                    </div>
                </div>
            </body>
            </html>";
            
            $hdrs    = "MIME-Version: 1.0\r\n"
                     . "Content-type: text/html; charset=UTF-8\r\n"
                     . "From: Camagru <noreply@camagru.local>\r\n"
                     . "Reply-To: support@camagru.local\r\n";
            mail($author['email'],$subject,$msg,$hdrs);
        } catch (Exception $e) {
            error_log("Notif erreur: ".$e->getMessage());
        }
    }

    public function comments()
    {
        if ($_SERVER['REQUEST_METHOD']!=='GET') {
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
            return;
        }
        $postId = $_GET['post_id'] ?? null;
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>'Post ID manquant']);
            return;
        }
        try {
            $comments = $this->model('Post')->getComments($postId);
            header('Content-Type: application/json');
            echo json_encode(['success'=>true,'comments'=>$comments]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success'=>false,'message'=>'Erreur serveur']);
        }
    }

    public function uploadWithOverlay()
    {
        header('Content-Type: application/json');
        
        // Vérification de l'authentification et de la vérification (compatible AJAX)
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Vous devez être connecté pour effectuer cette action.'];
            echo json_encode(['success' => false, 'redirect' => '/user/login']);
            return;
        }
        
        require_once '../app/models/User.php';
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        
        if (!$user || !$user['is_verified']) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Vous devez confirmer votre compte pour effectuer cette action.'];
            echo json_encode(['success' => false, 'redirect' => '/user/confirm']);
            return;
        }
        
        // Vérification de l'upload
        if (!isset($_FILES['photo'])||$_FILES['photo']['error']!==UPLOAD_ERR_OK) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur lors de l\'upload du fichier.'];
            echo json_encode(['success' => false, 'redirect' => '/create']);
            return;
        }
        
        $caption     = trim($_POST['caption']??'');
        $userId      = $_SESSION['user_id'];
        $overlayFile = $_POST['overlay_file'] ?? null;
        $x           = (int)($_POST['overlay_x'] ?? 0);
        $y           = (int)($_POST['overlay_y'] ?? 0);
        $w           = (int)($_POST['overlay_width']  ?? 80);
        $h           = (int)($_POST['overlay_height'] ?? 80);

        // Validation de la taille
        if ($_FILES['photo']['size']>5*1024*1024) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Le fichier est trop volumineux (maximum 5MB).'];
            echo json_encode(['success' => false, 'redirect' => '/create']);
            return;
        }
        
        // Validation du type MIME
        $allowed = ['image/jpeg','image/png','image/gif'];
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo,$_FILES['photo']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mimeType,$allowed)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.'];
            echo json_encode(['success' => false, 'redirect' => '/create']);
            return;
        }

        try {
            $final = $this->processImageWithOverlay(
                $_FILES['photo']['tmp_name'],
                $overlayFile,
                $x,$y,$w,$h
            );
            if ($final) {
                $pm = $this->model('Post');
                $imgPath = '/uploads/'.basename($final);
                if ($pm->create($userId,$imgPath,$caption)) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Photo publiée avec succès !'];
                    echo json_encode(['success' => true, 'redirect' => '/']);
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur lors de la sauvegarde en base de données.'];
                    echo json_encode(['success' => false, 'redirect' => '/create']);
                }
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur lors du traitement de l\'image.'];
                echo json_encode(['success' => false, 'redirect' => '/create']);
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur serveur lors du traitement.'];
            echo json_encode(['success' => false, 'redirect' => '/create']);
        }
    }

    private function processImageWithOverlay($photoPath,$overlayFile,$x,$y,$w,$h)
    {
        if (!extension_loaded('gd')) return false;
        $info = getimagesize($photoPath);
        if (!$info) return false;
        switch ($info[2]) {
            case IMAGETYPE_JPEG: $main = imagecreatefromjpeg($photoPath); break;
            case IMAGETYPE_PNG:  $main = imagecreatefrompng($photoPath);  break;
            case IMAGETYPE_GIF:  $main = imagecreatefromgif($photoPath);  break;
            default: return false;
        }
        imagealphablending($main,true);
        imagesavealpha($main,true);

        // overlay
        if ($overlayFile) {
            $path = '../public/assets/overlays/'.basename($overlayFile);
            if (file_exists($path)) {
                $ov = imagecreatefrompng($path);
                if ($ov) {
                    $res = imagecreatetruecolor($w,$h);
                    imagealphablending($res,false);
                    imagesavealpha($res,true);
                    $tr = imagecolorallocatealpha($res,0,0,0,127);
                    imagefill($res,0,0,$tr);
                    imagecopyresampled($res,$ov,0,0,0,0,$w,$h,imagesx($ov),imagesy($ov));
                    imagecopy($main,$res,$x,$y,0,0,$w,$h);
                    imagedestroy($ov);
                    imagedestroy($res);
                }
            }
        }

        $up = '/var/www/html/uploads/';
        $fn = $up.uniqid('composed_').'.png';
        imagesavealpha($main,true);
        $ok = imagepng($main,$fn);
        imagedestroy($main);
        return $ok ? $fn : false;
    }

    public function myGallery()
    {
        $this->requireVerify(); // Vérifie que l'utilisateur est connecté et validé

        // Numéro de page courant (GET ?page=)
        $page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 12;                       // Nombre d'images par page
        $offset  = ($page - 1) * $perPage;

        $postModel = $this->model('Post');
        $userId    = $_SESSION['user_id'];

        // Récupère les posts de l'utilisateur, avec pagination
        $posts = $postModel->getUserPosts($userId, $perPage, $offset);
        $total = $postModel->countUserPosts($userId);
        $pages = ceil($total / $perPage);

        // Affiche la vue correspondante
        require_once '../app/views/myposts.php';
    }

    public function delete()
    {
        $this->requireVerify();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $input  = json_decode(file_get_contents('php://input'), true);
        $postId = $input['post_id'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Post ID manquant']);
            return;
        }

        $pm = $this->model('Post');
        if ($pm->deletePost($postId, $userId)) {
            echo json_encode(['success' => true, 'message' => 'Photo supprimée']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur suppression']);
        }
    }
}
