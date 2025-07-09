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
            $subject = "Nouveau commentaire - Camagru";
            $link    = "http://localhost:8080/#post-".$postId;
            $msg     = "<html><body>...votre template HTML...</body></html>";
            $hdrs    = "MIME-Version: 1.0\r\n"
                     . "Content-type: text/html; charset=UTF-8\r\n"
                     . "From: Camagru <noreply@camagru.local>\r\n";
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
        $this->requireVerify();
        if (!isset($_FILES['photo'])||$_FILES['photo']['error']!==UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error'=>'Erreur upload']);
            return;
        }
        $caption     = trim($_POST['caption']??'');
        $userId      = $_SESSION['user_id'];
        $overlayFile = $_POST['overlay_file'] ?? null;
        $x           = (int)($_POST['overlay_x'] ?? 0);
        $y           = (int)($_POST['overlay_y'] ?? 0);
        $w           = (int)($_POST['overlay_width']  ?? 80);
        $h           = (int)($_POST['overlay_height'] ?? 80);

        // validation
        $allowed = ['image/jpeg','image/png','image/gif'];
        if ($_FILES['photo']['size']>5*1024*1024) {
            http_response_code(400);
            echo json_encode(['error'=>'Fichier trop gros']);
            return;
        }
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo,$_FILES['photo']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mimeType,$allowed)) {
            http_response_code(400);
            echo json_encode(['error'=>'Type non autorisé']);
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
                    echo json_encode(['success'=>true]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error'=>'Erreur BDD']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['error'=>'Traitement échoué']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error'=>'Erreur serveur']);
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

        $up = '../uploads/';
        if (!is_dir($up)) mkdir($up,0755,true);
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
