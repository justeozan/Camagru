<?php

class Comment extends Model 
{
    public function getByPostId($postId)
    {
        $stmt = $this->db->prepare("
            SELECT comments.*, users.username 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.post_id = ? 
            ORDER BY comments.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($postId, $userId, $content)
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (post_id, user_id, content) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function countByPostId($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM comments WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchColumn();
    }
} 