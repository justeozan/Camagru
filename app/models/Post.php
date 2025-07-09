<?php

class Post extends Model
{
    // ──────── GESTION DES POSTS ────────

    public function create($userId, $imagePath, $caption = null)
    {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, image_path, caption) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $imagePath, $caption]);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare($this->buildPostQuery("WHERE posts.id = ?"));
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($userId = null)
    {
        return $this->fetchPosts($userId);
    }

    public function getPaginated($limit = 5, $offset = 0, $userId = null)
    {
        return $this->fetchPosts($userId, $limit, $offset);
    }

    public function getUserPosts($userId, $limit = 5, $offset = 0)
    {
        $stmt = $this->db->prepare($this->buildPostQuery("WHERE posts.user_id = ?", "LIMIT $limit OFFSET $offset"));
        $stmt->execute([(int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    }

    public function countUserPosts($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
        $stmt->execute([(int) $userId]);
        return $stmt->fetchColumn();
    }

    public function deletePost($postId, $userId)
    {
        $stmt = $this->db->prepare("SELECT id, image_path FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([(int) $postId, (int) $userId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) return false;

        try {
            $this->db->beginTransaction();

            $this->db->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$postId]);
            $this->db->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$postId]);
            $this->db->prepare("DELETE FROM posts WHERE id = ?")->execute([$postId]);

            $imagePath = '../' . ltrim($post['image_path'], '/');
            if (file_exists($imagePath)) unlink($imagePath);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // ──────── GESTION DES LIKES ────────

    public function addLike($postId, $userId)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO likes (post_id, user_id) VALUES (?, ?)");
        return $stmt->execute([$postId, $userId]);
    }

    public function removeLike($postId, $userId)
    {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }

    public function getLikesCount($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchColumn();
    }

    public function isLikedByUser($postId, $userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    // ──────── GESTION DES COMMENTAIRES ────────

    public function addComment($postId, $userId, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function getComments($postId)
    {
        $stmt = $this->db->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ──────── OUTILS PRIVÉS ────────

    private function fetchPosts($userId = null, $limit = null, $offset = null)
    {
        $userLikeCheck = $userId ? "COUNT(DISTINCT user_likes.id) as user_has_liked" : "0 as user_has_liked";
        $userJoin = $userId ? "LEFT JOIN likes user_likes ON posts.id = user_likes.post_id AND user_likes.user_id = ?" : "";
        $pagination = ($limit !== null && $offset !== null) ? "LIMIT $limit OFFSET $offset" : "";

        $stmt = $this->db->prepare(
            "SELECT posts.*, users.username, COUNT(DISTINCT likes.id) as likes_count, COUNT(DISTINCT comments.id) as comments_count, $userLikeCheck
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            LEFT JOIN likes ON posts.id = likes.post_id
            LEFT JOIN comments ON posts.id = comments.post_id
            $userJoin
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
            $pagination"
        );

        if ($userId) {
            $stmt->execute([(int) $userId]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildPostQuery($where = '', $pagination = '')
    {
        return "SELECT posts.*, users.username, COUNT(DISTINCT likes.id) as likes_count, COUNT(DISTINCT comments.id) as comments_count
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                LEFT JOIN likes ON posts.id = likes.post_id
                LEFT JOIN comments ON posts.id = comments.post_id
                $where
                GROUP BY posts.id
                ORDER BY posts.created_at DESC
                $pagination";
    }
}
